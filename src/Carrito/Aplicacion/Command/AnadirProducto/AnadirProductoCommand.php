<?php
namespace App\Carrito\Aplicacion\Command\AnadirProducto;

class AnadirProductoCommand
{
    public string $carritoId;
    public string $productoId;
    public int $cantidad;
    public float $precio;

    public function __construct(string $carritoId, string $productoId, int $cantidad, float $precio)
    {
        $this->carritoId = $carritoId;
        $this->productoId = $productoId;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }
}
