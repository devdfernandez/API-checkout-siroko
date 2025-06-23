<?php

namespace App\Pedido\Aplicacion\Command\ProcesarCheckout;

use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\RepositorioCarrito;
use App\Pedido\Dominio\Pedido;
use App\Pedido\Dominio\PedidoId;
use App\Pedido\Dominio\LineaPedido;
use App\Pedido\Dominio\RepositorioPedido;

class ProcesarCheckoutHandler implements ProcesarCheckoutHandlerInterface
{
    public function __construct(
        private RepositorioCarrito $repositorioCarrito,
        private RepositorioPedido $repositorioPedido
    ) {}

    public function __invoke(ProcesarCheckoutCommand $command): void
    {
        $carritoId = new CarritoId($command->carritoId);
        $carrito = $this->repositorioCarrito->buscarPorId($carritoId);

        if (!$carrito) {
            throw new \RuntimeException('Carrito no encontrado');
        }

        if (empty($carrito->items())) {
            throw new \RuntimeException('El carrito está vacío');
        }

        $lineas = [];
        foreach ($carrito->items() as $item) {
            $lineas[] = new LineaPedido(
                productoId: $item->productoId()->valor(),
                cantidad: $item->cantidad(),
                precio: $item->precio()
            );
        }

        $pedido = new Pedido(PedidoId::generar(), $lineas);

        $this->repositorioPedido->guardar($pedido);
    }
}
