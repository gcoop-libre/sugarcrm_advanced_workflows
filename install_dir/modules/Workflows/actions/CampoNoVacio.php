<?php
class CampoNoVacio extends WorkflowBaseAction
{
    public $nombre="Campo No Vacio";
    public $tipo="choice";

    public function ejecutar($focus, $string_parametros)
    {
        $this->procesar_parametros($string_parametros);
        if ( !empty($focus->{$this->parametro}) )
        {
            $message = "El valor de '$this->parametro' es distinto de vacio";
            $out = True;
        }
        else
        {
            $message = "El valor de '$this->parametro' es vacio";   
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
        return array("parametro");
    }

}

