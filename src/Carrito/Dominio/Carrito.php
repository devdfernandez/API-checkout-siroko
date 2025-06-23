<?php

namespace App\Carrito\Dominio;

class Carrito
{
    private array $items = [];

    public function __construct(private CarritoId $id, array $items = [])
    {
        $this->items = $items;
    }

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
        if ($item->cantidad() <= 0)
            throw new \InvalidArgumentException('La cantidad debe ser mayor que cero.');

        foreach ($this->items as $key => $existente) {
            if ($existente->productoId()->valor() === $item->productoId()->valor()) {
                $this->actualizarItem($key, $item, $existente);
                return;
            }
        }

        $this->items[] = $item;
    }

    private function actualizarItem(int $key, ItemCarrito $item, ItemCarrito $existente): void
    {
        $this->items[$key] = new ItemCarrito(
            $item->productoId(),
            $existente->cantidad() + $item->cantidad(),
            $item->precio()
        );
    }

    public function eliminarItem(ProductoId $productoId): void
    {
        $this->items = array_filter($this->items, fn($item) => $item->productoId()->valor() !== $productoId->valor());
    }


    public function total(): float
    {
        return array_reduce($this->items, fn($carry, $item) => $carry + $item->total(), 0);
    }
}

?>