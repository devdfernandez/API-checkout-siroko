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

        // Asumimos que tienes el precio disponible en el comando, si no, tendrás que obtenerlo de otra forma
        $productoId = new ProductoId($command->productoId);
        $cantidad = $command->cantidad;
        $precio = $command->precio ?? 0; // Cambia esto si tienes otra forma de obtener el precio

        $item = new ItemCarrito($productoId, $cantidad, $precio);

        $carrito->anadirItem($item);

        $this->repositorioCarrito->guardar($carrito);
    }
}

?>