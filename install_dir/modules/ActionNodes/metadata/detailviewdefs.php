<?php
$viewdefs['ActionNodes']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
          'EDIT',
          'CANCEL',
      ),
      'hideAudit' => true,
    ),
    'maxColumns' => '2',
    'widths' => 
    array (
      array (
        'label' => '15',
        'field' => '25',
      ),
      array (
        'label' => '15',
        'field' => '25',
      ),
    ),
  ),
  'panels' => 
  array (
    'default' => 
    array (
      array (
        'name',
        'accion',
      ),
      array (
        '',
        'parametros',
      ),
    ),
    'siguiente' => 
    array (
      array (
        'siguiente',
        '',
      ),
    ),
  ),
);

?>
