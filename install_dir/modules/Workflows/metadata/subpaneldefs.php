<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$layout_defs['Workflows'] = array(
        'subpanel_setup' => array(
            'choicenodes' => array(
                'order' => 100,
                'sort_order' => 'desc',
                'sort_by' => 'id',
                'title_key' => 'LBL_CHOICENODES',
                'subpanel_name' => 'default',
                'module' => 'ChoiceNodes',
                'get_subpanel_data' => 'workflow_choicenodes', // nombre de la relacion
                ),
            'actionnodes' => array(
                'order' => 200,
                'sort_order' => 'desc',
                'sort_by' => 'id',
                'title_key' => 'LBL_ACTIONNODES',
                'subpanel_name' => 'default',
                'module' => 'ActionNodes',
                'get_subpanel_data' => 'workflow_actionnodes', // nombre de la relacion
                ),
            'executions' => array(
                'order' => 300,
                'sort_order' => 'desc',
                'sort_by' => 'id',
                'title_key' => 'LBL_EXECUTIONS',
                'subpanel_name' => 'default',
                'module' => 'Executions',
                'get_subpanel_data' => 'workflow_executions', // nombre de la relacion
                ),
            ),
);
?>
