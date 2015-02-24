<?php
require_once('custom/include/gcoop_global_funcs.php');
function vinculacion_nodos_dom($focus, $field, $value, $view)
{
    if (is_null($view))
    {
        return array($value => $value);
    }
    $return_dom = array('end' => 'Terminar Ejecución');
    
    if ($view != "EditView")
    {
        $return_dom[''] = 'Terminar Ejecución';
    }

    if (get_class($focus) == "Workflow") 
        $id = $focus->id;
    else
    {
        if (isset($focus->workflow_id))
            $id = $focus->workflow_id;
        else
            return $return_dom;
    }

    $sql_choicenodes = "
            SELECT id, name
            FROM choicenodes
            WHERE workflow_id = '$id'
            ";
    $sql_actionnodes = "
            SELECT id, name
            FROM actionnodes
            WHERE workflow_id = '$id'
            ";

    $resultado = localSqlQuery($sql_choicenodes);
    $resultado = array_merge(localSqlQuery($sql_actionnodes), $resultado);
    foreach ($resultado as $node) {
        $return_dom[$node['id']] = $node['name'];
    }

    return $return_dom;
}

function accion_nodo_dom($focus, $field, $value, $view)
{
    require_once('modules/Workflows/includes/WorkflowBaseAction.php');
    if ($view != 'EditView' && $view != 'QuickCreate')
    {
        if (!empty($value) && $value != 'Pausar')
        {
            $objeto = WorkflowBaseAction::obtener_accion_por_nombre($value);
            $accion = $objeto->nombre;
        }
        else
        {
            $accion = $value;
        }
        return array($value=>$accion);
    }
    else
    {
        $return_dom = array('' => '<Seleccionar>');
        $type = (get_class($focus) == 'ActionNode') ? 'action' : 'choice';
        $return_dom += WorkflowBaseAction::obtener_lista_acciones($type);
        if ($type == 'action')
            $return_dom['Pausar'] = 'Pausar';
    }

    return $return_dom;
}

function available_modules_dom($focus, $field, $value, $view)
{
    require_once('modules/Workflows/EnabledModules.php');
    global $app_list_strings;
    $modulos[''] = '';
    foreach ($enabled as $modulo)
    {
        $modulos[$modulo] = $app_list_strings['moduleList'][$modulo];
    }
    if ($view != 'EditView' && $view != 'QuickCreate')
    {
        return $modulos;
    } 
    else
    {
        #query a la base de datos para saber que modulos tienen workflow
        $query = "SELECT target_module FROM workflows WHERE target_module IS NOT NULL";
        $modulos_asignados = localSqlQuery($query);

        foreach ($modulos_asignados as $modulo)
        {
            if (!empty($modulo))
            {
                unset($modulos[$modulo['target_module']]);
            }
        }
        return $modulos;
    }
}

?>
