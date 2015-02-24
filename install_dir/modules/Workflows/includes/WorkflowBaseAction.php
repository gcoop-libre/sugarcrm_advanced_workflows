<?php
class WorkflowBaseAction
{
    public $focus;
    public $params;

    function ejecutar($focus, $param_string)
    {
        throw new Exception('La clase "'.get_class($this).'" no implementa el metodo ejecutar ');
    }
    function parametros_requeridos()
    {
        throw new Exception('La clase "'.get_class($this).'" no implementa el metodo parametros_requeridos ');
    }
    function procesar_parametros($params_string)
    {
        $parametros = json_decode(html_entity_decode($params_string));
        foreach( $this->parametros_requeridos() as $parametro)
        {
            if (is_null($parametros->$parametro) || empty($parametros->$parametro))
            {
                throw new Exception("Se esperaba el parametro '$parametro'");
            }
            else
            {
                $this->$parametro = $parametros->$parametro;
            }
        }

        
    }
    function verificar_parametros($params_string)
    {
        $parametros = json_decode(html_entity_decode($params_string));
        foreach( $this->parametros_requeridos() as $parametro)
        {
            if (is_null($parametros->$parametro) || empty($parametros->$parametro))
            {
                throw new Exception("Se esperaba el parametro '$parametro'");
            }
        }
        return true;

    }
    static function obtener_accion_por_nombre($nombre_accion)
    {
        $path = "modules/Workflows/actions/$nombre_accion.php";
        if (file_exists( $path )  )
        {
            require_once( $path);
            if ( class_exists($nombre_accion))
            {
                $objeto = new $nombre_accion();
                if (get_parent_class($objeto) == 'WorkflowBaseAction')
                {
                    return $objeto;
                }
                else
                {
                    throw new Exception("La clase $nombre_accion no hereda de WorkflowBaseAction");
                
                }
            }
            else
            {
                throw new Exception("El archivo $path no contiene la clase $nombre_accion");
            }
        }
        else
        {
            throw new Exception( "El archivo $path no existe");
        }
    }
    static function obtener_lista_acciones($type=null)
    {
        $base_path='modules/Workflows/actions/*.php';
        $files = glob($base_path);
        $files = is_array($files) ? $files : array();
        $return_array = array();
        foreach($files as $path)
        {
            require_once($path);
            $class_name = pathinfo($path, PATHINFO_FILENAME);
            if ( class_exists($class_name) )
            {
                $action = new $class_name();
                $tipo_clase = $action->tipo;
                if (
                    (is_null($type) || (!is_null($tipo_clase) && $tipo_clase == $type)) 
                    && !empty($action->nombre) && (get_parent_class($action) == 'WorkflowBaseAction'))
                {
                    $return_array[$class_name] = $action->nombre;
                }
            }
        }
        return $return_array;
    }
}

?>
