<?php

class DecirHolaMundo extends WorkflowBaseAction
{
    public $nombre = 'Decir Hola Mundo';
    public $tipo = 'action';
    
    function parametros_requeridos()
    {
        return array('nombre');
    }
    public function ejecutar($focus, $string_parametros)
    {
        echo "Hola Mundo $this->nombre\n";
    }
}

?>
