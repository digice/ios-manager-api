//
//  <#Model#>Table.swift
//  iOS Manager API
//
//  Created by Roderic Linguri on 4/10/17.
//  Copyright © 2017 Digices LLC. All rights reserved.
//

import UIKit

class <#Model#>View : UIViewController, <#Model#>ManagerDelegate {

  // MARK: - Outlets

  // set this to the size of actual content
  @IBOutlet weak var contentView: UIView!

  @IBOutlet weak var idField: UITextField!

  @IBOutlet weak var createdField: UITextField!

  @IBOutlet weak var updatedField: UITextField!

  @IBOutlet weak var nameField: UITextField!

  @IBOutlet weak var saveButton: UIButton!

  @IBOutlet weak var cancelButton: UIButton!

  @IBOutlet weak var messageLabel: UILabel!

  // MARK: - Properties

  let manager : <#Model#>Manager = <#Model#>Manager.shared

  // MARK: UIViewController Overrides

  override func viewDidLoad() {

    super.viewDidLoad()

    self.manager.viewDelegate = self

    // does data get loaded?
    if self.manager.load<#Model#>AtCursor == true {
      self.updateViewFromData()
    } else {
      self.saveButton.setTitle("Create Record", for: .normal)
    }

  }

  override func didReceiveMemoryWarning() {
    super.didReceiveMemoryWarning()
  }

  // MARK: - <#Model#>ManagerDelegate Methods

  func <#model#>ManagerDidUpdate() {
    self.updateViewFromData()
  }

  // MARK: - <#Model#>View (self) Methods

  func updateViewFromData() {

    // BUG: Not showing correct record after UPDATE

    let object = self.manager.objects[self.manager.cursor]
    self.idField.text = "\(object.id)"
    self.createdField.text = "\(object.created)"
    self.updatedField.text = "\(object.updated)"
    self.nameField.text = object.name
    self.saveButton.setTitle("Update Record", for: .normal)
    self.messageLabel.text = self.manager.connection.message
  }

  func create() {
    // create new <#model#> object with standard init
    if let n = self.nameField.text {
      if n.characters.count > 0 {
        let <#model#> = <#Model#>()
        <#model#>.name = n
        self.manager.create(new<#Model#>: <#model#>)
      }
    }
  }

  func update() {
    if let n = self.nameField.text {
      if n.characters.count > 0 {
        self.manager.objects[self.manager.cursor].name = n
        self.manager.update()
      }
    }
  }

  // MARK: - Actions
  @IBAction func save(_ sender: Any) {

    if let i = self.idField.text {
      if i.characters.count > 0 {
        self.update()
      } else {
        self.create()
      }
    }

  }

  @IBAction func cancel(_ sender: Any) {
    self.updateViewFromData()
    self.navigationController?.popViewController(animated: true)
  }
}
