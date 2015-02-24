<?php
$viewdefs['Executions']['EditView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
      ),
      'hideAudit' => true,
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      0 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
      1 => 
      array (
        'label' => '10',
        'field' => '30',
      ),
    ),
  ),
  'panels' => 
  array (
    'default' => 
    array (
      0 => 
      array (
        'workflow_name' =>
        array('name' => 'name', 'type' => 'readonly'),
        1 => 'node_id',
      ),
      1 => 
      array (
        'workflow_name' =>
        array('name' => 'workflow_name', 'type' => 'readonly'),
        1 => 'terminado',
      ),
      2 => 
      array (
        'parent_name' =>
        array('name' => 'parent_name', 'type' => 'readonly'),
        1 => 'error',
        ),
    ),
  ),
);

?>
