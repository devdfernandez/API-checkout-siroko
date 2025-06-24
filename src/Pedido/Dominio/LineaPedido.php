<?php

namespace App\Pedido\Dominio;

class LineaPedido
{
    public function __construct(
        private string $productoId,
        private int $cantidad,
        private float $precio
    ) {
        if ($cantidad < 1) {
            throw new \InvalidArgumentException('La cantidad debe ser mayor a 0');
        }
        if ($precio < 0) {
            throw new \InvalidArgumentException('El precio no puede ser negativo');
        }
    }

    public function productoId(): string
    {
        return $this->productoId;
    }

    public function cantidad(): int
    {
        return $this->cantidad;
    }

    public function precio(): float
    {
        return $this->precio;
    }

    public function subtotal(): float
    {
        return $this->cantidad * $this->precio;
    }
}