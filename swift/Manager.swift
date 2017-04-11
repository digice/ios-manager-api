//
//  <#Model#>Manager.swift
//  iOS Manager API
//
//  Created by Roderic Linguri <linguri@digices.com> on 4/10/17.
//  Copyright © 2017 Digices LLC. All rights reserved.
//

import Foundation

protocol <#Model#>ManagerDelegate {
  func <#model#>ManagerDidUpdate()
}

class <#Model#>Manager : ConnectionDelegate {

  static let shared : <#Model#>Manager = <#Model#>Manager()

  let model = "<#model#>"

  let defaults : UserDefaults = UserDefaults.standard

  let connection : Connection = Connection()

  var objects : [<#Model#>] = []

  var cursor : Int = 0

  var tableDelegate : <#Model#>ManagerDelegate?

  var viewDelegate : <#Model#>ManagerDelegate?

  var load<#Model#>AtCursor : Bool = false

  private init() {

    // load objects out of defaults
    if let data = self.defaults.object(forKey: "\(self.model)s") as? Data {
      self.objects = NSKeyedUnarchiver.unarchiveObject(with: data) as! [<#Model#>]
      self.cursor = 0
    }

    // set ourselves as the connection delegate
    self.connection.delegate = self

  }

  // LOCAL DATA MANIPULATION

  func sort() {
    if self.objects.count > 1 {
      self.objects.sort { $0.name < $1.name }
    }
  }

  func save() {
    self.sort()
    let data = NSKeyedArchiver.archivedData(withRootObject: self.objects)
    self.defaults.set(data, forKey: "\(self.model)s")
    self.defaults.synchronize()
  }

  func getIndexOfObjectById(id : Int) -> Int? {
    var i = 0
    for object in self.objects {
      if id == object.id {
        return i
      }
      i += 1
    }
    return nil
  }

  // DATABASE CRUD SEND - DOES NOT AFFECT LOCAL DATA

  func create(new<#Model#>: <#Model#>) {
    self.connection.setRequest(model: self.model, action: "create", params: new<#Model#>.dict())
    self.connection.sendRequest()
  }

  func read() {
    self.connection.setRequest(model: self.model, action: "read", params: ["id":"*"])
    self.connection.sendRequest()
    self.connection.sendRequest()
  }

  func update() {
    // make sure to set the cursor to the correct object
    let object = self.objects[self.cursor]
    self.connection.setRequest(model: self.model, action: "update", params: object.dict())
    self.connection.sendRequest()
  }

  func delete() {
    let object = self.objects[self.cursor]
    let id = object.id
    self.connection.setRequest(model: self.model, action: "delete", params: ["id":"\(id)"])
    self.connection.sendRequest()
  }

  // DATABASE CRUD RECEIVE - UPDATE LOCAL DATA

  func connectionDidCreate() {

    if self.connection.results.count > 0 {

      for dict in self.connection.results {
        let object = <#Model#>(dict: dict)
        self.objects.append(object)
      } // ./foreach result

      self.save()

      // notify table
      if let t = self.tableDelegate {
        t.<#model#>ManagerDidUpdate()
      }

      // notify view
      if let v = self.viewDelegate {
        v.<#model#>ManagerDidUpdate()
      }

      // reset connection
      self.connection.success = false

    } // ./ results dict is not empty

  }

  func connectionDidRead() {

    if self.connection.results.count > 0 {

      for dict in self.connection.results {

        let object = <#Model#>(dict: dict)

        if let index = self.getIndexOfObjectById(id: object.id) {

          // move cursor to the object that was read
          self.cursor = index

          // check update date, to see if update is required?

          // is this an authentication request?

        } // ./object is in objects

        else {
          let object = <#Model#>(dict: dict)
          self.objects.append(object)
        } // ./object not in objects

      } // ./foreach result

    } // ./ results dict is not empty

  }

  func connectionDidUpdate() {

    if self.connection.results.count > 0 {

      for dict in self.connection.results {

        let object = <#Model#>(dict: dict)

        if let index = self.getIndexOfObjectById(id: object.id) {
          // move the cursor to the position of the object
          self.cursor = index
          // replace the object
          self.objects[index] = object
        } // ./object is in objects

        else {

          // LOCAL UPDATE FAILED

        } // ./object not in objects

      } // ./foreach result

    } // ./ results dict is not empty

    if let t = self.tableDelegate {
      t.<#model#>ManagerDidUpdate()
    }

    if let v = self.viewDelegate {
      v.<#model#>ManagerDidUpdate()
    }

  }

  func connectionDidDelete() {

    if self.connection.results.count > 0 {

      for dict in self.connection.results {

        if let id = dict["id"] {

          if let idInt = Int(id) {

            if let index = self.getIndexOfObjectById(id: idInt) {

              // replace the object
              self.objects.remove(at: index)

            } // ./object is in objects

            else {

              // LOCAL DELETE FAILED

            } // ./object not in objects

          } // ./id resolves to integer

        } // ./id in dict

      } // ./foreach result

    } // ./ results dict is not empty

    if let t = self.tableDelegate {
      t.<#model#>ManagerDidUpdate()
    }

    if let v = self.viewDelegate {
      v.<#model#>ManagerDidUpdate()
    }

  }

  // MARK: - ConnectionDelegate Methods
  
  func connectionDidReceiveError() {
    // notify dlegate?
  }
  
  func connectionDidReceiveResponse() {

    if self.connection.success == true {
      
      if let a = self.connection.action {

        switch a {
        case "create":
          self.connectionDidCreate()
          break
        case "read":
          // self.connectionDidRead()
          break
        case "update":
          self.connectionDidUpdate()
          break
        case "delete":
          self.connectionDidDelete()
          break
        default:
          // do nothing
          break
        } // ./switch

      } // ./action is not nil
      
    } // ./success = true

  }
  
}
