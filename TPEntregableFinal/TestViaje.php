<?php

include "datos/Viaje.php";
include "datos/Pasajero.php";
include "datos/ResponsableV.php";
include "datos/PasajeroVip.php";
include "datos/PasajeroEspecial.php";
include "datos/BaseDatos.php";
include "datos/Empresa.php";

$listaViajes = array();
$listaPasajeros = array();

menu();

function menu()
{
    echo "¡Bienvenido/a!" . "\n";
    echo "Seleccione una opción para continuar: " . "\n" .
        "║  1  ║ Cargar Empresa                      " . "\n" .
        "║  2  ║ Modificar Empresa                      " . "\n" .
        "║  3  ║ Eliminar Empresa                      " . "\n" .
        "║  4  ║ Cargar Viaje                        " . "\n" .
        "║  5  ║ Modificar Viaje                        " . "\n" .
        "║  6  ║ Eliminar Viaje                        " . "\n" .
        "║  7  ║ Buscar Viaje                        " . "\n" .
        "║  8  ║ Cargar Chofer/Piloto                " . "\n" .
        "║  9  ║ Cargar Pasajero                     " . "\n" .
        "║  10  ║ Modificar Pasajero                  " . "\n" .
        "║  11  ║ Buscar Pasajero                     " . "\n" .
        "║  0  ║ Salir                               " . "\n";
    $opcion = fgets(STDIN);


    switch ($opcion) {
        case 0:
            ////echo "eligió la opción SALIR";
            exit; //finalizar ejecución.
        case 1:
            echo "eligió la opción 'Cargar Empresa'" . "\n";
            $empresa = new Empresa();
            $empresa->agregarEmpresa();
            menu();
            break;
        case 2:
            echo "eligió la opción 'Modificar Empresa'" . "\n";
            $empresa = new Empresa();
            $empresa->modificarEmpresa();
            menu();
            break;
        case 3:
            echo "eligió la opción 'Eliminar Empresa'" . "\n";
            $empresa = new Empresa();
            $empresa->eliminarEmpresa();
            menu();
            break;
        case 4:
            echo "eligió la opción 'Cargar Viaje'" . "\n";

            echo "Debe indicar el id de la empresa y del responsable del viaje" . "\n";
            echo "TENGA EN CUENTA QUE EL ID DEBE EXISTIR EN LA TABLA CORRESPONDIENTE PORQUE NO ESTOY VALIANDO QUE HAYA ERROR." . "\n";
            //cargar viaje
            $viaje = new Viaje();

            //antes debo recuperar la empresa y el responsable.
            echo "Empresa" . "\n";
            $idEmpresa = trim(fgets(STDIN));

            echo "Responsable" . "\n";
            $idResponsable = trim(fgets(STDIN));

            $viaje->agregarViaje($idEmpresa, $idResponsable);
            //$listaViajes = agregarViaje($listaViajes);
            menu();
            break;
        case 5:
            echo "eligió la opción 'Modificar Viaje'" . "\n";

            menu();
            break;
        case 6:
            echo "eligió la opción 'Eliminar Viaje'" . "\n";

            menu();
            break;
        case 7:
            echo "Eligió la opción 'Buscar Viaje'" . "\n";
            $viaje = new Viaje();
            $viaje->buscarViaje();
            menu();
            break;
        case 8:
            echo "eligió la opción 'Cargar Responsable Viaje'" . "\n";
            $chofer = new ResponsableV();
            $chofer->cargarResponsable();
            menu();
            break;
        case 9:
            echo "eligió la opción 'Cargar Pasajero'" . "\n";
            $pasajero = new Pasajero();
            $pasajero->agregarPasajero();
            menu();
            break;
        case 10:
            echo "eligió la opción 'Modificar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea modificar" . "\n";
            $documento = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            $respSql = $pasajero->buscarPasajero($documento);
            $dniPasj = $respSql['pdocumento'];
            $pasajero->modificarPasajero($dniPasj);
            menu();
            break;
        case 11:
            echo "eligió la opción 'Buscar Pasajero'" . "\n";
            echo "Ingrese el dni del pasajero que desea buscar" . "\n";
            $documento = trim(fgets(STDIN));
            $pasajero = new Pasajero();
            $pasajero->buscarPasajero($documento);
            menu();
            break;
        default:
            echo "Debe elegir una opción valida";
    }
}
?>