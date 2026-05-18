<?php
class EstadisticaDAO {
    public function promedioPreciosPorPeligrosidad() {
        return "SELECT p.Nivel, ROUND(AVG(t.PrecioHora)) 
                FROM Tarifa t 
                INNER JOIN Peligrosidad p ON t.Peligrosidad_idPeligrosidad = p.idPeligrosidad
                WHERE t.Activa = 1
                GROUP BY p.Nivel";
    }

    public function cantidadPerrosPorTamaño() {
        return "SELECT tam.Tamaño, COUNT(*) 
                FROM Perro p 
                INNER JOIN Raza r ON p.Raza_idRaza = r.idRaza
                INNER JOIN Tamaño tam ON r.Tamaño_idTamaño = tam.idTamaño
                GROUP BY tam.Tamaño";
    }
}
