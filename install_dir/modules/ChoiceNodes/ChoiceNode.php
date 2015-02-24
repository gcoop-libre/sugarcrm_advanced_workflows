<?php

require_once('modules/ChoiceNodes/ChoiceNode_sugar.php');

class ChoiceNode extends ChoiceNode_sugar 
{
	var $siguiente_caso_true;
    var $siguiente_caso_false;
    function ChoiceNode()
    {
		parent::ChoiceNode_sugar();
	}

    function save($check_notify=false)
    {
        if ($this->siguiente_caso_true == 'end')
        {
            $this->siguiente_caso_true = null;
        }
        if ($this->siguiente_caso_false == 'end')
        {
            $this->siguiente_caso_false = null;
        }
        return parent::save($check_notify);
    }
    function definir_siguiente($nodo)
    {
        throw new Exception("Llamada ambigua!!, no se puede llamar a este metodo desde un choice node, los choice node no tienen un solo nodo siguiente...");
    }

    function definir_siguientes_nodos($nodo_caso_true, $nodo_caso_false)
    {
        assert(!is_null($nodo_caso_true->id));
        assert(!is_null($nodo_caso_false->id));

        $this->siguiente_caso_true = $nodo_caso_true->id;
        $this->siguiente_caso_false = $nodo_caso_false->id;

        $nodo_caso_true->workflow_id = $this->workflow_id;
        $nodo_caso_false->workflow_id = $this->workflow_id;

        $nodo_caso_true->save();
        $nodo_caso_false->save();

        $this->save();
    }

    function generar_arbol_descendientes()
    {
        if (is_null($this->siguiente_caso_true) || is_null($this->siguiente_caso_false))
            throw new Exception("Error al dibujar arbol, este choice node no tiene siguientes!.");

        $siguiente_caso_true = Workflow::obtener_nodo($this->siguiente_caso_true);
        $siguiente_caso_false = Workflow::obtener_nodo($this->siguiente_caso_false);

        return array($this->id => array(
                                        $siguiente_caso_true->generar_arbol_descendientes(), 
                                        $siguiente_caso_false->generar_arbol_descendientes())
                                        );
    }

    function generar_arbol_descendientes_sobre_grafo($grafo)
    {
        if (is_null($this->siguiente_caso_true) || is_null($this->siguiente_caso_false))
            throw new Exception("Error al dibujar arbol, este choice node no tiene siguientes!.");

        $nombre_partida = "{$this->name}\\n({$this->accion})";
        
        
        if(!empty($this->siguiente_caso_true))
        {
            $siguiente_caso_true = Workflow::obtener_nodo($this->siguiente_caso_true);
            $nombre_llegada_true = "{$siguiente_caso_true->name}\\n({$siguiente_caso_true->accion})";
        }
        else
        {
            $nombre_llegada_true = 'Ejecución Terminada';
        }
        if(!empty($this->siguiente_caso_false))
        {
            $siguiente_caso_false = Workflow::obtener_nodo($this->siguiente_caso_false);
            $nombre_llegada_false = "{$siguiente_caso_false->name}\\n({$siguiente_caso_false->accion})";
        }
        else
        {
            $nombre_llegada_false = 'Ejecución Terminada';
        }

        $nombre_partida = "{$this->name}\\n({$this->accion})";


        $grafo->agregar($nombre_partida, $nombre_llegada_true, "VERDADERO", '#9ac836', 'octagon', 'ne');
        $grafo->agregar($nombre_partida, $nombre_llegada_false, "FALSO", '#ff9c00', 'octagon', 'se');

        if (!$grafo->existe_nodo_salida($nombre_llegada_true))
            $siguiente_caso_true->generar_arbol_descendientes_sobre_grafo($grafo);

        if (!$grafo->existe_nodo_salida($nombre_llegada_false))
            $siguiente_caso_false->generar_arbol_descendientes_sobre_grafo($grafo);
    }

    function ejecutar(&$focus)
    {
        $accion = WorkflowBaseAction::obtener_accion_por_nombre($this->accion);
        $resultado = $accion->ejecutar($focus, $this->parametros);
        return $resultado ? $this->siguiente_caso_true : $this->siguiente_caso_false;
    }
}
?>
