<?php

class AsignarCampo extends WorkflowBaseAction
{
    public $nombre="Asignar campo";
    public $tipo="action";

    public function ejecutar($focus, $string_parametros)
    {
        $this->procesar_parametros($string_parametros);
        $focus->{$this->parametro} = $this->valor;
        $focus->save();
    }

    public function parametros_requeridos()
    {
        return array("parametro", "valor");
    }
}

?>
