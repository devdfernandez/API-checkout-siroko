<?php

namespace App\Pedido\Infraestructura\Controlador;

use App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultarPedidoController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/pedidos/{pedidoId}', name: 'consultar_pedido', methods: ['GET'])]
    public function __invoke(string $pedidoId): JsonResponse
    {
        $pedido = $this->entityManager->getRepository(Pedido::class)->find($pedidoId);

        if (!$pedido) {
            return new JsonResponse(['error' => 'Pedido no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $pedido->getId(),
            'total' => $pedido->getTotal(),
            'lineas' => $pedido->getLineas(),
        ]);
    }
}
