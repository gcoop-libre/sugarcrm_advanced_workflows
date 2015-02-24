<?php

class SiempreFalse extends WorkflowBaseAction
{
    public $nombre="Siempre Falso";
    public $tipo="choice";
    public function ejecutar($focus, $string_parametros)
    {
       return false; 
    }
    public function parametros_requeridos()
    {
        return array();
    }
}

?>
