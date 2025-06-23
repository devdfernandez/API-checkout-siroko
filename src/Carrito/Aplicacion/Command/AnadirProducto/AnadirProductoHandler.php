<?php
namespace App\Carrito\Aplicacion\Command\AnadirProducto;

use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\ItemCarrito;

class AnadirProductoHandler
{
    private RepositorioCarrito $repositorioCarrito;

    public function __construct(RepositorioCarrito $repositorioCarrito)
    {
        $this->repositorioCarrito = $repositorioCarrito;
    }

    public function handle(AnadirProductoCommand $command): void
    {
        $carrito = $this->repositorioCarrito->buscarPorId(new CarritoId($command->carritoId));

        if (!$carrito) {
            throw new \RuntimeException('Carrito no encontrado');
        }

        $productoId = new ProductoId($command->productoId);
        $cantidad = $command->cantidad;
        $precio = $command->precio ?? 0;

        $item = new ItemCarrito($productoId, $cantidad, $precio);

        $carrito->anadirItem($item);

        $this->repositorioCarrito->guardar($carrito);
    }
}

?>