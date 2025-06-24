<?php

namespace App\Pedido\Infraestructura\Controlador;

use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutCommand;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProcesarCheckoutController extends AbstractController
{
    public function __construct(
        private ProcesarCheckoutHandlerInterface $handler
    ) {}
    #[Route('/checkout', name: 'procesar_checkout', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $carritoId = $data['carritoId'] ?? null;

            if (!$carritoId) {
                return new JsonResponse(['error' => 'carritoId requerido'], JsonResponse::HTTP_BAD_REQUEST);
            }

            $command = new ProcesarCheckoutCommand($carritoId);
            
            $pedidoId = ($this->handler)($command);
            return new JsonResponse(['id' => $pedidoId->valor()], JsonResponse::HTTP_CREATED);

        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Error inesperado'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
