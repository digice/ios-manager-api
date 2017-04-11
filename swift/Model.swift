//
//  <#Model#>.swift
//  OTMVC
//
//  Created by Digices LLC on 4/10/17.
//  Copyright © 2017 Digices LLC. All rights reserved.
//

import Foundation

class <#Model#> : NSObject, NSCoding {

  var id : Int = 0
  var created : Int = Int(Date().timeIntervalSince1970)
  var updated : Int = Int(Date().timeIntervalSince1970)
  var name : String = "New <#Model#>"

  override init() {
    super.init()
  }

  init(dict: [String:String]) {

    if let i = dict["id"] {
      self.id = Int(i)!
    } else {
      self.id = 0
    }

    if let c = dict["created"] {
      self.created = Int(c)!
    } else {
      self.created = 0
    }

    if let u = dict["updated"] {
      self.updated = Int(u)!
    } else {
      self.updated = 0
    }

    if let n = dict["name"] {
      self.name = n
    } else {
      self.name = ""
    }

  }

  required init?(coder aDecoder: NSCoder) {
    self.id = aDecoder.decodeInteger(forKey: "id")
    self.created = aDecoder.decodeInteger(forKey: "created")
    self.updated = aDecoder.decodeInteger(forKey: "updated")
    self.name = aDecoder.decodeObject(forKey: "name") as! String
  }

  func encode(with aCoder: NSCoder) {
    aCoder.encode(self.id, forKey: "id")
    aCoder.encode(self.created, forKey: "created")
    aCoder.encode(self.updated, forKey: "updated")
    aCoder.encode(self.name, forKey: "name")
  }

  func dict() -> [String:String] {
    var d : [String:String] = [:]
    d["id"] = "\(self.id)"
    d["created"] = "\(self.created)"
    d["updated"] = "\(self.updated)"
    d["name"] = self.name
    return d
  }

}
