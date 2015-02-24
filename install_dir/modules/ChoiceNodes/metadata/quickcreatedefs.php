<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
 
$viewdefs = array (
  'ChoiceNodes' => 
  array (
    'QuickCreate' => 
    array (
      'templateMeta' => 
      array (
        'form' => 
        array (
          'buttons' => array (),
          'hidden' => array (
             '<input type="hidden" name="workflow_id" value="{$smarty.request.workflow_id}">',
          ),
        ),
        'maxColumns' => '2',
        'widths' => 
        array (
          array (
            'label' => '10',
            'field' => '30',
          ),
          array (
            'label' => '10',
            'field' => '30',
          ),
        ),
        'includes' => 
        array (
        ),
      ),
      'panels' => 
      array (
        'default' => 
        array (
          array (
              'name',
            ),
          ),
        ),
      ),
    ),
);
?>
