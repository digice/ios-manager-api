<?php

/** the API requires two parameters:
  * 'm' = the model we are accessing
  * 'a' = the action we want the controller to take
  **/

class AppCtl
{

  protected $action;

  protected $model;

  public function __construct()
  {

    // check for API token

    // add your API models to the list of permitted models
    $permitted_models = array(
      'example' => 'ExampleCtl',
      'contact' => 'ContactCtl',
      'default' => 'DefaultCtl'
    );

    $this->model = 'default';

    if ($m = Req::val('m')) {

      // we have a model, make sure it is permitted
      if (isset($permitted_models[$m])) {

        // yes, the model is permitted
        $this->model = $m;

      }

    }

    $class = $permitted_models[$this->model];

    $ctl = new $class();

    header('Content-Type: application/json');

    echo $ctl->json();

  } // ./construct

}
