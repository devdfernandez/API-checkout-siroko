<?php

namespace App\Carrito\Aplicacion\Command\ActualizarProducto;

use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;

final class ActualizarProductoHandler
{
    public function __construct(
        private RepositorioCarrito $repositorioCarrito
    ) {}

    public function __invoke(ActualizarProductoCommand $command): void
    {
        $carritoId = new CarritoId($command->carritoId);
        $productoId = new ProductoId($command->productoId);

        $carrito = $this->repositorioCarrito->buscarPorId($carritoId);

        if (!$carrito) {
            throw new \RuntimeException('Carrito no encontrado');
        }

        $carrito->actualizarCantidad($productoId, $command->nuevaCantidad);

        $this->repositorioCarrito->guardar($carrito);
    }
}