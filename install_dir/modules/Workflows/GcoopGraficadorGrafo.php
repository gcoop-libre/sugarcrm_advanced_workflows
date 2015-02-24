<?php

class GcoopGraficadorGrafo
{
    function __construct()
    {
        $this->nodos_salida = array();
        $this->nodos_salida[] = "Inicio Ejecución";
        $this->nodos_salida[] = "Ejecución Terminada";
        $this->relaciones = "";
        $this->nodos = "";
        $this->texto = "digraph G {
    rankdir=LR
    overlap=scalexy
    splines=true
    node [shape=rect, style=filled, fontname=Helvetica, fontsize=8];
    edge [fontname=Helvetica, fontsize=6];\n\n";

        if (!file_exists("/usr/bin/dot"))
            throw new Exception("Error, debe instalar graphviz para graficar grafos.");
    }

    /*
     * Agrega un enlace entre dos nodos, opcionalmente se puede colocar
     * una etiqueta:
     *
     *      >>> $g->agregar("uno", "dos", "le sigue el...");
     *      >>> $g->agregar("dos", "tres");
     *
     */
    function agregar($nodo_a, $nodo_b, $etiqueta="", $color='#828282', $type='rect', $tailport='_')
    {
        if ($type == "rect")
        {
            $color_caja="#196674";
        }
        else
        {
            $color_caja="#33acb2";
        }
        $this->nodos .= "\t\"$nodo_a\"[shape=\"$type\", color=\"$color_caja\", fontcolor=white];\n";
        $this->relaciones .= "\t\"$nodo_a\" -> \"$nodo_b\" [taillabel=\"$etiqueta\", tailport=\"$tailport\", arrowsize=1, color=\"$color\"];\n";
        $this->nodos_salida[] = $nodo_a;
    }

    function existe_nodo_salida($nombre)
    {
        return in_array($nombre, $this->nodos_salida);
    }

    /*
     * Genera un archivo png y dot con un grafico de los nodos conectados.
     *
     */
    function generar_png($directorio_salida)
    {
        $this->texto .=  $this->nodos . "\n" . $this->relaciones;
        $this->texto .= "}";
        $nombre_archivo = $this->obtener_nombre_salida($directorio_salida);
        $this->crear_y_cargar_archivo("$nombre_archivo.dot", $this->texto);

        $comando = $this->crear_png_desde_dot("$nombre_archivo.dot", "$nombre_archivo.png");
        return md5($this->texto);
    }

    function borrar_grafo_si_existe($directorio_salida)
    {
        $nombre_archivo = $this->obtener_nombre_salida($directorio_salida);
        $archivo_dot = "$nombre_archivo.dot";
        $archivo_png = "$nombre_archivo.png";

        if (file_exists($archivo_dot))
            unlink($archivo_dot);

        if (file_exists($archivo_png))
            unlink($archivo_png);
    }

    /*
     * Invoca a dot para convertir archivos dot en png.
     *
     */
    private function crear_png_desde_dot($archivo_dot, $archivo_png)
    {
        $comando = "dot -Tpng $archivo_dot > $archivo_png";
        $salida = exec($comando);
        return $comando;
    }

    /*
     * Genera un archivo y define su contenido inicial.
     *
     */
    function crear_y_cargar_archivo($nombre_archivo, $contenido)
    {
        $archivo = fopen($nombre_archivo, "wt");
        fwrite($archivo, $contenido);
        fclose($archivo);
    }

    /*
     * Genera un nombre de archivo en base al contenido a mostrar.
     *
     * La ruta se retorna sin extension.
     */
    function obtener_nombre_salida($directorio_salida)
    {
        $nombre = md5($this->texto);
        return "$directorio_salida/$nombre";
    }
}

?>
