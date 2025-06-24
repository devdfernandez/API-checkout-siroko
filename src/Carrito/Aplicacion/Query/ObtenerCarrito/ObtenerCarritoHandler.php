<?php
namespace App\Carrito\Aplicacion\Query\ObtenerCarrito;

use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Dominio\CarritoId;

class ObtenerCarritoHandler
{
    private RepositorioCarrito $repositorioCarrito;

    public function __construct(RepositorioCarrito $repositorioCarrito)
    {
        $this->repositorioCarrito = $repositorioCarrito;
    }

    public function handle(ObtenerCarritoQuery $query)
    {
        return $this->repositorioCarrito->buscarPorId(new CarritoId($query->carritoId));
    }
}
