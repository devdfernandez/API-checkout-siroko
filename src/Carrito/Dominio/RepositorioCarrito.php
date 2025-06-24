<?php

namespace App\Carrito\Dominio;

interface RepositorioCarrito
{
    public function guardar(Carrito $carrito): void;

    public function buscarPorId(CarritoId $id): ?Carrito;
}
