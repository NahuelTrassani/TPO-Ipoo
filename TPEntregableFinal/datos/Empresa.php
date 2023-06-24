<?php

class Empresa
{
    private $idempresa;
    private $enombre;
    private $edireccion;

    public function __construct()
    {
        $this->enombre = "";
        $this->edireccion = "";
    }

    public function getIdempresa()
    {
        return $this->idempresa;
    }

    public function getEnombre()
    {
        return $this->enombre;
    }

    public function setEnombre($enombre)
    {
        $this->enombre = $enombre;
    }

    public function getEdireccion()
    {
        return $this->edireccion;
    }

    public function setEdireccion($edireccion)
    {
        $this->edireccion = $edireccion;
    }

    public function agregarEmpresa()
    {
        echo "Nombre empresa" . "\n";
        $nomEmpresa = fgets(STDIN);

        echo "Dirección empresa" . "\n";
        $dirEmpresa = trim(fgets(STDIN));

        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->insertarEmpresa($nomEmpresa, $dirEmpresa);
            $respSql = $conx->Ejecutar($sql);
            if ($respSql == 1) {
                echo "Empresa cargada con éxito" . "\n";
            } else {
                echo "Error insertando Empresa" . "\n";
            }
        } else {
            echo "Error conectando a la bd" . "\n";
        }
    }

    public function modificarEmpresa()
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


    public function eliminarEmpresa()
    {
        echo "POR UNA CUESTION DE INTEGRIDAD REFERENCIAL PRIMERO VAS A TENER QUE BORRAR LOS VIAJES ASOCIADOS A LA EMPRESA QUE QUERES BORRAR". "\n";
        echo "Indique el nombre de la empresa que desea eliminar" . "\n";
        $nomEmpresa = trim(fgets(STDIN));
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->buscarEmpresa($nomEmpresa);
            $respSql = $conx->EjecutarConRetorno($sql);
            if ($respSql) {
                $sql2 = $conx->eliminarEmpresa($respSql['idempresa']);
                $borraIsOk = $conx->Ejecutar($sql2);
                if ($borraIsOk == 1) {
                    echo "Se borró la empresa de manera exitosa". "\n";
                } else {
                    echo "Falló el borrado de la empresa". "\n";
                }

            }
        }
    }

}

?>