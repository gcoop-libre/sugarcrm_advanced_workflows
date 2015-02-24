<?php
require_once('include/MVC/Controller/SugarController.php');

class ChoiceNodesController extends SugarController{

    public function pre_save()
    {
        parent::pre_save();
        require_once('modules/Workflows/includes/WorkflowBaseAction.php');
        if (isset($this->bean->accion) && isset($this->record))
        {
            $accion = WorkflowBaseAction::obtener_accion_por_nombre($this->bean->accion);
            try
            {
                $accion->verificar_parametros($this->bean->parametros);
            }
            catch (Exception $e)
            {
                sugar_set_message("No se encontraron los parametros: ".implode(', ', $accion->parametros_requeridos())."; para la acción: $accion->nombre. No se Guardaron los cambios", "error");
                $args= array('module' => $this->module, 'action' => "EditView", 'record'=>$this->record);
                $this->set_redirect(create_url($args));
                $this->redirect();
                die();
            }
            
        }
    }
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

}
?>
