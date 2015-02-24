<?php
require_once( 'modules/Workflows/includes/WorkflowBaseAction.php');
require_once( 'modules/ActionNodes/ActionNode_sugar.php');

class ActionNode extends ActionNode_sugar 
{
	function ActionNode()
    {
        $this->siguiente = null;
		parent::ActionNode_sugar();
	}


    function save($check_notify=false)
    {
        if ($this->siguiente == 'end')
        {
            $this->siguiente = null;
        }
        return parent::save($check_notify);
    }
    function definir_siguiente($nodo)
    {
        if (empty($nodo->id))
            throw new Exception("Error: El nodo para asociar no se ha guardado aun.");

        if (empty($this->id))
            throw new Exception("Error: El nodo actual no ha guardado, no se le puede asignar un siguiente.");

        $nodo->workflow_id = $this->workflow_id;
        $this->siguiente = $nodo->id;
        $this->save();
        $nodo->save();
    }

    function generar_arbol_descendientes()
    {
        if (empty($this->siguiente))
        {
            $descendientes = array();
        }
        else
        {
            $siguiente = Workflow::obtener_nodo($this->siguiente);
            $descendientes = $siguiente->generar_arbol_descendientes();
        }

        return array($this->id => $descendientes);
    }

    function generar_arbol_descendientes_sobre_grafo($grafo)
    {
        $nombre_llegada = 'Ejecución Terminada';
        $nombre_partida = "{$this->name}\\n({$this->accion})";
        if (!empty($this->siguiente))
        {
            $siguiente = Workflow::obtener_nodo($this->siguiente);
            $nombre_llegada = "{$siguiente->name}\\n({$siguiente->accion})";
        }
        $grafo->agregar($nombre_partida, $nombre_llegada);

        if (!$grafo->existe_nodo_salida($nombre_llegada))
            $siguiente->generar_arbol_descendientes_sobre_grafo($grafo);
    }

    function ejecutar( $focus )
    {
        $accion = WorkflowBaseAction::obtener_accion_por_nombre($this->accion);
        $accion->ejecutar($focus, $this->parametros);
        return $this->siguiente;
    }
}
?>
