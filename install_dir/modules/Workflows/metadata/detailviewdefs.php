<?php
$viewdefs['Workflows']['DetailView'] = array (
  'templateMeta' => 
  array (
    'form' => 
    array (
      'buttons' => 
      array (
            'EDIT',
            array("customCode" => '<input class="button" onclick="this.form.return_module.value=\'Workflows\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Preview\'; this.form.module.value=\'Workflows\';" type="submit" name="Preview" value="Previsualizar flujo">'),
            
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
        array (
            'name',
            '',
      ),
        array(
            'start_node_id',
            '',
        ),
        array(
            'target_module',
            '',
        ),
    ),
  ),
);

?>
