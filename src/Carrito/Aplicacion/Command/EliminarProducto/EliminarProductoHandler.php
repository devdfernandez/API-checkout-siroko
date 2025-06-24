<?php

namespace App\Carrito\Aplicacion\Command\EliminarProducto;

use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\RepositorioCarrito;

final class EliminarProductoHandler
{
    public function __construct(
        private RepositorioCarrito $repositorioCarrito
    ) {}

    public function __invoke(EliminarProductoCommand $command): void
    {
        $carritoId = new CarritoId($command->carritoId);
        $productoId = new ProductoId($command->productoId);

        $carrito = $this->repositorioCarrito->buscarPorId($carritoId);

        if (!$carrito) {
            throw new \RuntimeException('Carrito no encontrado');
        }

        $carrito->eliminarItem($productoId);

        $this->repositorioCarrito->guardar($carrito);
    }
}
