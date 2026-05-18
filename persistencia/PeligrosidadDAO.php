<?php

class PeligrosidadDAO {
    public function consultarTodos() {
        return "SELECT idPeligrosidad, Nivel FROM Peligrosidad ORDER BY idPeligrosidad ASC";
    }
}
?>