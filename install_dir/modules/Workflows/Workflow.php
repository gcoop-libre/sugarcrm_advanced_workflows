<?php
require_once('config.php');
require_once('modules/Workflows/Workflow_sugar.php');
require_once('modules/ActionNodes/ActionNode.php');
require_once('modules/ChoiceNodes/ChoiceNode.php');
require_once('modules/Workflows/GcoopGraficadorGrafo.php');
require_once('modules/Workflows/GcoopLogger.php');

class Workflow extends Workflow_sugar 
{
	function Workflow()
    {
        $this->log = new GcoopLogger('workflow');
		parent::Workflow_sugar();
	}

    function info($mensaje)
    {
        $this->log->log("Workflow id='{$this->id}': $mensaje");
    }

    /*
     * Define el nodo inicial para un workflow.
     *
     * El nodo inicial no solo está señalada por el workflow, sino que
     * el nodo también sabe a qué workflow pertenece.
     */
    function definir_nodo_inicial($nodo)
    {
        if (is_null($nodo->id))
            throw new Exception("Error: El nodo indicado no se ha guardado aun.");

        if (is_null($this->id))
            throw new Exception("Error: El workflow no se guardo aun.");

        $this->start_node_id = $nodo->id;
        $nodo->workflow_id = $this->id;
        $this->info("Asignando el nodo inicial {$nodo->id}");
        $nodo->save();
        $this->save();
    }


    /* 
     * Elimina todos los nodos asociados a este workflow
     */
    function limpiar()
    {
        $nodos = $this->obtener_nodos();

        foreach ($nodos as $nodo)
            $nodo->mark_deleted($nodo->id);

        $this->info("Borrando todos los nodos");
    }

    /* 
     * Retorna una lista con todos los nodos.
     * 
     * La lista retornada contendrá tanto "actionsNodes" cómo "choiceNodes".
     *
     */
    function obtener_nodos()
    {
        $action_nodes = $this->get_linked_beans("workflow_actionnodes", "ActionNode");
        $choice_nodes = $this->get_linked_beans("workflow_choicenodes", "ChoiceNode");

        return array_merge($action_nodes, $choice_nodes);
    }

    /*
     * Imprime en pantalla una lista con todos los nodos.
     */
    function obtener_flujo_como_array()
    {
        $nodo_inicial = $this->obtener_nodo_inicial();
        $arbol = $nodo_inicial->generar_arbol_descendientes();
        return $arbol;
    }

    function obtener_flujo_como_grafo()
    {
        $grafo = new GcoopGraficadorGrafo();

        $nodo_inicial = $this->obtener_nodo_inicial();
        $nodo_inicial->generar_arbol_descendientes_sobre_grafo($grafo);

        return $grafo;
    }

    function obtener_nodo_inicial()
    {
        $id = $this->start_node_id;
        assert(!is_null($id));
        return Workflow::obtener_nodo($id);
    }

    /*
     * Retorna un objeto Nodo.
     *
     * El objeto retornado puede ser un actionnode o un choicenode.
     */
    public static function obtener_nodo($id)
    {
        $nodo = new ActionNode();
        $nodo->retrieve($id);

        if (empty($nodo->id))
        {
            $nodo = new ChoiceNode();
            $nodo->retrieve($id);

            if (empty($nodo->id))
            {
                throw new Exception("Error en 'obtener_nodo', no se encuentra el nodo id='$id'");
            }
        }

        return $nodo;
    }

    function generar_imagen_de_flujo_para_vista_html()
    {
        $url = array (
            'module' => 'Workflows',
            'action' => 'Imagen',
            'record' => $this->id,
            );

        $path = create_url($url);
        $this->info("Generando la imagen: $path");
        return "<div style=\"overflow: auto; width: 1024px\" ><img src='$path'/></div>";
    }

    function generar_grafico_png_de_flujo()
    {
        $grafo = $this->obtener_flujo_como_grafo();

        $directorio_destino = $GLOBALS['sugar_config']['upload_dir'];
        $grafo->borrar_grafo_si_existe($directorio_destino);
        
        $nombre_archivo = $grafo->generar_png($directorio_destino);
        return $nombre_archivo;
    }
    static function get_workflow_id($bean)
    {
        $module = $bean->module_dir;
        $workflow = loadBean('Workflows');
        $workflow->retrieve_by_string_fields(array('target_module' => $module));
        return $workflow->id;
    }
}

?>
