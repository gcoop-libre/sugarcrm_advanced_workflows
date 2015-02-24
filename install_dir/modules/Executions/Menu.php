<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

global $mod_strings, $app_strings, $sugar_config;

if(ACLController::checkAccess('Executions', 'edit', true))$module_menu[]=Array("index.php?module=Executions&action=EditView&return_module=Executions&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateExecutions", 'Executions');
if(ACLController::checkAccess('Executions', 'list', true))$module_menu[]=Array("index.php?module=Executions&action=index&return_module=Executions&return_action=DetailView", $mod_strings['LNK_LIST'],"Executions", 'Executions');
if(ACLController::checkAccess('Executions', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Executions&return_module=Executions&return_action=index", $app_strings['LBL_IMPORT'],"Import", 'Executions');
