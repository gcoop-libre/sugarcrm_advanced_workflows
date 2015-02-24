<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$module_name='ChoiceNodes';
$subpanel_layout = array(
        'top_buttons' => array(
            array('widget_class' => 'SubPanelTopButtonQuickCreate'),
            ),

        'where' => '',

        'list_fields' => array(
            'name'=>array(
                'vname' => 'LBL_NAME',
                'widget_class' => 'SubPanelDetailViewLink',
                'width' => '35%',
                ),
            'siguiente_caso_false'=>array(
                'vname' => 'LBL_SIGUIENTE_CASO_FALSE',
                'width' => '25%',
                ),
            'siguiente_caso_true'=>array(
                'vname' => 'LBL_SIGUIENTE_CASO_TRUE',
                'width' => '25%',
                ),
            'accion'=>array(
                'vname' => 'LBL_ACCION',
                'width' => '25%',
                ),
            'parametros'=>array(
                'vname' => 'LBL_PARAMETROS',
                'width' => '25%',
                ),
            'edit_button'=>array(
                'widget_class' => 'SubPanelEditButton',
                'module' => $module_name,
                'width' => '4%',
                ),
            'remove_button'=>array(
                'widget_class' => 'SubPanelRemoveButton',
                'module' => $module_name,
                'width' => '4%',
                ),
            'workflow_id' => array(
                'usage' => 'query_only',
                ),
),
        );

?>
