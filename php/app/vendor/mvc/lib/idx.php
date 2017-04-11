<?php

/** @package    OTMVC 
  * @descr      A Standalone File-based Database Manager
  * @class      Idx (Index)
  * @author     Roderic Linguri <linguri@digices.com>
  * @license    MIT
  * @copyright  2017 Digices LLC. All Rights Reserved.
  **/


abstract class Idx
{

  /** @property *str* Model Name **/
  protected $mdl;
  
  /** @property *str* Key Column Name **/
  public $col;
  
  /** @property *str* Path where index file is stored **/
  protected $pth;

  /** @property *assoc* Index of Ids keyed by Column Value **/
  protected $dct;
  
  /**
    * @method Constructor
    * @descr  extended class must set strings for mdl and col
    */
  public function __construct()
  {
    $this->dct = array();
    $this->pth = dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.$this->mdl.DIRECTORY_SEPARATOR.'idx'.DIRECTORY_SEPARATOR.$this->col;
    if ($csv = file_get_contents($this->pth)) {
      $lines = explode(PHP_EOL, $csv);
      foreach ($lines as $line) {
        if (strlen($line) > 0) {
          $vals = explode(',', $line);
          // reverse order for index
          // i.e. dct['Column Value'] = *int* id
          $this->dct[$vals[1]] = intval($vals[0]);
        }
      }
    }
  }

  /**
    * @method Save
    */
  public function save()
  {
    $csv = '';
    foreach ($this->dct as $k=>$v) {
      $csv .= $v.','.$k.PHP_EOL;
    }
    file_put_contents($this->pth, $csv);
  }

  /**
    * @method Fetch Id For Column
    * @param  *str* Column Value
    */
  public function fetchIdForCol($value) {
    $hash = md5($value);
    if (isset($this->dct[$hash])) {
      return intval($this->dct[$hash]);
    } else {
      return false;
    }
  }

  /**
    * @method Insert
    * @param  *int* Id
    * @param  *str* Index Key
    */
  public function insert($id,$key)
  {
    $this->dct[md5($key)] = $id;
    $this->save();
  }

  /**
    * @method Update By Value
    * @param  *str* Old Value
    * @param  *str* New Value
    */
  public function updateByValue($old_value,$new_value)
  {
    $old_hash = md5($old_value);
    $new_hash = md5($new_value);

    if (isset($this->dct[$old_hash])) {
      $id = $this->dct[$old_hash];
      unset($this->dct[$old_hash]);
      $this->dct[$new_hash] = $id;
    }
    asort($this->dct, SORT_NUMERIC);
    $this->save();
  }
  
  /**
    * @method Update By Id
    * @param  *int* Id
    * @param  *str* New Value
    */
  public function updateById($id,$new_value)
  {
    foreach ($this->dct as $k=>$v) {
      if ($v == $id) {
        $this->dct[md5($new_value)] = $v;
        unset($this->dct[$k]);
      }
    }
    asort($this->dct, SORT_NUMERIC);
    $this->save();
  }

  /**
    * @method Delete By Id
    * @param  *int* Id
    */
  public function deleteById($id) {
    foreach ($this->dct as $k=>$v) {
      if ($v == $id) {
        unset($this->dct[$k]);
      }
    }
    $this->save();
  }

}
