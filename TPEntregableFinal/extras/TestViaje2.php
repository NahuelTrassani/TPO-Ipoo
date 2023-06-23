<?php

include "Viaje.php";
include "Pasajero.php";
include "ResponsableV.php";
include "PasajeroVip.php";
include "PasajeroEspecial.php";

// Conexión a la base de datos
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "tu_basededatos";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$listaViajes = array();
$listaPasajeros = array();

$opcion = 0;

menu($opcion, $listaViajes, $listaPasajeros, $conn);

function menu($opcion, $listaViajes, $listaPasajeros, $conn)
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
            $listaViajes = agregarViaje($listaViajes, $conn);
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
        case 2:
            echo "Eligió la opción 'Buscar Viaje'" . "\n";
            // Buscar viaje en la base de datos.
            echo "Ingrese el 'Nro de Vuelo' que desea buscar: ";
            $x = trim(fgets(STDIN));
            $viaje = buscarViaje($x, $conn);

            if ($viaje !== null) {
                // Si se encontró el vuelo, muestra sus datos junto con los datos de los pasajeros.
                echo $viaje;
            } else {
                echo "No se encontró el vuelo" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
        case 3:
            echo "Para cargar una persona, antes debe indicar a continuación el vuelo donde desea ubicarlo: " . "\n";
            $nroVuelo = trim(fgets(STDIN));
            $vuelo = buscarViaje($nroVuelo, $conn);
            //busca el vuelo
            if ($vuelo !== null) {
                if ($vuelo['cant_pasajes_disponibles'] > 0) {
                    echo "Hay pasajes disponibles para el vuelo seleccionado" . "\n";
                    //si encontró el vuelo y hay pasajes disponibles
                    echo "Ingrese el 'Tipo de Pasajero' que desea cargar: " . "\n" .
                        "║ 1 ║ Pasajero VIP                        " . "\n" .
                        "║ 2 ║ Pasajero Especial                   " . "\n" .
                        "║ 3 ║ Responsable de Vuelo                 " . "\n";
                    $tipoPasajero = trim(fgets(STDIN));

                    switch ($tipoPasajero) {
                        case 1:
                            echo "eligió la opción 'Pasajero VIP'" . "\n";
                            cargarPasajero($vuelo, new PasajeroVip(), $conn);
                            break;
                        case 2:
                            echo "eligió la opción 'Pasajero Especial'" . "\n";
                            cargarPasajero($vuelo, new PasajeroEspecial(), $conn);
                            break;
                        case 3:
                            echo "eligió la opción 'Responsable de Vuelo'" . "\n";
                            cargarPasajero($vuelo, new ResponsableV(), $conn);
                            break;
                        default:
                            echo "Opción inválida" . "\n";
                            break;
                    }
                } else {
                    echo "No hay pasajes disponibles para el vuelo seleccionado" . "\n";
                }
            } else {
                echo "No se encontró el vuelo" . "\n";
            }
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
        case 4:
            echo "Eligió la opción 'Modificar Pasajero'" . "\n";
            // Modificar datos del pasajero en la base de datos.
            echo "Ingrese el 'DNI del Pasajero' que desea modificar: ";
            $dniPasajero = trim(fgets(STDIN));
            modificarPasajero($dniPasajero, $conn);
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
        case 5:
            echo "Eligió la opción 'Buscar Pasajero'" . "\n";
            // Buscar pasajero en la base de datos.
            echo "Ingrese el 'DNI del Pasajero' que desea buscar: ";
            $dniPasajero = trim(fgets(STDIN));
            buscarPasajero($dniPasajero, $conn);
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
        default:
            echo "Opción inválida" . "\n";
            menu($opcion, $listaViajes, $listaPasajeros, $conn);
            break;
    }
}

