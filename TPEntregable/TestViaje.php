<?php

include "Viaje.php";
include "Pasajero.php";
include "ResponsableV.php";
include "PasajeroVip.php";
include "PasajeroEspecial.php";

$listaViajes = array();
$listaPasajeros = array();

$opcion = 0;

menu($opcion, $listaViajes, $listaPasajeros);

function menu($opcion, $listaViajes, $listaPasajeros)
{
    echo "¡Bienvenido/a!" . "\n";
    echo "Seleccione una opción para continuar: " . "\n" .
        "║ 1 ║ Cargar Viaje                        " . "\n" .
        "║ 2 ║ Buscar Viaje                        " . "\n" .
        "║ 3 ║ Cargar Pasajero                     " . "\n" .
        "║ 4 ║ Modificar Pasajero                  " . "\n" .
        "║ 5 ║ Buscar Pasajero                     " . "\n" .
        "║ 0 ║ Salir                               " . "\n";
    $opcion = fgets(STDIN);


    switch ($opcion) {
        case 0:
            ////echo "eligió la opción SALIR";
            exit; //finalizar ejecución.
        case 1:
            echo "eligió la opción 'Cargar Viaje'" . "\n";
            //cargar viaje
            $listaViajes = agregarViaje($listaViajes);
            menu($opcion, $listaViajes, $listaPasajeros);
            break;
        case 2:
            echo "Eligió la opción 'Buscar Viaje'" . "\n";
            // Buscar viaje en la colección de viajes.
            echo "Ingrese el 'Nro de Vuelo' que desea buscar: ";
            $x = trim(fgets(STDIN));
            $viaje = buscarViaje($listaViajes, $x);

            if (gettype($viaje) === 'object' && get_class($viaje) === 'Viaje') {
                // Si se encontró el vuelo, muestra sus datos junto con los datos de los pasajeros.
                echo $viaje;
            } else {
                echo "No se encontró el vuelo" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);
            break;
        case 3:
            echo "Para cargar una persona, antes debe indicar a continuación el vuelo donde desea ubicarlo: " . "\n";
            $nroVuelo = trim(fgets(STDIN));
            $vuelo = buscarViaje($listaViajes, $nroVuelo);
            //busca el vuelo
            if (gettype($vuelo) === 'object' && get_class($vuelo) === 'Viaje') {
                if ($vuelo->hayPasajesDisponible()) {
                    echo "Hay pasajes disponibles para el vuelo seleccionado" . "\n";
                    //si encontro el vuelo debe revisar que el pasajero no exista aun-.

                    $pasajerosVuelo = $vuelo->getPasajeros();
                    if (!empty($pasajerosVuelo)) {
                        //si encontró pasajeros busca el indicado
                        echo "Ingrese el dni del pasajero que desea cargar: " . "\n";
                        $n = trim(fgets(STDIN));
                        $condicion = validarPasajeroEnViaje($pasajerosVuelo, $n);
                        //si lo encuentra no lo puede volver a cargar
                        if ($condicion) {
                            echo "El pasajero ya se encuentra cargado en el vuelo" . "\n";
                        } else {


                            //si encontró el vuelo y el pasajero no se encuentra en el, lo carga
                            $pasajero = agregarPasajero($n, $listaViajes, $nroVuelo);

                            echo "Indique que tipo de pasaje quiere comprar:\n" . "1. VIP\n" . "2. Necesidades Especiales\n";
                            $tipoPasaje = fgets(STDIN);

                            if ($tipoPasaje == 1) {
                                echo "Indique su número de viajero frecuente: ";
                                $numVip = fgets(STDIN);

                                echo "Indique la cantidad de millas que tiene acumuladas: ";
                                $cantMillas = fgets(STDIN);

                                $objPasajero1 = new PasajeroVIP($pasajero->getDni(), $pasajero->getNombre(), $pasajero->getApellido(), $pasajero->getTelefono(), $pasajero->getNroVuelo(), $pasajero->getNumeroAsiento(), $pasajero->getNumeroTicket(), $numVip, $cantMillas);
                                //vender pasaje
                                $vuelo->venderPasaje($objPasajero1);
                        

                            } elseif ($tipoPasaje == 2) {
                                echo "Indique si utiliza silla de ruedas:\n " . "1. Si\n" . "2. No\n";
                                $sillaRuedas = fgets(STDIN);

                                echo "Indique si necesita asistencia para embarcar:\n " . "1. Si\n" . "2. No\n";
                                $asistenciaEmbarque = fgets(STDIN);

                                echo "Indique si necesita comida especial:\n " . "1. Si\n" . "2. No\n";
                                $comidaEsp = fgets(STDIN);

                                $objPasajero2 = new PasajeroNecesidadesEspeciales($pasajero->getDni(), $pasajero->getNombre(), $pasajero->getApellido(), $pasajero->getTelefono(), $pasajero->getNroVuelo(), $pasajero->getNumeroAsiento(), $pasajero->getNumeroTicket(), $sillaRuedas, $asistenciaEmbarque, $comidaEsp);
                                //vender pasaje
                                $vuelo->venderPasaje($objPasajero2);
                                //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                                $vuelo->cargarPasajeroVuelo($objPasajero2);
                                $vuelo->cuentaCantPasajeros(1);
                            } else {
                                //vender pasaje
                                $vuelo->venderPasaje($pasajero);
                                //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                                $vuelo->cargarPasajeroVuelo($pasajero);
                                $vuelo->cuentaCantPasajeros(1);
                            }

                        }
                    } else {
                        //si encontró el vuelo y el pasajero no se encuentra en el, lo carga
                        $pasajeroNew = agregarPasajero("", $listaViajes, $nroVuelo);

                        echo "Indique que tipo de pasaje quiere comprar:\n" . "1. VIP\n" . "2. Necesidades Especiales\n";
                        $tipoPasaje = fgets(STDIN);

                        if ($tipoPasaje == 1) {
                            echo "Indique su número de viajero frecuente: ";
                            $numVip = fgets(STDIN);

                            echo "Indique la cantidad de millas que tiene acumuladas: ";
                            $cantMillas = fgets(STDIN);

                            $objPasajero1 = new PasajeroVIP($pasajeroNew->getDni(), $pasajeroNew->getNombre(), $pasajeroNew->getApellido(), $pasajeroNew->getTelefono(), $pasajeroNew->getNroVuelo(), $pasajeroNew->getNumeroAsiento(), $pasajeroNew->getNumeroTicket(), $numVip, $cantMillas);
                            //vender pasaje
                            $vuelo->venderPasaje($objPasajero1);
                            //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                            $vuelo->cargarPasajeroVuelo($objPasajero1);
                            $vuelo->cuentaCantPasajeros(1);

                        } elseif ($tipoPasaje == 2) {
                            echo "Indique si utiliza silla de ruedas:\n " . "1. Si\n" . "2. No\n";
                            $sillaRuedas = fgets(STDIN);

                            echo "Indique si necesita asistencia para embarcar:\n " . "1. Si\n" . "2. No\n";
                            $asistenciaEmbarque = fgets(STDIN);

                            echo "Indique si necesita comida especial:\n " . "1. Si\n" . "2. No\n";
                            $comidaEsp = fgets(STDIN);

                            $objPasajero2 = new PasajeroNecesidadesEspeciales($pasajeroNew->getDni(), $pasajeroNew->getNombre(), $pasajeroNew->getApellido(), $pasajeroNew->getTelefono(), $pasajeroNew->getNroVuelo(), $pasajeroNew->getNumeroAsiento(), $pasajeroNew->getNumeroTicket(), $sillaRuedas, $asistenciaEmbarque, $comidaEsp);
                            //vender pasaje
                            $vuelo->venderPasaje($objPasajero2);
                            //agrega el pasajero a la coleccion de pasajeros en un vuelo.
                            $vuelo->cargarPasajeroVuelo($objPasajero2);
                            $vuelo->cuentaCantPasajeros(1);
                        } else {
                            echo "Error ingresando tipo de pasajero\n";
                        }
                    }
                } else {
                    echo "No hay pasajes disponibles para el vuelo seleccionado" . "\n";
                    // ...
                }
            } else {
                echo "No se encontro el vuelo" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);
            //vuelve a llamar al menu con las colecciones.
            break;
        case 4:
            echo "eligió la opción 'Modificar Pasajero'" . "\n";
            //MODIFICAR PERSONA.
            echo "Ingrese el dni del pasajero que desea modificar" . "\n";
            $n = trim(fgets(STDIN));
            $pers1 = buscarPasajero($listaPasajeros, $n);
            if (!empty($pers1)) {
                echo "Se encontro al pasajero con " . "\n" . "Nombre: " . $pers1->getNombre() . "\n" . " Apellido: " . $pers1->getApellido() . "\n" . "Nro de vuelo: " . $pers1->getVuelo() . "\n";
                modificarPasajero($listaPasajeros, $n);
            } else {
                echo "No se encontró al pasajero" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);
            break;
        case 5:
            echo "eligió la opción 'Buscar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea buscar" . "\n";
            $n = trim(fgets(STDIN));
            $pers1 = buscarPasajero($listaPasajeros, $n);
            if (!empty($pers1)) {
                echo "Se encontro al pasajero con " . "\n" . "Nombre: " . $pers1->getNombre() . "\n" . " Apellido: " . $pers1->getApellido() . "\n" . "Nro de vuelo: " . $pers1->getVuelo() . "\n";
            } else {
                echo "No se encontró al pasajero" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros);
            break;
        default:
            echo "Debe elegir una opción valida";
    }
}


