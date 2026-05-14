<?php
class EstadisticaDAO {
    public function promedioPreciosPorTamaño() {
        return "SELECT tam.Tipo, ROUND(AVG(t.PrecioHora)) 
                FROM tarifa t 
                INNER JOIN tamaño tam ON t.Tamaño_idTamaño = tam.idTamaño
                WHERE t.Activa = 1
                GROUP BY tam.Tipo";
    }

    public function cantidadPerrosPorTamaño() {
        return "SELECT tam.Tipo, COUNT(*) 
                FROM perro p 
                INNER JOIN raza r ON p.Raza_idRaza = r.idRaza
                INNER JOIN tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
                GROUP BY tam.Tipo";
    }
}
