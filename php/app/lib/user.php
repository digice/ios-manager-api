<?php

class UserNameIdx extends Idx
{

  public function __construct()
  {

    $this->mdl = 'user';
    
    $this->col = 'name';

    parent::__construct();
    
  }

}

class UserMdl extends Mdl
{

  protected static $shared;

  // return the singleton. Access with ContactMdl::shared();
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
    $this->name = 'user';
    
    $this->idx = new UserNameIdx();

    // parent constructor will calculate paths based on name
    parent::__construct();

  }

}

class UserCtl extends Ctl
{

  public function __construct()
  {
    // parent constructor creates a default response
    parent::__construct();

    $this->res->model = 'user';
    
    $this->mdl = ContactMdl::shared();
    
    if ($action = Req::val('a')) {
      switch ($action) {
        case 'create':
          $this->create();
          break;
        case 'read':
          $this->read();
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

}