<?php

namespace App\Carrito\Dominio;

interface RepositorioCarritoInterface
{
    public function guardar(Carrito $carrito): void;

    public function buscar(CarritoId $id): ?Carrito;
}

?>