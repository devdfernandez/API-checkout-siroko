<?php

namespace App\Pedido\Infraestructura\Persistencia\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'pedidos')]
class Pedido
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'json')]
    private array $lineas;

    #[ORM\Column(type: 'float')]
    private float $total;

    public function __construct(string $id, array $lineas, float $total)
    {
        $this->id = $id;
        $this->lineas = $lineas;
        $this->total = $total;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLineas(): array
    {
        return $this->lineas;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}
