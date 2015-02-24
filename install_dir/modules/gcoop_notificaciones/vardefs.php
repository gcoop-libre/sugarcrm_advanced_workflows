<?php
$dictionary = array (
    'gcoop_notificaciones' => 
    array (
        'table' => 'gcoop_notificaciones',
        'audited' => true,
        'duplicate_merge' => true,
        'unified_search' => true,
        'unified_search_default_enabled' => true,
        'fields' => 
        array (
            'name' => 
            array (
                'name' => 'name',
                'rname' => 'name',
                'vname' => 'LBL_SUBJECT',
                'dbType' => 'varchar',
                'type' => 'name',
                'len' => '255',
                'importable' => 'required',
                'required' => 'true',
                'unified_search' => 'true',
                ),
            'parent_id'=>
            array(
                'name'=>'parent_id',
                'vname'=>'LBL_LIST_RELATED_TO_ID',
                'type'=>'id',
                'group'=>'parent_name',
                'reportable'=>false,
                'comment' => 'The ID of the parent Sugar object identified by parent_type'
                ),
            'parent_type'=>
            array(
                    'name'=>'parent_type',
                    'vname'=>'LBL_PARENT_TYPE',
                    'type' => 'parent_type',
                    'dbType'=>'varchar',
                    'required'=> true,
                    'group'=>'parent_name',
                    'options'=> 'parent_type_display',
                    'len'=>255,
                    'comment' => 'The Sugar object to which the call is related'
                 ),

            'parent_name'=>
            array(
                    'name'=> 'parent_name',
                    'parent_type'=>'record_type_display' ,
                    'type_name'=>'parent_type',
                    'id_name'=>'parent_id',
                    'vname'=>'LBL_LIST_RELATED_TO',
                    'type'=>'parent',
                    'group'=>'parent_name',
                    'source'=>'non-db',
                    'options'=> 'parent_type_display',
                    'required' => true,
                 ),
            ),
        'relationships' => 
        array (
            ),
        'indices' => 
        array(
            array (
    	        'name' => 'idx_gcoop_notificaciones_par_del',
    	        'type' => 'index',
    	        'fields' => array('parent_id','parent_type','deleted')
            ),
        ),
        'optimistic_lock' => true,
    ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('gcoop_notificaciones','gcoop_notificaciones', array('basic',));
?>
