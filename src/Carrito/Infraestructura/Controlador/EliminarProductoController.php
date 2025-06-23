<?php

namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Aplicacion\Command\EliminarProducto\EliminarProductoCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class EliminarProductoController
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    #[Route('/api/carrito/{carritoId}/producto/{productoId}', name: 'carrito_eliminar_producto', methods: ['DELETE'])]
    public function __invoke(Request $request, string $carritoId, string $productoId): JsonResponse
    {
        $command = new EliminarProductoCommand(
            carritoId: $carritoId,
            productoId: $productoId
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(null, 204); // No Content
    }
}
