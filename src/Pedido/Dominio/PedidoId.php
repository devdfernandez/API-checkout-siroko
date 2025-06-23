<?php

namespace App\Pedido\Dominio;

use Ramsey\Uuid\Uuid;

class PedidoId
{
    private string $valor;

    public function __construct(string $valor)
    {
        if (!Uuid::isValid($valor)) {
            throw new \InvalidArgumentException("PedidoId inválido: $valor");
        }

        $this->valor = $valor;
    }

    public static function generar(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(PedidoId $otro): bool
    {
        return $this->valor === $otro->valor();
    }

    public function __toString(): string
    {
        return $this->valor;
    }
}

?>