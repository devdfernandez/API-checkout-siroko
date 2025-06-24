<?php
namespace App\Carrito\Aplicacion\Command\CrearCarrito;

use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\RepositorioCarrito;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CrearCarritoHandler
{
    public function __construct(private readonly RepositorioCarrito $repositorioCarrito) {}

    public function __invoke(CrearCarritoCommand $command): CarritoId
    {
        $carritoId = new CarritoId();
        $carrito = new Carrito($carritoId);
        $this->repositorioCarrito->guardar($carrito);
        return $carritoId;
    }
}
