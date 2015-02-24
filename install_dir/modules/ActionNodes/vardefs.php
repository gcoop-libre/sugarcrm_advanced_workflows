<?php
$dictionary = array (
  'ActionNode' => 
  array (
    'table' => 'actionnodes',
    'audited' => false,
    'duplicate_merge' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
    'fields' => 
    array (
      'accion' => 
      array (
        'name' => 'accion',
        'type' => 'enum',
        'len' => 100,
        'vname' => 'LBL_ACCION',
        'function' => array('name' => 'accion_nodo_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
        'required' => true
      ),
      'siguiente' => 
      array (
        'name' => 'siguiente',
        'type' => 'enum',
        'len' => 36,
        'vname' => 'LBL_SIGUIENTE',
        'function' => array('name' => 'vinculacion_nodos_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
        'required' => true,
      ),
      'parametros' => 
      array (
        'name' => 'parametros',
        'type' => 'text',
        'vname' => 'LBL_PARAMETROS',
        'default_value' => '{"clave":"valor", "clave2":"valor2"}',
      ),
      // INICIO Relacion
      // (1) Workflow -> (M) ActionNodes
      'workflow_link' =>
      array (
        'name' => 'workflow_link',
        'type' => 'link',
        'relationship' => 'workflow_actionnodes',
        'source'=>'non-db',
        'side' => 'right',
        'bean_name' => 'Workflow',
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
        'link'=>'workflow_link',
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
        'name' => 'idx_workflow_actionnodes_id',
        'type' =>'index',
        'fields'=>array('workflow_id'),
        ),
    ),
    'relationships' => 
    array (
      'workflow_actionnodes' =>
      array(
        'lhs_module'=> 'Workflows',
        'lhs_table'=> 'workflows',
        'lhs_key' => 'id',
        'rhs_module'=> 'ActionNodes',
        'rhs_table'=> 'actionnodes',
        'rhs_key' => 'workflow_id',
        'relationship_type'=>'one-to-many'
      ),
    ),
    'optimistic_lock' => true,
  ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('ActionNodes','ActionNode', array('basic','assignable'));
?>
