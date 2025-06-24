<?php

namespace App\Pedido\Infraestructura\Controlador;

use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutCommand;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

class ProcesarCheckoutController extends AbstractController
{
    public function __construct(
        private ProcesarCheckoutHandlerInterface $handler
    ) {}
    #[Route('/checkout/{carritoId}', name: 'procesar_checkout', methods: ['POST'])]
    public function __invoke(Request $request, string $carritoId): JsonResponse
    {
        try {
            $command = new ProcesarCheckoutCommand($carritoId);
            ($this->handler)($command);

            return new JsonResponse(['status' => 'Pedido generado correctamente'], JsonResponse::HTTP_CREATED);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Error inesperado'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
