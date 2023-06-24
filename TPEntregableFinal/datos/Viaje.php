<?php

class Viaje
{
    /**
     * Variables instancia de la clase Viaje
     * int $id
     * string $destino
     * int $cantMaxPasajeros
   
     */
    private $id;
    private $destino;
    private $cantMax;
    private $cantPasajeros;
    private $pasajeros = array();
    private $responsable;
    private $costoViaje;
    private $costosAbonados;
    //...

    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    public function getResponsable()
    {
        return $this->responsable;
    }

    //
    //GETTERS
    // 

    public function __toString()
    {
        $output = "ID del viaje: {$this->id}\n";
        $output .= "Destino: {$this->destino}\n";
        $output .= "Cantidad máxima de pasajeros: {$this->cantMax}\n";
        $output .= "Cantidad de pasajeros: {$this->cantPasajeros}\n";
        $output .= "Responsable: {$this->responsable}\n";
        $output .= "Costo del viaje: {$this->costoViaje}\n";
        $output .= "Costos abonados: {$this->costosAbonados}\n";
        $output .= "Pasajeros:\n";

        foreach ($this->pasajeros as $pasajero) {
            $output .= $pasajero->getApellido() . ", " . $pasajero->getNombre() . "\n";
        }

        return $output;
    }
    // Obtiene el valor de cantMaxPasajeros

    public function getCantMaxPasajeros()
    {
        return $this->cantMax;
    }
    public function getCantPasajeros()
    {
        return $this->cantPasajeros;
    }
    //Obtiene el valor de destino
    public function getDestino()
    {
        return $this->destino;
    }

    //Obtiene el valor de idViaje
    public function getIdViaje()
    {
        return $this->id;
    }


    public function cuentaCantPasajeros($cantPasajeros)
    {
        $this->cantPasajeros += $cantPasajeros;
    }

    public function getPasajeros()
    {
        return $this->pasajeros;
    }

    //
    //SETTERS
    //


    //Establece el valor de id

    public function setIdViaje($id)
    {
        $this->id = $id;
    }

    //Establece el destino
    public function setDestino($destino)
    {
        $this->destino = $destino;
    }

    //Establece el valor de cantMaxPasajeros
    public function setCantMaxPasajeros($cantMax)
    {
        $this->cantMax = $cantMax;
    }
    public function getCostoViaje()
    {
        return $this->costoViaje;
    }

    public function setCostoViaje($costo)
    {
        $this->costoViaje = $costo;
    }
    public function getCostosAbonados()
    {
        return $this->costosAbonados;
    }

    public function setCostosAbonados($costos)
    {
        $this->costosAbonados = $costos;
    }


    //antes del cambio de coleccion a bd... Deprecado tambien?????
    public function venderPasaje($objPasajero)
    {
        // Verificar si hay espacio disponible para el pasajero
        if ($this->hayPasajesDisponible()) {

            // Calcular el costo final del pasaje (sujeto a modif dependiendo el tipo de pasaje)



            //para calcular el costo real tengoq ue ir a por el metodo darPorcentajeIncremento en pasajero e hijas.
            $costoFinal = $this->costoViaje + $objPasajero->darPorcentajeIncremento();
            $this->setCostoViaje($costoFinal);
            // Actualizar los costos abonados
            $this->costosAbonados += $costoFinal;

            // Agregar el pasajero a la colección
            $this->cargarPasajeroVuelo($objPasajero);
            // Incrementar la cantidad de pasajeros
            $this->cuentaCantPasajeros(1);

            return $costoFinal;
        } else {
            return 0; // No hay espacio disponible, retorna 0 como indicador
        }
    }

    public function __construct()
    {
        $this->id = "";
        $this->destino = "";
        $this->cantMax = "";
        $this->cantPasajeros = 0;
        $this->pasajeros = array();
        $this->responsable = "";
        $this->costoViaje = "";
        $this->costosAbonados = 0;
    }

 
    public function cargarPasajeroVuelo($persona)
    {
        array_push($this->pasajeros, $persona);
    }
    public function hayPasajesDisponible()
    {
        return $this->cantPasajeros < $this->cantMax;
    }

