<?php

class ExampleMdl extends Mdl
{

  protected static $shared;

  // return the singleton. Access with ExampleMdl::shared();
  public static function shared()
  {
    if (!isset(self::$shared)) {
      self::$shared = new self();
    }
    return self::$shared;
  }

  public function __construct()
  {

    // set the name of this model
    $this->name = 'example';

    // parent constructor will calculate paths based on name
    parent::__construct();

  }

}

class ExampleCtl extends Ctl
{

  public function __construct()
  {
    parent::__construct();

    $this->res->model = 'example';
    
    $this->mdl = ExampleMdl::shared();
    
    if ($action = Req::val('a')) {
      switch ($action) {
        case 'push':
          $this->push();
          break;
        case 'pull':
          $this->pull();
          break;
        case 'update':
          $this->update();
          break;
        case 'delete':
          $this->delete();
          break;
        default:
          $this->dearth();
          break;
      }
    }
    
    else {
      $this->res->action = 'default';
      $this->res->message = 'Invalid action parameter';
    }
  }
  
  public function push()
  {

    $this->res->action = 'push';
    
    // if an id is present we need to UPDATE
    if ($i = Req::val('i')) {
      $this->update(intval($i));
    } // ./an id is set
    
    else {
      $this->create();
    } // ./no id set
    

  }

  public function pull()
  {
    $this->res->action = 'pull';
  }

  public function create()
  {

    $this->res->action = 'update';

    if ($n = Req::val('name')) {
      
      if ($c = Req::val('created')) {
        $created = intval($c);
      } else {
        $created = intval(date('U'));
      }
      
      $assoc = array(
        'name' => $n,
        'created' => $created
      );
      
      // attempt to insert
      if ($id = $this->mdl->insertAssoc($assoc)) {
        $record = $this->mdl->fetchAssocById($id);
        $this->res->appendResult($record);
        $this->res->success = 'true';
        $this->res->message = 'Record Added';
      }
      
    } // ./name is present
    
    else {
      $this->res->message = 'Name Parameter missing';
    }
  }

  public function update($id)
  {
    $this->res->action = 'update';
    
    if ($rec = $this->mdl->fetchAssocById($id)) {
    
      if ($n = Req::val('name')) {
      
        if ($n != $rec['name']) {
          
          $rec['name'] = $n;
          
          $this->mdl->updateAssoc($rec);
          
          $this->res->success = 'true';
          $this->res->message = 'Record Updated';
          
        }
      
      }
    
    } // .record found
    
    else {
    
      $this->res->message = 'ID not found?';
    
    } // ./record not found

  }

  public function delete()
  {
    $this->res->action = 'delete';
  }

  public function dearth()
  {
    $this->res->action = 'default';
    $this->res->message = 'Invalid action parameter';
  }

}