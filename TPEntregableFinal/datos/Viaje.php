<?php

class Viaje{
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
    
    public function setResponsable($responsable) {
        $this->responsable = $responsable;
    }
    
    public function getResponsable() {
        return $this->responsable;
    }
    
    //
    //GETTERS
    // 

    public function __toString() {
        $output = "ID del viaje: {$this->id}\n";
        $output .= "Destino: {$this->destino}\n";
        $output .= "Cantidad máxima de pasajeros: {$this->cantMax}\n";
        $output .= "Cantidad de pasajeros: {$this->cantPasajeros}\n";
        $output .= "Responsable: {$this->responsable}\n";
        $output .= "Costo del viaje: {$this->costoViaje}\n";
        $output .= "Costos abonados: {$this->costosAbonados}\n";
        $output .= "Pasajeros:\n";
    
        foreach ($this->pasajeros as $pasajero) {
            $output .=  $pasajero->getApellido(). ", ".$pasajero->getNombre()."\n";
        }
    
        return $output;
    }
    // Obtiene el valor de cantMaxPasajeros
      
    public function getCantMaxPasajeros(){
        return $this->cantMax;
    }
    public function getCantPasajeros(){
        return $this->cantPasajeros;
    }
    //Obtiene el valor de destino
    public function getDestino(){
        return $this->destino;
    }

     //Obtiene el valor de idViaje
    public function getIdViaje() {
        return $this->id;
    }


    public function cuentaCantPasajeros($cantPasajeros){
        $this->cantPasajeros += $cantPasajeros;
    }

    public function getPasajeros() {
        return $this->pasajeros;
    }

    //
    //SETTERS
    //


    //Establece el valor de id
   
    public function setIdViaje($id){
        $this->id = $id;
    }

    //Establece el destino
    public function setDestino($destino){
        $this->destino = $destino;
    }

     //Establece el valor de cantMaxPasajeros
    public function setCantMaxPasajeros($cantMax){
        $this->cantMax = $cantMax;
    }
    public function getCostoViaje() {
        return $this->costoViaje;
    }
    
    public function setCostoViaje($costo) {
        $this->costoViaje = $costo;
    }
    public function getCostosAbonados() {
        return $this->costosAbonados;
    }
    
    public function setCostosAbonados($costos) {
        $this->costosAbonados = $costos;
    }

    public function venderPasaje($objPasajero) {
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
    //inicializar Instancia de clase.-
    // @param int $id
 	// @param string $destino
	// @param int $cantMax
    
    public function __construct($id, $destino, $cantMax,$costoViaje) {
        $this->id = $id;
        $this->destino = $destino;
        $this->cantMax = $cantMax;
        $this->cantPasajeros = 0;
        $this->pasajeros = array();
        $this->responsable = "";
        $this->costoViaje = $costoViaje;
        $this->costosAbonados = 0;
    }

    /*
	 // declara variables tipo parametro-.
	 // @param int $id
 	 // @param string $destino
	 // @param int $cantMax
	
    
    public function cargarViaje($id, $destino, $cantMax) {
            $this->setIdViaje($id);
            $this->setDestino($destino);
            $this->setCantMaxPasajeros($cantMax);
          
    }
    */
    public function cargarPasajeroVuelo($persona) {
        array_push($this->pasajeros, $persona);
      }
      public function hayPasajesDisponible() {
        return $this->cantPasajeros < $this->cantMax;
    }
}
?>