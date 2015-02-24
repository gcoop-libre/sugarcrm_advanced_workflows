<?php
$dictionary = array (
  'ChoiceNode' => 
  array (
    'table' => 'choicenodes',
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
        'required' => true,
      ),
      'siguiente_caso_true' => 
      array (
        'name' => 'siguiente_caso_true',
        'type' => 'enum',
        'len' => 36,
        'vname' => 'LBL_SIGUIENTE_CASO_TRUE',
        'function' => array('name' => 'vinculacion_nodos_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
      ),
      'siguiente_caso_false' => 
      array (
        'name' => 'siguiente_caso_false',
        'type' => 'enum',
        'len' => 36,
        'vname' => 'LBL_SIGUIENTE_CASO_FALSE',
        'function' => array('name' => 'vinculacion_nodos_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
      ),
      'parametros' => 
      array (
        'name' => 'parametros',
        'type' => 'text',
        'vname' => 'LBL_PARAMETROS',
        'default_value' => '{"clave":"valor", "clave2":"valor2"}',
      ),
      // INICIO Relacion
      // (1) Workflow -> (M) ChoiceNodes
      'workflow_link' =>
      array (
        'name' => 'workflow_link',
        'type' => 'link',
        'relationship' => 'workflow_choicenodes',
        'source'=>'non-db',
        'side' => 'right',
        'vname'=>'LBL_WORKFLOW',
        'bean_name' => 'Workflow',
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
        'name' => 'idx_workflow_choicenodes_id',
        'type' =>'index',
        'fields'=>array('workflow_id'),
        ),
    ),
    'relationships' => 
    array (
      'workflow_choicenodes' =>
      array(
        'lhs_module'=> 'Workflows',
        'lhs_table'=> 'workflows',
        'lhs_key' => 'id',
        'rhs_module'=> 'ChoiceNodes',
        'rhs_table'=> 'choicenodes',
        'rhs_key' => 'workflow_id',
        'relationship_type'=>'one-to-many'
      ),
    ),
    'optimistic_lock' => true,
  ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('ChoiceNodes','ChoiceNode', array('basic','assignable'));
?>
