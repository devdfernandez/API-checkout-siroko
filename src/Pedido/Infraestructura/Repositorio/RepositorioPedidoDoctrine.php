<?php

namespace App\Pedido\Infraestructura\Repositorio;

use App\Pedido\Dominio\Pedido as PedidoDominio;
use App\Pedido\Dominio\RepositorioPedido;
use App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido as PedidoDoctrine;
use Doctrine\ORM\EntityManagerInterface;

class RepositorioPedidoDoctrine implements RepositorioPedido
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function guardar(PedidoDominio $pedido): void
    {
        $doctrinePedido = new PedidoDoctrine(
            $pedido->id()->valor(),
            array_map(fn($linea) => [
                'productoId' => $linea->productoId(),
                'cantidad' => $linea->cantidad(),
                'precio' => $linea->precio(),
            ], $pedido->lineas()),
            $pedido->total()
        );

        $this->entityManager->persist($doctrinePedido);
        $this->entityManager->flush();
    }
}
