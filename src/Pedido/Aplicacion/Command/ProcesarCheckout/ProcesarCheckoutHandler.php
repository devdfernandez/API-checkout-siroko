<?php

namespace App\Pedido\Aplicacion\Command\ProcesarCheckout;

use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\RepositorioCarrito;
use App\Pedido\Dominio\Pedido;
use App\Pedido\Dominio\PedidoId;
use App\Pedido\Dominio\LineaPedido;
use App\Pedido\Dominio\RepositorioPedido;
use App\Inventario\Dominio\GestorStock;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandlerInterface;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcesarCheckoutHandler implements ProcesarCheckoutHandlerInterface
{
    public function __construct(
        private RepositorioCarrito $repositorioCarrito,
        private RepositorioPedido $repositorioPedido,
        private GestorStock $gestorStock
    ) {}

    public function __invoke(ProcesarCheckoutCommand $command): PedidoId
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
            $linea = new LineaPedido(
                productoId: $item->productoId()->valor(),
                cantidad: $item->cantidad(),
                precio: $item->precio()
            );
            
            $this->gestorStock->reservarStock($linea);

            $lineas[] = $linea;
        }

        $pedidoId = PedidoId::generar();
        $pedido = new Pedido($pedidoId, $lineas);

        $this->repositorioPedido->guardar($pedido);

        return $pedidoId;
    }
}
