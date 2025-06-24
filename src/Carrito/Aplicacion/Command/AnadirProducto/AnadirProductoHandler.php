<?php
namespace App\Carrito\Aplicacion\Command\AnadirProducto;

use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\ItemCarrito;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AnadirProductoHandler
{
    private RepositorioCarrito $repositorioCarrito;

    public function __construct(RepositorioCarrito $repositorioCarrito)
    {
        $this->repositorioCarrito = $repositorioCarrito;
    }

    public function __invoke(AnadirProductoCommand $command): void
    {
        $carritoId = new CarritoId($command->carritoId);
        $carrito = $this->repositorioCarrito->buscarPorId($carritoId);


        if (!$carrito) {
            // TEMPORAL para debug
            throw new \RuntimeException("Carrito no encontrado: " . $carritoId->valor());
        }

        $productoId = new ProductoId($command->productoId);
        $cantidad = $command->cantidad;
        
        if (!is_numeric($command->precio)) {
            throw new \InvalidArgumentException('Precio no válido');
        }
        $precio = (float) $command->precio;

        $item = new ItemCarrito($productoId, $cantidad, $precio);

        $carrito->anadirItem($item);

        $this->repositorioCarrito->guardar($carrito);
    }
}
