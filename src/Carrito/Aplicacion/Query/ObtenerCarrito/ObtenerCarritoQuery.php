<?php
namespace App\Carrito\Aplicacion\Query\ObtenerCarrito;

class ObtenerCarritoQuery
{
    public string $carritoId;

    public function __construct(string $carritoId)
    {
        $this->carritoId = $carritoId;
    }
}
