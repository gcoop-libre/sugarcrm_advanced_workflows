<?php
$dictionary = array (
        'Execution' => 
        array (
            'table' => 'executions',
            'audited' => false,
            'duplicate_merge' => true,
            'unified_search' => true,
            'unified_search_default_enabled' => true,
            'fields' => 
            array (
                'parent_type' => 
                array (
                    'name' => 'parent_type',
                    'type' => 'varchar',
                    'len' => 36,
                    'vname' => 'LBL_PARENT_TYPE',
                    ),
                'parent_id' => 
                array (
                    'name' => 'parent_id',
                    'type' => 'varchar',
                    'len' => 36,
                    'vname' => 'LBL_PARENT_ID',
                    ),
                'parent_name' =>
                array(
                    'name' => 'parent_name',
                    'parent_type'=>'record_type_display' ,
                    'type_name'=>'parent_type',
                    'id_name'=>'parent_id',
                    'vname'=>'LBL_LIST_RELATED_TO',
                    'type'=>'parent',
                    'massupdate' => 0,
                    'group'=>'parent_name',
                    'source'=>'non-db',
                    'required' => true,
                    ),
                'node_id' => 
                array (
                        'name' => 'node_id',
                        'type' => 'enum',
                        'len' => 36,
                        'vname' => 'LBL_NODE_ID',
                        'function' => array('name' => 'vinculacion_nodos_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
                      ),
                'terminado' => 
                array (
                        'name' => 'terminado',
                        'type' => 'bool',
                        'dbType' => 'varchar',
                        'len' => 100,
                        'vname' => 'LBL_TERMINADO',
                      ),
                'error' => 
                array (
                        'name' => 'error',
                        'type' => 'text',
                        'vname' => 'LBL_ERROR',
                      ),
                // INICIO Relacion
                // (1) Workflow -> (M) Executions
                'workflow_link' =>
                array (
                        'name' => 'workflow_link',
                        'type' => 'link',
                        'relationship' => 'workflow_executions',
                        'source'=>'non-db',
                        'side' => 'right',
                        'vname'=>'LBL_WORKFLOW',
                      ),
                'workflow_id'=>
                array(
                        'name'=>'workflow_id',
                        'type' => 'id',
                        'len' => 36,
                     ),
                'workflow_name' =>
                array (
                        'name' => 'workflow_name',
                        'rname' => 'name',
                        'id_name' => 'workflow_id',
                        'vname' => 'LBL_WORKFLOW_NAME',
                        'type' => 'relate',
                        'link'=>'workflows',
                        'table' => 'workflows',
                        'join_name'=>'workflows',
                        'isnull' => 'true',
                        'module' => 'Workflows',
                        'dbType' => 'varchar',
                        'len' => 100,
                        'source'=>'non-db',
                        'required' => false,
                      ),
                // FIN Campo Relacion
                ),
                'indices' => 
                array (
                        array(
                            'name' => 'idx_workflow_executions_id',
                            'type' =>'index',
                            'fields'=>array('workflow_id'),
                            ),
                      ),
                'relationships' => 
                array (
                        'workflow_executions' =>
                        array(
                            'lhs_module'=> 'Workflows',
                            'lhs_table'=> 'workflows',
                            'lhs_key' => 'id',
                            'rhs_module'=> 'Executions',
                            'rhs_table'=> 'executions',
                            'rhs_key' => 'workflow_id',
                            'relationship_type'=>'one-to-many'
                            ),
                      ),
                'optimistic_lock' => true,
                ),
                );

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('Executions','Execution', array('basic','assignable'));
?>
