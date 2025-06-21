<?php

namespace App\Carrito\Dominio;

class Carrito
{
    private array $items = [];

    public function __construct(private CarritoId $id) {}

    public function id(): CarritoId
    {
        return $this->id;
    }

    public function items(): array
    {
        return $this->items;
    }

    public function anadirItem(ItemCarrito $item): void
    {
        foreach ($this->items as $key => $existente) {
            if ($existente->productoId()->valor() === $item->productoId()->valor()) {
                $this->items[$key] = new ItemCarrito(
                    $item->productoId(),
                    $existente->cantidad() + $item->cantidad(),
                    $item->precio()
                );
                return;
            }
        }

        $this->items[] = $item;
    }

    public function total(): float
    {
        return array_reduce($this->items, fn($carry, $item) => $carry + $item->total(), 0);
    }
}

?>