//
//  Connection.swift
//  iOS Manager API
//
//  Created by Digices LLC on 4/9/17.
//  Copyright © 2017 Digices LLC. All rights reserved.
//

import UIKit

protocol ConnectionDelegate {
  func connectionDidReceiveResponse()
  func connectionDidReceiveError()
}

class Connection {

  // MARK: - Properties

  static let shared : Connection = Connection()

  var delegate : ConnectionDelegate?

  var request : URLRequest = URLRequest(url: URL(string: "https://<#api-url#>/index.php")!)

  // access to API is restricted by a global token

  var token : String = "1a79a4d60de6718e8e5b326e338ae533"

  // json is always returned as a dictionary of two elements, i.e.:
  // {"response":{"success":"false","model":"contact","action":"create","count":"0","message":"No Data"},
  // {"results":[]}

  // response is parsed into separate properties of connection

  var success : Bool = false

  var model : String?

  var action : String?

  var count : Int = 0

  var message : String = ""

  // results are smply parsed into an array of dictionaries

  var results : [[String:String]] = []

  // MARK: - Methods

  func setRequest(model: String, action: String, params: [String : String]) {

    self.action = action

    self.request.httpMethod = "POST"

    var bodyString : String = "?t=\(self.token)&m=\(model)&a=\(action)"

    for (key,value) in params {
      bodyString.append("&\(key)=\(value)")
    }

    print("Request Body: ")
    print("https://www.digices.com/otmvc-example/index.php\(bodyString)")

    self.request.httpBody = bodyString.data(using: .utf8)

  }

  func completionHandler(data: Data?, response: URLResponse?, error: Error?) {

    if let e = error {

      print("Error: \(e)")

      if let d = self.delegate {
        d.connectionDidReceiveError()
      }

    } // ./an error was reported

    else {

      if let d = data {

        // DEBUG: Print Response To Console
        if let str = String(data: d, encoding: .utf8) {
          print("Received Data:")
          print(str)
        }

        // attempt to parse json aobject
        if let dict = try? JSONSerialization.jsonObject(with: d, options: .allowFragments) as! [String : Any] {

          // split into response and data

          // extract response
          if let r = dict["response"] as? [String:String] {

            // extract success as Bool
            if let s = r["success"] {
              if s == "true" {
                self.success = true
              } else {
                self.success = false
              }
            }

            // extract action
            if let m = r["model"] {
              self.model = m
            }

            // extract action
            if let a = r["action"] {
              self.action = a
            }

            if let c = r["count"] {
              if let cInt = Int(c) {
                self.count = cInt
              }
            }

            // extract message
            if let g = r["message"] {
              self.message = g
            }

          } // response was not nil

          // extract results
          if let o = dict["results"] as? [[String:String]] {
            self.results = o
          }

        } // ./dictionary was extracted from data

      } // ./data is not nil

    } // .no error

    OperationQueue.main.addOperation {
      UIApplication.shared.isNetworkActivityIndicatorVisible = false
      if let d = self.delegate {
        d.connectionDidReceiveResponse()
      }
    }

  }

  func sendRequest() {
    UIApplication.shared.isNetworkActivityIndicatorVisible = true
    let session = URLSession.shared
    let task = session.dataTask(with: self.request, completionHandler: self.completionHandler)
    task.resume()
  }

}