/**
 ***************************SECCION viajes*************************************.
 */

/**
 * Summary of agregarViaje
 * @param array $listaViajes
 * @return array
 */
function agregarViaje($listaViajes)
{
    //crea instancia de clase viaje
    // $viaje = new Viaje();
    //setea los datos del vuelo
    echo "Indique el número del vuelo (numérico): " . "\n" . "¡ADVERTENCIA!: Si el valor ingresado no es numérico, el vuelo no podrá ser encontrado (validación pendiente de implementación):" . "\n";
    $id = fgets(STDIN);

    echo "Indique el destino del viaje: ";
    $destino = trim(fgets(STDIN));

    echo "Indique la capacidad máxima de personas que tiene el viaje: ";
    $cantMax = fgets(STDIN);

    echo "Indique el precio del viaje: ";
    $costoViaje = fgets(STDIN);

    echo "Ingrese los datos del responsable del vuelo: " . "\n";

    echo "Nombre" . "\n";
    $nombre = trim(fgets(STDIN));

    echo "Apellido: " . "\n";
    $apellido = trim(fgets(STDIN));

    echo "numEmpleado: " . "\n";
    $numEmpleado = fgets(STDIN);

    echo "numLicencia" . "\n";
    $numLicencia = fgets(STDIN);

    $responsable = new ResponsableV($nombre, $apellido, $numEmpleado, $numLicencia);
    //$responsable->cargarResponsable($nombre, $apellido, $numEmpleado, $numLicencia);

    //invoca al metodo insert de la clase viaje con los parametros indicados anteriormente.
    $viaje = new Viaje($id, $destino, $cantMax, $costoViaje); //con el constructor de la clase.
    // $viaje->cargarViaje($id,$destino,$cantMax);
    $viaje->setResponsable($responsable);

    //echo "Viaje cargado en array con éxito ";

    $listaViajes[] = $viaje;

    return $listaViajes;
}