    function agregarViaje($idEmpresa, $idResponsable)
    {

        //setea los datos del vuelo
        //echo "Indique el número del vuelo (numérico): " . "\n" . "¡ADVERTENCIA!: Si el valor ingresado no es numérico, el vuelo no podrá ser encontrado (validación pendiente de implementación):" . "\n";
        //$id = fgets(STDIN);

        echo "Indique el destino del viaje: ". "\n";
        $destino = trim(fgets(STDIN));
        /*
        echo "Indique la capacidad máxima de personas que tiene el viaje: ";
        $cantMax = fgets(STDIN);
        */
        echo "Indique el precio del viaje: ". "\n";
        $costoViaje = fgets(STDIN);

        //de aca saqué la carga del responsable.

        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->insertarViaje($destino, 0, $idEmpresa, $idResponsable, $costoViaje);
            $respSql = $conx->Ejecutar($sql);
            if ($respSql == 1) {
                echo "Viaje cargado con éxito". "\n";
            } else {
                echo "Error insertando Viaje". "\n";
            }
        } else {
            echo "Error conectando a la bd". "\n";
        }
    }

    function buscarViaje()
    {

        echo "Ingrese el 'idViaje' que desea buscar: ". "\n";
        $idViaje = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarViaje($idViaje);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql !== false) {
                // La consulta se ejecutó correctamente y se obtuvo un resultado
                if (!empty($respSql)) { //si no está vacio muestra el viaje encontrado, de lo contrario avisa que no coincide la busqueda.
                    print_r($respSql);
                    return $respSql;
                } else {
                    echo "No se encontró viaje para ese id". "\n";
                }
            } else {
                echo "la consulta no se ejecutó correctamente". "\n";
            }
        
        }
    }

    public function modificarViaje()
    {
        echo "Indique el nombre de la empresa que desea modificar" . "\n";
        $nomEmpresa = trim(fgets(STDIN));
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql) {
                echo "Ingrese un nuevo nombre para la empresa" . "\n";
                $nomEmpresa = fgets(STDIN);

                echo "Nueva dirección de la empresa" . "\n";
                $dirEmpresa = trim(fgets(STDIN));

                $sql = $conx->actualizarEmpresa($respSql['idempresa'], $nomEmpresa, $dirEmpresa);
                $respSql2 = $conx->Ejecutar($sql);
                if ($respSql2 == 1) {
                    echo "Actualización exitosa". "\n";

                } else {
                    echo "Falló actualización". "\n";
                }
            }
        }
    }
}






//codigo realizado para trabajar con colecciones, a partir de añadir una bd al sistema esto queda deprecado.




/*

function buscarViaje($listaViajes, $nroVuelo)
{
    for ($i = 0; $i < count($listaViajes); $i++) {
        $encontro = recuperarViaje($listaViajes[$i], $nroVuelo);
        if ($encontro) {
            return $listaViajes[$i];
        }
    }
}



function recuperarViaje($viaje, $nroVuelo)
{
    $id = $viaje->getIdViaje();
    if ($viaje->getIdViaje() == $nroVuelo) {
        //echo "encontró!!";
        return true;
    } else {
        //echo "NOOOO encontró";
        return false;
    }

    //$des = $viaje->getDestino();
    //echo "Destino: " . $des;
}


function listarViajes($listaViajes)
{
    for ($i = 0; $i < count($listaViajes); $i++) {
        $viaje = $listaViajes[$i];
        echo "Datos viaje: " . "\n" . "Destino: " . $viaje->getDestino() . "\n" . "Cantidad máxima de pasajeros: " . $viaje->getCantMaxPasajeros() . "\n";
    }
}
*/
?>