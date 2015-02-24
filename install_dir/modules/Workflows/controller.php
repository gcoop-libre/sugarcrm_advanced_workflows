<?php
require_once('include/MVC/Controller/SugarController.php');
require_once('modules/Workflows/includes/WorkflowBaseAction.php');

class WorkflowsController extends SugarController{

    public function loadBean()
    {
        #esta es nuestra versión parchada para poder tener el bean antes 
        if(!empty($GLOBALS['beanList'][$this->module]))
        { 
            $class = $GLOBALS['beanList'][$this->module]; 
            if(!empty($GLOBALS['beanFiles'][$class]) and is_null($this->bean))
            {
                require_once($GLOBALS['beanFiles'][$class]); 
                $this->bean = new $class(); 
                if(!empty($this->record))
                { 
                    $this->bean->retrieve($this->record); 
                    if($this->bean) 
                        $GLOBALS['FOCUS'] = $this->bean; 
                }   
            }   
            else 
            {
                $GLOBALS['FOCUS'] = $this->bean; 
            } 
        }
    }

    public function action_Preview()
    {
        $this->loadBean();
        $this->view = 'preview';
    }

    /*
     * Retorna la imagen que le corresponde a un determinado
     * registro workflow.
     */
    public function action_Imagen()
    {
        $this->loadBean();
        $nombre = $this->bean->generar_grafico_png_de_flujo();

        $ruta = $GLOBALS['sugar_config']['upload_dir']."/$nombre.png";

        header('Content-Type: image/png');
        readfile($ruta);
        unlink($ruta);
        die();
    }
    public function action_Parametros()
    {
        $action_name = $_REQUEST['function'];
        $ret_array = array();
        try
        {
            $entity = WorkflowBaseAction::obtener_accion_por_nombre($action_name);
            $parametros = $entity->parametros_requeridos();
            $ret_array = array();
            foreach ($parametros as $name)
            {
                $ret_array[$name] = "";
            }
        }
        catch (Exception $e)
        {
            
        }
        ob_clean();
        header('Content-Type: text/plain');
        echo json_encode($ret_array);
        die();
    }
}
?>
