<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/json_config.php');
require_once('include/MVC/View/views/view.detail.php');


class WorkflowsViewPreview extends ViewDetail {


 	function WorkflowsViewPreview(){
 		parent::ViewDetail();
 	}

 	/**
 	 * display
 	 *
 	 * We are overridding the display method to manipulate the portal information.
 	 * If portal is not enabled then don't show the portal fields.
 	 */
 	function display()
    {
        echo "<h1>Vista previa {$this->bean->name}</h1>";
        $this->imprimir_boton_regresar();
        $elemento_html = $this->bean->generar_imagen_de_flujo_para_vista_html();
        echo $elemento_html;
        $this->imprimir_boton_regresar();
 	}

    function imprimir_boton_regresar()
    {
        $url = array (
            'module' => 'Workflows',
            'action' => 'DetailView',
            'record' => $this->bean->id,
            );

        $path = create_url($url);
        echo "<p><a href='$path'>Regresar al workflow.</a>";
    }
}

?>
