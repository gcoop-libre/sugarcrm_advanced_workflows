<?php
$dictionary = array (
  'Workflow' => 
  array (
    'table' => 'workflows',
    'audited' => false,
    'duplicate_merge' => true,
    'unified_search' => true,
    'unified_search_default_enabled' => true,
    'importable' =>  false,
    'fields' => 
    array (
      'start_node_id' => 
      array (
        'name' => 'start_node_id',
        'type' => 'enum',
        'len' => 36,
        'vname' => 'LBL_START_NODE_ID',
        'function' => array('name' => 'vinculacion_nodos_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
        ),
       'target_module' => 
       
       array(
            'name' => 'target_module',
            'type' => 'enum',
            'len' => 36,
            'vname' => 'LBL_TARGET_MODULE',
            'function' => array('name' => 'available_modules_dom', 'include' => 'modules/Workflows/GcoopWorkflowsHelper.php'),
       ),
      
      // Para visualizar el Subpanel de ActionNodes en workflow
      'workflow_actionnodes' =>
       array (
        'name' => 'workflow_actionnodes',
        'type' => 'link',
        'relationship' => 'workflow_actionnodes',
        'source' => 'non-db',
        'vname' => 'LBL_ACTIONNODES',
      ),
      // Para visualizar el Subpanel de ChoiceNodes en workflow
      'workflow_choicenodes' =>
       array (
        'name' => 'workflow_choicenodes',
        'type' => 'link',
        'relationship' => 'workflow_choicenodes',
        'source' => 'non-db',
        'vname' => 'LBL_CHOICENODES',
      ),
      // Para visualizar el Subpanel de Executions en workflow
      'workflow_executions' =>
       array (
        'name' => 'workflow_executions',
        'type' => 'link',
        'relationship' => 'workflow_executions',
        'source' => 'non-db',
        'vname' => 'LBL_EXECUTIONS',
      ),

      // Para visualizar el Subpanel de Executions en workflow
      'workflow_segurcoop_administrador' =>
       array (
        'name' => 'workflow_segurcoop_administrador',
        'type' => 'link',
        'relationship' => 'workflow_segurcoop_administrador',
        'source' => 'non-db',
        'vname' => 'LBL_SEGURCOOP_ADMINISTRADOR',
      ),
    ),
    'relationships' => 
    array (
    ),
    'optimistic_lock' => true,
  ),
);

require_once('include/SugarObjects/VardefManager.php');
VardefManager::createVardef('Workflows','Workflow', array('basic','assignable'));
?>
