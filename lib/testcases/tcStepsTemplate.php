<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/
 * This script is distributed under the GNU General Public License 2 or later.
 *
 * Test Case and Test Steps operations
 *
 * @filesource  tcEdit.php
 * @package     TestLink
 * @author      TestLink community
 * @copyright   2007-2014, TestLink community 
 * @link        http://www.testlink.org/
 *
 *
 * @internal revisions
 * @since 1.9.13
 *
 **/
require_once("../../config.inc.php");
require_once('common.php');

testlinkInitPage($db);

$smarty = new TLSmarty();
//$smarty->assign('gui',$xbm);
$smarty->tlTemplateCfg = $templateCfg = templateConfiguration();


$steps_template = new steps($db);

$stepInfo = [];

if (isset($_POST['btn_add'])) {
	if (isset($_POST['doAction']) && $_POST['doAction']=='doEditStep') {
		$steps_template->update_step_template($_POST['id'], $_POST['step']);
	} else { 
		$steps_template->create_step_template($_POST['step']);
	}
		
	redirect("tcStepsTemplate.php");
}
if (isset($_GET['doAction']) && $_GET['doAction'] == 'doDeleteStep') {
	$steps_template->delete_step_by_id($_GET['id']);
} else if (isset($_GET['doAction']) && $_GET['doAction'] == 'doEditStep') {
	$stepInfo = $steps_template->get_step_by_id($_GET['id']);
}

$steps = $steps_template->get_steps(); 

$smarty->assign('steps',$steps);
$smarty->assign('stepInfo',$stepInfo);
$smarty->display($templateCfg->template_dir . 'tcSteps.tpl');