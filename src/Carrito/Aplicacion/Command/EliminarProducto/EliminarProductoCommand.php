<?php

namespace App\Carrito\Aplicacion\Command\EliminarProducto;

final class EliminarProductoCommand
{
    public function __construct(
        public readonly string $carritoId,
        public readonly string $productoId
    ) {}
}

?>
