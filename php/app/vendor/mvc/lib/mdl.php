<?php

/** @package    OTMVC 
  * @descr      A Standalone File-based Database Manager
  * @class      Mdl (Model)
  * @author     Roderic Linguri <linguri@digices.com>
  * @license    MIT
  * @copyright  2017 Digices LLC. All Rights Reserved.
  **/


abstract class Mdl
{

  /** @property *str* Model Name **/
  protected $name;

  /** @property *str* Path to Directory **/
  protected $dir;
  
  /** @property *obj* Index **/
  protected $idx;

  /**
    * @method Constructor
    * @descr  extended class must set string for name
    */
  public function __construct()
  {
    $this->dir = dirname(dirname(dirname(__DIR__))).DIRECTORY_SEPARATOR.'var'.DIRECTORY_SEPARATOR.$this->name.DIRECTORY_SEPARATOR;
    $this->id_path = $this->dir.'int'.DIRECTORY_SEPARATOR.'id';
    $this->row_path = $this->dir.'row'.DIRECTORY_SEPARATOR;
  }

  /**
   * @return *int* next unused id
   */
  public function nextId()
  {
    $id = intval(file_get_contents($this->id_path)) + 1;
    file_put_contents($this->id_path, strval($id));
    return $id;
  }

  /**
   * @param  *int* id
   * @return *assoc* record for id
   *         false if record is not found
   */
  public function fetchAssocById($id)
  {

    $path = $this->dir.'row'.DIRECTORY_SEPARATOR.$id;

    if (file_exists($path)) {
      return unserialize(file_get_contents($path));
    } else {
      return false;
    }
  }

  /**
   * @param  *str* name value
   * @return *assoc* record for name
   *         false if record is not found
   */
  public function fetchAssocByName($name)
  {
    $id = $this->idx->fetchIdForCol($name);
    return $this->fetchAssocById($id);
  }

  /**
   * @param  *assoc* to be inserted
   * @return *id* for record
   *         false if record was not created
   */
  public function insertAssoc($assoc)
  {

    if ($id = $this->idx->fetchIdForCol($assoc['name'])) {
      $assoc['name'] = $assoc['name'].'-1';
    }

    $assoc['id'] = $this->nextId();
    if (!isset($assoc['created'])) {
      $assoc['created'] = intval(date('U'));
    }
    if (!isset($assoc['updated'])) {
      $assoc['updated'] = intval(date('U'));
    }

    $path = $this->dir.'row'.DIRECTORY_SEPARATOR.strval($assoc['id']);
    file_put_contents( $path, serialize($assoc) );
    $this->idx->insert($assoc['id'],$assoc[$this->idx->col]);
    return $assoc['id'];
  }

  /**
   * @param  *str* assoc_key
   * @param  *str* value to match
   * @return *array* of assocs
   * (array is empty if no records are found)
   */
  public function fetchAssocsByKeyAndValue($key,$value)
  {
    $assocs = array();
    $di = new DirectoryIterator($this->dir.'row');
    foreach ($di as $item) {
      $id = $item->getFilename();
      if (substr($fn, 0, 1) != '.') {
        $assoc = $this->fetchAssocById($id);
        if (isset($assoc[$key])) {
          if ($assoc[$key] == $value) {
            array_push($assocs, $assoc);
          } // ./value_matches
        } // ./value exists
      } // ./file does not begin with dot
    } // ./foreach item in row dir
    return $assocs;
  }

  /**
   * @param  *assoc*
   * @return *void*
   */
  public function updateAssoc($assoc) {

    if ($id = $this->idx->fetchIdForCol($assoc['name'])) {
      $assoc['name'] = $assoc['name'].'-1';
    } // ./duplicate name

    $path = $this->dir.'row'.DIRECTORY_SEPARATOR.strval($assoc['id']);
    $assoc['update_date'] = date('U');
    file_put_contents( $path, serialize($assoc) );

  }

  public function deleteAssocById($id) {
    $path = $this->dir.'row'.DIRECTORY_SEPARATOR.strval($id);
    if (file_exists($path)) {
      unlink($path);
    } // ./file exists
    $this->idx->deleteById($id);
  }

  public function deleteAssoc($name) {

    $assoc = $this->fetchAssocByName($name);
    $path = $this->dir.'row'.DIRECTORY_SEPARATOR.strval($assoc['id']);
    file_put_contents($path, '');

    // @TODO: implement updating of index

  }

}
