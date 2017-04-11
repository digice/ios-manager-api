<?php

class Res
{

  public $success;
  
  public $model;
  
  public $action;
  
  public $message;
  
  public $results;

  public function __construct()
  {
    // initialize a default reply
    $this->results = array();
    $this->success = 'false';
  }

  public function json()
  {
    $res = array(
      'response' => array(
        'success' => $this->success,
        'model' => $this->model,
        'action' => $this->action,
        'count' => strval(count($this->results)),
        'message' => $this->message
      ),
      'results' => $this->results
    );
    return json_encode($res);
  }

  public function appendResult($object)
  {
    $arr = array();
    foreach ($object as $k=>$v) {
      $arr[strval($k)] = strval($v);
    }
    array_push($this->results, $arr);
  }

}