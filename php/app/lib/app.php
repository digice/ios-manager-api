<?php

/** the API requires three parameters:
  * 't' = a global api token
  * 'm' = the model we are accessing
  * 'a' = the action we want the controller to take
  **/

class AppCtl
{

  protected $action;

  protected $model;

  public function __construct()
  {

    // check for global API token
    if ($t = Req::val('t')) {

      if ($t == '1a79a4d60de6718e8e5b326e338ae533') {

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
 
      } // .token matches
 
      else {
        die('API Access Requires an access token.');
      } // ./token does not match

    } // .token is set
 
    else {
     die('API Access Requires an access token.');
    } // ./token is not set

  } // ./construct

}
