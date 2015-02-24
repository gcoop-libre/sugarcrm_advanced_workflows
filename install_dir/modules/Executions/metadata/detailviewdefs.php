<?php
$viewdefs['Executions']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array ('EDIT', 'CANCEL',
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
        0 => 'name',
        1 => 'node_id',
      ),
      1 => 
      array (
        0 => 'workflow_name',
        1 => 'terminado',
      ),
      2 => 
      array (
        0 => 'parent_name',
        1 => 'error',
      ),
    ),
  ),
);

?>
