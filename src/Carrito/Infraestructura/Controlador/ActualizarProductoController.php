<?php

namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Aplicacion\Command\ActualizarProducto\ActualizarProductoCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class ActualizarProductoController
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    #[Route('/api/carrito/{carritoId}/producto/{productoId}', name: 'carrito_actualizar_producto', methods: ['PUT'])]
    public function __invoke(Request $request, string $carritoId, string $productoId): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['cantidad']) || !is_int($data['cantidad'])) {
            return new JsonResponse(['error' => 'Cantidad inválida'], 400);
        }

        $command = new ActualizarProductoCommand(
            carritoId: $carritoId,
            productoId: $productoId,
            nuevaCantidad: $data['cantidad']
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(null, 204); // No Content
    }
}
?>