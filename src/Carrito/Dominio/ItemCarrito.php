<?php 
namespace App\Carrito\Dominio;

class ItemCarrito
{
    public function __construct(
        private ProductoId $productoId,
        private int $cantidad,
        private float $precio
    ) {}

    public function productoId(): ProductoId
    {
        return $this->productoId;
    }

    public function cantidad(): int
    {
        return $this->cantidad;
    }

    public function actualizarCantidad(int $cantidad): void
    {
        if ($cantidad < 1) {
            throw new \InvalidArgumentException('Cantidad inválida.');
        }

        $this->cantidad = $cantidad;
    }

    public function precio(): float
    {
        return $this->precio;
    }

    public function total(): float
    {
        return $this->cantidad * $this->precio;
    }
}

?>