/**
 * Summary of buscarViaje
 * @param array $listaViajes
 * @param int $n
 * @return mixed
 */
function buscarViaje($listaViajes, $nroVuelo)
{
    for ($i = 0; $i < count($listaViajes); $i++) {
        $encontro = recuperarViaje($listaViajes[$i], $nroVuelo);
        if ($encontro) {
            return $listaViajes[$i];
        }
    }
}


/**
 * Summary of mostrarViaje
 * @param Viaje $viaje
 * @return boolean
 */
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



/**
 * Summary of listarViajes
 * @param array $listaViajes
 * @return void
 */
function listarViajes($listaViajes)
{
    for ($i = 0; $i < count($listaViajes); $i++) {
        $viaje = $listaViajes[$i];
        echo "Datos viaje: " . "\n" . "Destino: " . $viaje->getDestino() . "\n" . "Cantidad máxima de pasajeros: " . $viaje->getCantMaxPasajeros() . "\n";
    }
}



/**
 ***************************SECCION PASAJEROS*************************************.
 */

/**
 * Summary of agregarPasajero
 * @param array $colPasajeros
 * @return Pasajero 
 */
function agregarPasajero($n, $listaViajes, $nroVuelo)
{
    //crea instancia de clase viaje
    // $persona1 = new Persona();
    //setea los datos del vuelo
    if (empty($n)) {
        echo "Indique el Dni del pasajero (numérico): ";
        $dni = fgets(STDIN);
    } else {
        $dni = $n;
    }
    echo "Indique el nombre del pasajero: ";
    $nombre = trim(fgets(STDIN));

    echo "Indique el apellido del pasajero: ";
    $apellido = trim(fgets(STDIN));

    echo "Indique el teléfono del pasajero: ";
    $telefono = fgets(STDIN);

    echo "Indique el número de asiento: ";
    $numeroAsiento = fgets(STDIN);

    echo "Indique el número de ticket: ";
    $numeroTicket = fgets(STDIN);



    //invoca al metodo insert de la clase viaje con los parametros indicados anteriormente.
    $persona1 = new Pasajero($dni, $nombre, $apellido, $telefono, $nroVuelo, $numeroAsiento, $numeroTicket);
    // $persona1->cargarPersona($dni, $nombre, $apellido, $telefono, $nroVuelo);
    //$viaje = buscarViaje($listaViajes, $nroVuelo);
    //$viaje->cuentaCantPasajeros(1);
    //$colPasajeros[]= $persona1;
    return $persona1;

}

