<?php
namespace App\Carrito\Aplicacion\Command\AnadirProducto;

class AnadirProductoCommand
{
    public string $carritoId;
    public string $productoId;
    public int $cantidad;

    public function __construct(string $carritoId, string $productoId, int $cantidad)
    {
        $this->carritoId = $carritoId;
        $this->productoId = $productoId;
        $this->cantidad = $cantidad;
    }
}
