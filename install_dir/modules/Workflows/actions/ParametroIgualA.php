<?php

class ParametroIgualA extends WorkflowBaseAction
{
    public $nombre="Parametro igual a";
    public $tipo="choice";

    public function ejecutar($focus, $string_parametros)
    {
        $this->procesar_parametros($string_parametros);
        if ($focus->{$this->parametro} == $this->valor_esperado)
        {
            $message = "El valor de '$this->parametro' es igual a $this->valor_esperado";
            $out = True;
        }
        else
        {
            $message = "El valor de '$this->parametro' distinto de $this->valor_esperado";   
            $out = False;
        } 
        if (method_exists($focus, 'notificar'))
        {
            $focus->notificar( $message, "Condicion" );
        }
        return $out;
    }

    public function parametros_requeridos()
    {
        return array("parametro", "valor_esperado");
    }

}

?>