/**
 * Summary of buscarPasajero
 * @param array $listaPasajeros
 * @param int $n
 * @return $persona
 */
function buscarPasajero($listaPasajeros, $n)
{
    for ($i = 0; $i < count($listaPasajeros); $i++) {
        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {
            return $persona;
        }
    }
}

/**
 * Summary of validarPasajeroEnViaje
 * @param array $listaPasajeros
 * @param int $n
 * @return boolean
 */
function validarPasajeroEnViaje($listaPasajeros, $n)
{
    $condicion = false;
    for ($i = 0; $i < count($listaPasajeros); $i++) {

        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {
            //echo "Se encontro al pasajero con " . "\n". "Nombre: " . $persona->getNombre() . "\n". " Apellido: " . $persona->getApellido() ."\n". "Nro de vuelo: ".$persona->getVuelo()."\n";
            $condicion = true;
        }

    }
    return $condicion;
}


/**
 * Summary of modificarPasajero
 * @param array $listaPasajeros
 * @param int $n
 * @return void
 */
function modificarPasajero($listaPasajeros, $n)
{

    for ($i = 0; $i < count($listaPasajeros); $i++) {

        $persona = $listaPasajeros[$i];
        if (trim($persona->getDni()) === $n) {

            echo "Indique el nombre del pasajero: ";
            $nombre = trim(fgets(STDIN));
            $persona->setNombre($nombre);

            echo "Indique el apellido del pasajero: ";
            $apellido = trim(fgets(STDIN));
            $persona->setApellido($apellido);

            echo "Indique el teléfono del pasajero: ";
            $telefono = fgets(STDIN);
            $persona->setTelefono($telefono);
        }

    }
}

/**
 * Summary of listarPasajeros
 * @param array $colPasajeros
 * @return void
 */
function listarPasajeros($colPasajeros)
{
    for ($i = 0; $i < count($colPasajeros); $i++) {
        $persona = $colPasajeros[$i];
        echo "Datos Pasajero: " . "\n" . "Nombre: " . $persona->getNombre() . "\n" . " Apellido: " . $persona->getApellido() . "\n";
    }

}

?>