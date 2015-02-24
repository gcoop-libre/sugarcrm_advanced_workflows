<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$module_name = 'gcoop_notificaciones';
$listViewDefs[$module_name] = array(
	'NAME' => array
        (
            'label' => 'LBL_NAME',
            'default' => true,
        ),
	'IDT24' => array
        (
            'label' => 'LBL_IDT24',
            'default' => true,
            'link' => true,
        ),
	'DESCRIPTION' => array
        (
            'label' => 'LBL_DESCRIPTION',
            'default' => true,
        ),
	
);
?>
