<?php

class DefaultCtl extends Ctl
{

  public function __construct()
  {
    parent::__construct();
    $this->res->model = 'default';
    $this->res->action = 'default';
    $this->res->message = 'Invalid model and-or action';
  }

}