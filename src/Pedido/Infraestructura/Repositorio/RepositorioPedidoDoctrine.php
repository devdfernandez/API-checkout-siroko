<?php

namespace App\Pedido\Infraestructura\Repositorio;

use App\Pedido\Dominio\Pedido;
use App\Pedido\Dominio\PedidoId;
use App\Pedido\Dominio\LineaPedido;
use App\Pedido\Dominio\RepositorioPedido;
use App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido as PedidoDoctrine;
use Doctrine\ORM\EntityManagerInterface;

class RepositorioPedidoDoctrine implements RepositorioPedido
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function guardar(Pedido $pedido): void
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

    public function buscarPorId(PedidoId $id): ?Pedido
    {
        /** @var PedidoDoctrine|null $pedidoEntity */
        $pedidoEntity = $this->entityManager
            ->getRepository(PedidoDoctrine::class)
            ->find($id->valor());

        if (!$pedidoEntity) {
            return null;
        }

        $lineas = array_map(
            fn(array $linea) => new LineaPedido(
                $linea['productoId'],
                $linea['cantidad'],
                $linea['precio']
            ),
            $pedidoEntity->getLineas()
        );

        return new Pedido($id, $lineas);
    }
}
