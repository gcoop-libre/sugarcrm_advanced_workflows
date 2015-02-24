<?php

require_once('modules/Executions/Execution_sugar.php');
require_once('modules/Workflows/Workflow.php');
require_once('modules/Workflows/GcoopLogger.php');

class Execution extends Execution_sugar
{
    public function Execution()
    {
        $this->log = new GcoopLogger('workflow');
        parent::Execution_sugar();
    }

    public function ejecutar($focus, $workflow_id)
    {
        $this->inicializar_ejecucion($focus, $workflow_id);
        $this->recorrer_workflow();
    }

    public function info($mensaje)
    {
        if (empty($this->id))
            $id = "(sin id, no guardado)";
        else
            $id = $this->id;

        $this->log->log("Execution id='{$id}' sobre focus_id='{$this->focus->id}': $mensaje");
    }

    public function inicializar_ejecucion(&$focus, $workflow_id)
    {
        $this->focus =& $focus;
        $this->workflow = new Workflow();
        
        $this->workflow->retrieve($workflow_id);
        $this->name = "Ejecucion para workflow '{$this->workflow->name}' y '{$focus->name}'";
        $this->info("Ejecutando: '{$this->name}'");
        $this->workflow_id = $workflow_id;

        $fields = array(
                        'parent_type' => $this->focus->module_dir,
                        'parent_id' => $this->focus->id,
                        'workflow_id' => $this->workflow->id,
                        );

        $foo = $this->retrieve_by_string_fields($fields);

        // Si es un registro nuevo, le asigna nodo inicial
        if (is_null($this->id))
        {
            $this->node_id = $this->workflow->start_node_id;
            $this->parent_type = $this->focus->module_dir;
            $this->parent_id = $this->focus->id;
            $this->info("Iniciando por primera vez, asignando nodo: {$this->node_id}");
            $this->notificar("Nueva Ejecución del Workflow '{$this->workflow->name}'", "Nuevo");
        }
        else
        {
            $this->info("Continuando desde el nodo: {$this->node_id}");
            $this->notificar("Continuando Ejecución");
        }
    }

    public function recorrer_workflow()
    {
        while (true)
        {
            $nodo = $this->obtener_nodo();
            $this->info("Recorriendo desde el nodo: {$nodo->name}");

            try
            {
                if ($nodo->accion == "Pausar")
                {
                    $this->node_id = $nodo->siguiente;
                    $this->save();
                    $this->info("Pausando...");
                    $this->notificar("Esperando actualización\n", 'Pausa');
                    return;
                }

                $siguiente_id = $nodo->ejecutar($this->focus);
                $this->node_id = $siguiente_id;

                if (is_null($siguiente_id) || empty($siguiente_id)) 
                {
                    $this->info("Ha llegado al nodo final");
                    $this->notificar("Ejecución Terminada\n", 'Terminado');
                    $this->terminado = true;

                    if (isset($this->focus->inmutable))
                    {
                        $this->info("Inmutando el registro");
                        $this->notificar("Registro Inmutable", "Inmutable");
                        $this->focus->inmutable = true;
                        
                        if (isset($this->focus->save))
                            $this->focus->save();
                    }

                    $this->node_id = null;
                    $this->save();
                    return;
                }

                $this->error = "";
                $this->save();
            }
            catch (Exception $e)
            {
                $this->error = $e->getMessage();
                $this->info("Se ha detectado un error: {$this->error}.");
                $this->notificar("Error en la ejecución: {$this->error}", 'Error');
                $this->save();
                return;
            }
        }
    }
    function notificar($message)
    {
        if ( method_exists($this->focus, 'notificar'))
        {
            $this->focus->notificar($message);
        }
    }

    public function obtener_nodo()
    {
        return Workflow::obtener_nodo($this->node_id);
    }
}
?>
