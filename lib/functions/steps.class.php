<?php

/** related functionality */
require_once( dirname(__FILE__) . '/steps.class.php' );

/**
 * class for Test case CRUD
 * @package   TestLink
 */
class steps extends tlObjectWithAttachments
{
  const AUTOMATIC_ID=0;
  const DEFAULT_ORDER=0;
  const ALL_VERSIONS=0;
  const LATEST_VERSION=-1;
  const AUDIT_OFF=0;
  const AUDIT_ON=1;
  const CHECK_DUPLICATE_NAME=1;
  const DONT_CHECK_DUPLICATE_NAME=0;
  const ENABLED=1;
  const ALL_TESTPLANS=null;
  const ANY_BUILD=null;
  const GET_NO_EXEC=1; 
  const ANY_PLATFORM=null;
  const NOXMLHEADER=true;    
  const EXECUTION_TYPE_MANUAL = 1;
  const EXECUTION_TYPE_AUTO = 2;

        
    
  /** @var database handler */
  var $db;
  var $debugMsg;
  var $layout;

  
  /**
   * testplan class constructor
   * 
   * @param resource &$db reference to database handler
   */
  function __construct(&$db)
  {
    $this->db = &$db;

    $this->debugMsg = ' Class:' . __CLASS__ . ' - Method: ';

    parent::__construct($this->db,"steps_template");
  }


  function update_step_template($id,$step)
  {

    $sql = " UPDATE {$this->tables['steps_template']} " .
           " SET step='" . $this->db->prepare_string($step) . "'" .
           " WHERE id = " . $this->db->prepare_int($id); 
    $this->db->exec_query($sql);
  }

  /**
     * 
     *
     */
  function create_step_template($step)
  {
    $debugMsg = 'Class:' . __CLASS__ . ' - Method: ' . __FUNCTION__;
    $ret = array();
      
    $sql = "/* $debugMsg */ INSERT INTO {$this->tables['steps_template']} " .
           " (step) " .
           " VALUES('" . $this->db->prepare_string($step) . "')";
      
    $result = $this->db->exec_query($sql);
    $ret = array('msg' => 'ok', 'id' => $item_id, 'status_ok' => 1, 'sql' => $sql);
    if (!$result)
    {
      $ret['msg'] = $this->db->error_msg();
      $ret['status_ok']=0;
      $ret['id']=-1;
    }
    return $ret;
  }

  function delete_step_by_id($id)
  {
    $debugMsg = 'Class:' . __CLASS__ . ' - Method: ' . __FUNCTION__;
    
    $sql = array();
    $whereClause = " WHERE id = " . $this->db->prepare_int($id);
    
    $sqlSet[] = "/* $debugMsg */ DELETE FROM {$this->tables['steps_template']} {$whereClause} ";
                " {$whereClause}";

    foreach($sqlSet as $sql)
    {
      $this->db->exec_query($sql);
    } 
  }

  function get_step_by_id($step_id)
  {
    $debugMsg = 'Class:' . __CLASS__ . ' - Method: ' . __FUNCTION__;
    $sql = "/* $debugMsg */ " . 
           " SELECT * FROM {$this->tables['steps_template']} " .
           " WHERE id = {$step_id} ";
    $result = $this->db->get_recordset($sql);
    
    return is_null($result) ? $result : $result[0];
  }

  function get_steps()
  {
    $sql = " SELECT id, step " .
           " FROM {$this->tables['steps_template']}  ";
    $result = $this->db->get_recordset($sql);

    return $result;
  }

}  