<?php
class ResponsableV
{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;

    public function __construct()
    {
        $this->nombre = "";
        $this->apellido = "";
        $this->numEmpleado = 0;
        $this->numLicencia = 0;
    }

    public function __toString()
    {
        return "\n" . "Responsable: " . $this->apellido . ", " . $this->nombre . "\n" . "Numero de empleado: " . $this->numEmpleado . "Numero de licencia: " . $this->numLicencia . "\n";
    }

    /*
    public function cargarResponsable($nombre, $apellido, $numEmpleado, $numLicencia){		
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumEmpleado($numEmpleado);
        $this->setNumLicencia($numLicencia);
    }
    */
    public function getNumEmpleado()
    {
        return $this->numEmpleado;
    }

    public function getNumLicencia()
    {
        return $this->numLicencia;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setNumEmpleado($numEmpleado)
    {
        $this->numEmpleado = $numEmpleado;
    }

    public function setNumLicencia($numLicencia)
    {
        $this->numLicencia = $numLicencia;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function cargarResponsable()
    {
        echo "Ingrese los datos del responsable del vuelo: " . "\n";

        echo "Numero de licencia" . "\n";
        $numLicencia = fgets(STDIN);

        echo "Nombre" . "\n";
        $nombre = trim(fgets(STDIN));

        echo "Apellido: " . "\n";
        $apellido = trim(fgets(STDIN));
        /*
        echo "numEmpleado: " . "\n";
        $numEmpleado = fgets(STDIN);
        */
        //conectarme a la bd para insertar el registro.
        $conx = new BaseDatos();
        $resp = $conx->iniciar();
        if ($resp == 1) {
            $sql = $conx->insertarResponsable($numLicencia, $nombre, $apellido);
            $respSql = $conx->Ejecutar($sql);
            if ($respSql == 1) {
                echo "Responsable cargado con éxito";
            } else {
                echo "error insertando responsable";
            }
        } else {
            echo "error conectando a la bd";
        }
    }
}

?>