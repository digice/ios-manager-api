<?php

/** @package    OTMVC 
  * @descr      A Standalone File-based Database Manager
  * @class      Ctl (Controller)
  * @author     Roderic Linguri <linguri@digices.com>
  * @license    MIT
  * @copyright  2017 Digices LLC. All Rights Reserved.
  **/



abstract class Ctl
{

  /** @property *assoc* Result **/
  protected $res;
  
  /** @property *str* Model Name **/
  protected $mdl;

  /**
    * @method Constructor
    * @descr  extended class must set strings for mdl and col
    */
  public function __construct()
  {
    $this->res = new Res();
  }

  public function json()
  {
    return $this->res->json();
  }

}
