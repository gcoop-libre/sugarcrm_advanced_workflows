<?php

require_once('modules/Workflows/includes/WorkflowBaseAction.php');

class CrearAlarma extends WorkflowBaseAction
{
    public $nombre = "Crear Alarma";
    public $tipo = 'action';

    public function ejecutar($focus, $string_parametros)
    {
        $this->procesar_parametros($string_parametros);
        
        $alarma = loadBean('gcoop_alarmas');
        
        $alarma->parent_type = $focus->module_dir;
        $alarma->parent_id = $focus->id;
        $alarma->destinatario = $this->destinatario;
        $alarma->notificacion = $this->notificacion;
        $alarma->parametro = $this->parametro;
        $alarma->valor = $this->valor;

        #sumarle dias no fin de semana!

        $fecha = new DateTime();
        $dia = new DateInterval('P1D');
        while( $this->cantidad_dias > 0)
        {
            $fecha->add($dia);
            if ($fecha->format('N') < 6)
            {
                $this->cantidad_dias -= 1;
            }
        }
        $timedate = TimeDate::getInstance();
        $alarma->fecha_disparo = $timedate->asDb($fecha);
        $alarma->save();
        if (method_exists($focus, 'notificar'))
        {
            $focus->notificar( "Se creó alarama con fecha de disparo $alarma->fecha_disparo", 'Alarma' );
        }
    }
    public function parametros_requeridos()
    {
        return array('destinatario', 'notificacion', 'cantidad_dias', 'parametro', 'valor');
    }
}

?>
