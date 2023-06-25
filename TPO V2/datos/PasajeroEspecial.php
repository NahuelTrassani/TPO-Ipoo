<?php

class PasajeroNecesidadesEspeciales extends Pasajero {
    private $sillaRuedas;
    private $asistenciaEmbarque;
    private $comidaEspecial;
    
    public function __construct($dni, $nombre, $apellido, $telefono, $nroVuelo, $numeroAsiento, $numeroTicket, $sillaRuedas, $asistenciaEmbarque, $comidaEspecial) {
        parent::__construct($dni, $nombre, $apellido, $telefono, $nroVuelo, $numeroAsiento, $numeroTicket);
        $this->sillaRuedas = $sillaRuedas;
        $this->asistenciaEmbarque = $asistenciaEmbarque;
        $this->comidaEspecial = $comidaEspecial;
    }
    
    
    public function darPorcentajeIncremento() {
        $porcentajeIncremento = 0;
        
        if ($this->sillaRuedas && $this->asistenciaEmbarque && $this->comidaEspecial) {
            $porcentajeIncremento = 30;
        } elseif ($this->sillaRuedas || $this->asistenciaEmbarque || $this->comidaEspecial) {
            $porcentajeIncremento = 15;
        }
        
        return $porcentajeIncremento;
    }
}

?>