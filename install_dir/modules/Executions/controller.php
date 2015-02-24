<?php
require_once('include/MVC/Controller/SugarController.php');

class ExecutionsController extends SugarController{

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
