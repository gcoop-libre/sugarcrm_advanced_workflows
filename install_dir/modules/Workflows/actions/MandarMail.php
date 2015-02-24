<?php

require_once('modules/Workflows/includes/WorkflowBaseAction.php');

/*
 * Envia un correo electronico a un grupo de direcciones.
 *
 * El cuerpo del correo depende del objeto receptor (focus), ya
 * que se buscara invocar al metodo 'get_email_body' de esta instancia
 * al componer el mensaje. Inicialmente, si no hay un metodo ``get_email_body``
 * definido, el propio PHP va a ir a buscar al método SugarBean::get_email_body
 */
class MandarMail extends WorkflowBaseAction
{
    public $nombre = "Enviar Correo";
    public $tipo = 'action';

    public function ejecutar($focus, $string_parametros)
    {
        require_once('custom/include/gcoop_global_funcs.php');
        $this->procesar_parametros($string_parametros);

        $asunto = "Notificacion Automática: registro {$focus->name}";
        $cuerpo = $focus->get_email_body();
        $message = "Notificación enviada a: '$this->email'\n";
        sendSugarPHPMail(array($this->email), $asunto, $cuerpo);
        if (method_exists($focus, 'notificar'))
        {
            $focus->notificar( $message, 'Mail' );
        }
    }
    public function parametros_requeridos()
    {
        return array("email");
    }
}

?>
