<?php

class PasajeroVIP extends Pasajero {
    private $numeroViajeroFrecuente;
    private $cantidadMillas;
    
    public function __construct($dni, $nombre, $apellido, $telefono, $nroVuelo, $numeroAsiento, $numeroTicket, $numeroViajeroFrecuente, $cantidadMillas) {
        parent::__construct($dni, $nombre, $apellido, $telefono, $nroVuelo, $numeroAsiento, $numeroTicket);
        $this->numeroViajeroFrecuente = $numeroViajeroFrecuente;
        $this->cantidadMillas = $cantidadMillas;
    }
    
    
    public function darPorcentajeIncremento() {
        $porcentajeIncremento = 35; // Porcentaje de incremento para pasajeros VIP
        
        if ($this->cantidadMillas > 300) {
            $porcentajeIncremento += 30;
        }
        
        return $porcentajeIncremento;
    }
}

?>