function agregarViaje($listaViajes, $conn)
{
    echo "Ingrese el 'Nro de Vuelo' del nuevo viaje: ";
    $nroVuelo = trim(fgets(STDIN));
    echo "Ingrese la 'Fecha de Salida' del nuevo viaje: ";
    $fechaSalida = trim(fgets(STDIN));
    echo "Ingrese la 'Ciudad de Origen' del nuevo viaje: ";
    $ciudadOrigen = trim(fgets(STDIN));
    echo "Ingrese la 'Ciudad de Destino' del nuevo viaje: ";
    $ciudadDestino = trim(fgets(STDIN));
    echo "Ingrese la 'Cantidad de Pasajes Disponibles' del nuevo viaje: ";
    $cantPasajesDisponibles = trim(fgets(STDIN));

    // Insertar nuevo viaje en la base de datos
    $sql = "INSERT INTO viajes (nro_vuelo, fecha_salida, ciudad_origen, ciudad_destino, cant_pasajes_disponibles) 
            VALUES ('$nroVuelo', '$fechaSalida', '$ciudadOrigen', '$ciudadDestino', '$cantPasajesDisponibles')";
    if ($conn->query($sql) === TRUE) {
        echo "El viaje ha sido cargado exitosamente" . "\n";
        return $listaViajes;
    } else {
        echo "Error: " . $sql . "\n" . $conn->error;
        return $listaViajes;
    }
}

function buscarViaje($nroVuelo, $conn)
{
    // Buscar el viaje en la base de datos
    $sql = "SELECT * FROM viajes WHERE nro_vuelo = '$nroVuelo'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $viaje = new Viaje($row['nro_vuelo'], $row['fecha_salida'], $row['ciudad_origen'], $row['ciudad_destino'], $row['cant_pasajes_disponibles']);

        // Obtener los pasajeros asociados al vuelo
        $sql = "SELECT * FROM pasajeros WHERE nro_vuelo = '$nroVuelo'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dni = $row['dni'];
                $nombre = $row['nombre'];
                $apellido = $row['apellido'];
                $edad = $row['edad'];
                $pasajero = new Pasajero($dni, $nombre, $apellido, $edad);
                $viaje->agregarPasajero($pasajero);
            }
        }

        return $viaje;
    } else {
        return null;
    }
}

function cargarPasajero($vuelo, $pasajero, $conn)
{
    echo "Ingrese el 'DNI' del pasajero: ";
    $dni = trim(fgets(STDIN));
    echo "Ingrese el 'Nombre' del pasajero: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el 'Apellido' del pasajero: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese la 'Edad' del pasajero: ";
    $edad = trim(fgets(STDIN));

    // Insertar nuevo pasajero en la base de datos
    $sql = "INSERT INTO pasajeros (dni, nombre, apellido, edad, nro_vuelo) 
            VALUES ('$dni', '$nombre', '$apellido', '$edad', '".$vuelo->getNroVuelo()."')";
    if ($conn->query($sql) === TRUE) {
        echo "El pasajero ha sido cargado exitosamente" . "\n";
        // Actualizar la cantidad de pasajes disponibles del vuelo
        $vuelo->agregarPasajero($pasajero);
        $vuelo->actualizarCantPasajes($conn);
    } else {
        echo "Error: " . $sql . "\n" . $conn->error;
    }
}

function modificarPasajero($dniPasajero, $conn)
{
    echo "Ingrese el nuevo 'Nombre' del pasajero: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese el nuevo 'Apellido' del pasajero: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingrese la nueva 'Edad' del pasajero: ";
    $edad = trim(fgets(STDIN));

    // Modificar los datos del pasajero en la base de datos
    $sql = "UPDATE pasajeros SET nombre = '$nombre', apellido = '$apellido', edad = '$edad' WHERE dni = '$dniPasajero'";
    if ($conn->query($sql) === TRUE) {
        echo "Los datos del pasajero han sido modificados exitosamente" . "\n";
    } else {
        echo "Error: " . $sql . "\n" . $conn->error;
    }
}

function buscarPasajero($dniPasajero, $conn)
{
    // Buscar el pasajero en la base de datos
    $sql = "SELECT * FROM pasajeros WHERE dni = '$dniPasajero'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pasajero = new Pasajero($row['dni'], $row['nombre'], $row['apellido'], $row['edad']);
        echo "Se encontró al pasajero:" . "\n";
        echo $pasajero;
    } else {
        echo "No se encontró al pasajero" . "\n";
    }
}

?>
