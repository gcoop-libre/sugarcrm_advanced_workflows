<?php

class SiempreTrue extends WorkflowBaseAction
{
    public $nombre="Siempre Verdadero";
    public $tipo="choice";
    public function ejecutar($focus, $string_parametros)
    {
        return true;
    }
    public function parametros_requeridos()
    {
        return array("un_parametro");
    }
}

?>
