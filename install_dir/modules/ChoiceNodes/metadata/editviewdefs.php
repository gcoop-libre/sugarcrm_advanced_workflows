<?php
$viewdefs['ChoiceNodes']['EditView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
          'SAVE',
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
   'includes' => 
      array (
        array (
          'file' => 'custom/include/javascript/jquery.js',
        ),
        array (
          'file' => 'custom/include/javascript/gcooptools.js',
        ),
        array (
          'file' => 'modules/Workflows/javascript/action_helper.js',
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
        'siguiente_caso_true',
        'siguiente_caso_false',
      ),
    ),
  ),
);

?>
