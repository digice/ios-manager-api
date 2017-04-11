//
//  <#Model#>Table.swift
//  iOS Manager API
//
//  Created by Roderic Linguri on 4/10/17.
//  Copyright © 2017 Digices LLC. All rights reserved.
//

import UIKit

class <#Model#>Table : UIViewController, UITableViewDelegate, UITableViewDataSource, <#Model#>ManagerDelegate {

  // MARK: - Outlets

  @IBOutlet weak var tableView: UITableView!

  // MARK: - Properties

  let manager : <#Model#>Manager = <#Model#>Manager.shared

  // MARK: UIViewController Overrides

  override func viewDidLoad() {
    super.viewDidLoad()
    self.title = "<#Model#>s"
    self.navigationItem.rightBarButtonItem = UIBarButtonItem(barButtonSystemItem: .add, target: self, action: #selector(<#Model#>Table.new<#Model#>Entry))
    self.navigationItem.leftBarButtonItem = self.editButtonItem
    self.manager.tableDelegate = self
  }

  override func didReceiveMemoryWarning() {
    super.didReceiveMemoryWarning()

  }

  override func setEditing(_ editing: Bool, animated: Bool) {
    super.setEditing(editing, animated: animated)
    self.tableView.setEditing(editing, animated: animated)
  }

  // MARK: UITableViewDelegate Methods

  func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
    return self.manager.objects.count
  }

  func numberOfSections(in tableView: UITableView) -> Int {
    return 1
  }

  func tableView(_ tableView: UITableView, commit editingStyle: UITableViewCellEditingStyle, forRowAt indexPath: IndexPath) {
    self.manager.objects.remove(at: indexPath.row)
    self.tableView.reloadData()
  }

  // MARK: UITableViewDataSource Methods

  func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
    // self.manager.sort()
    let cell = tableView.dequeueReusableCell(withIdentifier: "<#Model#>Cell", for: indexPath)
    cell.textLabel?.text = self.manager.objects[indexPath.row].name
    return cell
  }

  func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
    self.manager.cursor = indexPath.row
    self.manager.load<#Model#>AtCursor = true
    performSegue(withIdentifier: "Show<#Model#>", sender: self)
  }

  // MARK: <#Model#>ManagerDelegate Methods
  func <#model#>ManagerDidUpdate() {
    self.tableView.reloadData()
  }

  // MARK: <#Model#>Table (self) Methods

  func new<#Model#>Entry() {
    self.manager.load<#Model#>AtCursor = false
    performSegue(withIdentifier: "Show<#Model#>", sender: self)
  }

  // MARK: Actions

}
