<?php

class ContactNameIdx extends Idx
{

  public function __construct()
  {

    $this->mdl = 'contact';
    
    $this->col = 'name';

    parent::__construct();
    
  }

}

class ContactMdl extends Mdl
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
    $this->name = 'contact';
    
    $this->idx = new ContactNameIdx();

    // parent constructor will calculate paths based on name
    parent::__construct();

  }

}

class ContactCtl extends Ctl
{

  public function __construct()
  {
    // parent constructor creates a default response
    parent::__construct();

    // requires a token to access
    if ($t = Req::val('t')) {
    
      $tkn = TokenMdl::shared();
      
      if ($assoc = $tkn->fetchAssocByName($t)) {
      
      } else {
        
      } // ./token is not valid
    
    } // ./token is set
    
    else {
    
    
    } // ./token not set

    $this->res->model = 'contact';
    
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
  
  public function create()
  {

    $this->res->action = 'create';

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
    } // ./name not set

  }

  public function read()
  {
  
  }

  public function update()
  {
    $this->res->action = 'update';
    
    if ($i = Req::val('id')) {
    
			if ($rec = $this->mdl->fetchAssocById($i)) {
		
				if ($n = Req::val('name')) {
			
					// don't update unless the name has changed
					if ($n != $rec['name']) {
					
						$rec['name'] = $n;
					
						$this->mdl->updateAssoc($rec);
					
						$this->res->success = 'true';
						$this->res->message = 'Record Updated';
						$this->res->appendResult($rec);
					
					} // ./name is not equal to stored name
				
					else {
						$this->res->message = 'No Update Required';
					} // ./name is equal to stored name
			
				} // ./name is set

        else {
          $this->res->message = 'Name is required';
        } // ./name is not set
        
			} // .record found
		
			else {
		
				$this->res->message = 'ID not found?';
		
			} // ./record not found
    
    } // ./id is set
    
    else {
      $this->res->message = 'Update requires an ID';
    } // ./id is not set

  }

  public function delete()
  {
    $this->res->action = 'delete';
    
    if ($i = Req::val('id')) {
    
      $this->mdl->deleteAssocById($i);
      $this->res->success = 'true';
      $this->res->message = 'Record Deleted';
			$this->res->appendResult(array('id' => $i));
    
    } // ./id is set
    
    else {
    
    } // ./id is not set

  } // ./delete

  public function dearth()
  {
    $this->res->action = 'default';
    $this->res->message = 'Invalid action parameter';
  }

}