<?php
/*--------------------------------------------------------------------------------------------
 * dbUpdates.php
 *
 * Copyright 2015 2016 2017 2018 by John Gambini
 *
 ---------------------------------------------------------------------------------------------*/
session_start();

$old_error_handler = set_error_handler("myErrorHandler");

$errorMessage = '';
$successMessage = '';
$debugMessage = '';

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
	$errorMessage = 'dbUpdates.php: ' . $errstr . ' in ' . $errfile . ' line number: ' . $errline;
	$successMessage = '';
	$debugMessage = 'No debug output.';
	exit(json_encode(array($errorMessage,$successMessage,$debugMessage)));
}

define('ABSDIR', dirname(__FILE__));

require_once( ABSDIR . '\config\app_defs.php');
require_once( WORKBENCH_DIR . '\php\objects\wbDatabase.php');
require_once( WORKBENCH_DIR . '\php\objects\mysqliDatabase.php');
require_once( WORKBENCH_DIR . '\php\objects\pdoDatabase.php');
require_once( WORKBENCH_DIR . '\php\objects\dbObject.php');
require_once( WORKBENCH_DIR . '\php\objects\dbUser.php');
require_once( WORKBENCH_DIR . '\php\objects\wbSql.php');
require_once( WORKBENCH_DIR . '\php\objects\dbContent.php');
require_once( WORKBENCH_DIR . '\php\objects\wbDataArrays.php');
require_once( WORKBENCH_DIR . '\php\includes\wb_functions.php');
require_once( WORKBENCH_DIR . '\php\includes\galleryWidgetString.php');

$userObj = new dbUser();
$dbObj = new mysqliDatabase();

if( ! $dbObj->connect(DB_HOST,DB_USER,DB_PASSWORD,DATABASE,DB_CHARSET)) {
	$errorMessage = $dbObj->error;
	die(json_encode(array($errorMessage,$successMessage,$debugMessage)));
}

if ( $userObj->get_user($dbObj,$_SESSION['userID']) == false ) {
	$errorMessage = $errorMessage . $dbObj->error;
	die(json_encode(array($errorMessage,$successMessage,$debugMessage)));
}

require_once( WORKBENCH_DIR . '\php\wb_database_updates.php');

if(database_updates($dbObj, $userObj) == false) {
	$errorMessage = $errorMessage . $dbObj->error;
	$articleData = array(
			replace_wb_variable($_POST['editor']),
			'Last Modified: ' . date("Y-m-d h:i:sa"),
			//unproccessed text for the client editor
			$_POST['editor']
	);
	die(json_encode(array($errorMessage,$successMessage,$debugMessage,$articleData)));
} else {
	$successMessage = "The database has been successfully updated.";
	$contentObj = new dbContent();
	$contentObj->ID = $_POST['Id'];
	if($contentObj->get_content_by_id($dbObj,$userObj) == false) {
		$errorMessage = "dbUpdates: " . $contentObj->db_error;
	}
	
	$sqlObject = new wbSql($userObj,$contentObj);
	$dataArrays = new wbDataArrays();
	
	//$error = 'Always throw this error';
	//throw new Exception($error);
	
	//$dataArrays->get_galleryItemsArray($dbObj, $sqlObject);
	
	$articleData = array(
			replace_wb_variable("" . $_POST['editor'], $dbObj, $sqlObject, $contentObj, $dataArrays),
			'Last Modified: ' . date("Y-m-d h:i:sa"),
			//unproccessed text for the client editor
			"" . $_POST['editor']
	);
	exit(json_encode(array($errorMessage,$successMessage,$debugMessage,$articleData)));
}

//$dbObj->close();

?>