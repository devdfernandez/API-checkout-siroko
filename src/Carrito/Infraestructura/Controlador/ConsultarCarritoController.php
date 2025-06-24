<?php

namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConsultarCarritoController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/carrito/{carritoId}', name: 'consultar_carrito', methods: ['GET'])]
    public function __invoke(string $carritoId): JsonResponse
    {
        $carrito = $this->entityManager->getRepository(Carrito::class)->find($carritoId);

        if (!$carrito) {
            return new JsonResponse(['error' => 'Carrito no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $items = array_map(fn($item) => [
            'productoId' => $item->getProductoId(),
            'cantidad' => $item->getCantidad(),
            'precio_unitario' => $item->getPrecioUnitario(),
        ], $carrito->getItems());

        return new JsonResponse(['id' => $carrito->getId(), 'items' => $items]);
    }
}
