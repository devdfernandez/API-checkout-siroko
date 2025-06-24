<?php

namespace App\Carrito\Aplicacion\Command\ActualizarProducto;

use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;

final class ActualizarProductoCommand
{
    public function __construct(
        public readonly string $carritoId,
        public readonly string $productoId,
        public readonly int $nuevaCantidad
    ) {}
}