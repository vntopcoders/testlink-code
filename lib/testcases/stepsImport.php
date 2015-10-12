<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later.
 *  
 * Platforms import management
 *
 * @package 	  TestLink
 * @author 		  Francisco Mancardi (francisco.mancardi@gmail.com)
 * @copyright   2005-2014, TestLink community 
 * @filesource  platformsImport.php
 * @link 		    http://www.testlink.org
 * @uses 		    config.inc.php
 *
 * @internal revisions
 * @since 1.9.11
 *
 */
require('../../config.inc.php');
require_once('common.php');
require_once('xml.inc.php');
testlinkInitPage($db,false,false);

$templateCfg = templateConfiguration();

$args = init_args();
$gui = initializeGui();


$resultMap = null;
switch($args->doAction)
{
  case 'doImport':
    $gui->file_check = doImport($db);
  break;  
    
  default:
  break;  
}


$smarty = new TLSmarty();
$smarty->assign('gui',$gui);  
$smarty->display($templateCfg->template_dir . $templateCfg->default_template);


/**
 *
 */
function init_args()
{
	$args = new stdClass();
	$iParams = array("doAction" => array(tlInputParameter::STRING_N,0,50));
		
	R_PARAMS($iParams,$args);
	$args->userID = $_SESSION['userID'];
	return $args;
}

/**
 *
 */
function initializeGui()
{
  $guiObj = new stdClass();
  $guiObj->goback_url = $_SESSION['basehref'] . 'lib/testcases/tcStepsTemplate.php'; 
  $guiObj->page_title = lang_get('Import Steps Definition');
  $guiObj->file_check = array('show_results' => 0, 'status_ok' => 1, 'msg' => 'ok', 'filename' => '');
  $guiObj->importTypes = array('XML' => 'XML');
  $guiObj->importLimitBytes = config_get('import_file_max_size_bytes');
  $guiObj->max_size_import_file_msg = sprintf(lang_get('max_size_file_msg'), $guiObj->importLimitBytes/1024);
  return $guiObj;  
}


/**
 * @param object dbHandler reference to db handler
 *
 */
function doImport(&$dbHandler)
{

  	$import_msg = array('ok' => array(), 'ko' => array());
  	$file_check = array('show_results' => 0, 'status_ok' => 0, 'msg' => '', 
                    	'filename' => '', 'import_msg' => $import_msg);
  
  	$key = 'targetFilename';
	$dest = TL_TEMP_PATH . session_id(). "-import_steps.tmp";
	$fInfo = $_FILES[$key];
	$source = isset($fInfo['tmp_name']) ? $fInfo['tmp_name'] : null;
	if (($source != 'none') && ($source != ''))
	{ 
		$file_check['filename'] = $fInfo['name'];
		$xml = false;
		if (move_uploaded_file($source, $dest))
		{
      // http://websec.io/2012/08/27/Preventing-XXE-in-PHP.html
      $xml = @simplexml_load_file_wrapper($dest);
    }
         
	if($xml !== FALSE)
    {
     	$file_check['status_ok'] = 1;
      	$file_check['show_results'] = 1;

	  	$stepsTemplateObj = new steps($dbHandler);

	  	foreach($xml as $step)
	  	{
		    if(property_exists($step, 'step'))
		    {  
		 		$import_msg['ok'][] = sprintf(lang_get('step_imported'),$step->step);
		        $stepsTemplateObj->create_step_template($step->step);
		    } else {
			      $import_msg['ko'][] = lang_get('bad_line_skipped');
		    }  
	  	}      
    } else {
    	$file_check['msg'] = lang_get('problems_loading_xml_content');  
    }  
          
  	}	else {
		$msg = getFileUploadErrorMessage($fInfo);
		$file_check = array('show_results' => 0, 'status_ok' => 0,'msg' => $msg);
	}
  
  	if( count($import_msg['ko']) == 0 )
  	{
    	$import_msg['ko'] = null;
  	}  
  	$file_check['import_msg'] = $import_msg;
  	
  	return $file_check;
}