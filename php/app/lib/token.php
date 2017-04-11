<?php

class TokenNameIdx extends Idx
{

  public function __construct()
  {

    $this->mdl = 'token';
    
    $this->col = 'name';

    parent::__construct();
    
  }

}

class TokenMdl extends Mdl
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
    $this->name = 'token';
    
    $this->idx = new TokenNameIdx();

    // parent constructor will calculate paths based on name
    parent::__construct();

  }

}

class TokenCtl extends Ctl
{

  public function __construct()
  {
    $this->mdl = TokenMdl::shared();
  }

  public function create($user_id)
  {
    $assoc = array(
      'name' => md5(strval(microtime(true))),
      'user_id' => $user_id
    );
    $id = $this->mdl->insertAssoc($assoc);
  }

}