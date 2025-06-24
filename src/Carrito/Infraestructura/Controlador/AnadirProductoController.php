<?php

namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Aplicacion\Command\AnadirProducto\AnadirProductoCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AnadirProductoController
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {}

    #[Route('/carrito/{carritoId}/producto', name: 'carrito_anadir_producto', methods: ['POST'])]
    public function __invoke(Request $request, string $carritoId): JsonResponse
    {
        $datos = json_decode($request->getContent(), true);

        if (!isset($datos['productoId'], $datos['cantidad'], $datos['precio'])) {
            return new JsonResponse(['error' => 'productoId, cantidad y precio son obligatorios'], 400);
        }

        $productoId = $datos['productoId'];  
        $cantidad = $datos['cantidad'];
        $precio = $datos['precio'];

        $command = new AnadirProductoCommand($carritoId, $productoId, $cantidad, $precio);

        $this->commandBus->dispatch($command);

        return new JsonResponse(['status' => 'Producto añadido al carrito'], 201);
    }
}
