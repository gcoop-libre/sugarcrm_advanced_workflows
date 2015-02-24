<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$module_name='Executions';
$subpanel_layout = array(
        'top_buttons' => array(
            ),

        'where' => '',

        'list_fields' => array(
            'name'=>array(
                'vname' => 'LBL_NAME',
                'widget_class' => 'SubPanelDetailViewLink',
                'width' => '35%',
                ),
            'node_id'=>array(
                'vname' => 'LBL_NODE_ID',
                'width' => '25%',
                'sortable' => false,
                ),
            'terminado'=>array(
                'vname' => 'LBL_TERMINADO',
                'widget_class' => 'Fieldbool',
                'width' => '25%',
                'table_key' => '',
                ),
            'error'=>array(
                'vname' => 'LBL_ERROR',
                'width' => '25%',
                'sortable' => false,
                ),
            'edit_button'=>array(
                'widget_class' => 'SubPanelEditButton',
                'module' => $module_name,
                'width' => '4%',
                ),
            'workflow_id' => array(
                'usage' => 'query_only',
                ),
            ),
        );
?>
