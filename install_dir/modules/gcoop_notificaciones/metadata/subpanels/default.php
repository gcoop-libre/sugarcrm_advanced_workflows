<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
$module_name='gcoop_notificaciones';
$subpanel_layout = array(
	'top_buttons' => array(
	),

	'where' => '',

	'list_fields' => array(
		'name'=>array(
	 		'vname' => 'LBL_NAME',
//			'widget_class' => 'SubPanelDetailViewLink',
	 		'width' => '15%',
            'sortable' => false,
		),
		'description'=>array(
	 		'vname' => 'LBL_DESCRIPTION',
	 		'width' => '60%',
            'sortable' => false,
		),
		
        'created_by_name'=>array(
	 		'vname' => 'LBL_CREATED_USER',
	 		'width' => '10%',
            'sortable' => false,
            'default' => true,
		),
		'date_modified'=>array(
	 		'vname' => 'LBL_DATE_MODIFIED',
	 		'width' => '10%',
            'sortable' => true,
		),
        'created_by' =>array(
            'usage' => 'query_only',
            ),
	),
);

?>
