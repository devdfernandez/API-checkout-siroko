<?php

namespace App\Pedido\Dominio;

class Pedido
{
    /**
     * @param LineaPedido[] $lineas
     */
    public function __construct(
        private PedidoId $id,
        private array $lineas
    ) {
        if (empty($lineas)) {
            throw new \InvalidArgumentException('Un pedido debe tener al menos una línea.');
        }
    }

    public function id(): PedidoId
    {
        return $this->id;
    }

    /**
     * @return LineaPedido[]
     */
    public function lineas(): array
    {
        return $this->lineas;
    }

    public function total(): float
    {
        return array_reduce(
            $this->lineas,
            fn(float $carry, LineaPedido $linea) => $carry + $linea->subtotal(),
            0.0
        );
    }
}
