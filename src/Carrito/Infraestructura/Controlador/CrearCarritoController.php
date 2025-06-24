<?php

namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Aplicacion\Command\CrearCarrito\CrearCarritoCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/carrito', name: 'crear_carrito', methods: ['POST'])]
class CrearCarritoController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private EntityManagerInterface $entityManager // inyecta aquí
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $envelope = $this->commandBus->dispatch(new CrearCarritoCommand());

        /** @var HandledStamp $stamp */
        $stamp = $envelope->last(HandledStamp::class);
        $carritoId = $stamp->getResult();

        $this->entityManager->clear();

        return new JsonResponse(['id' => $carritoId->valor()], Response::HTTP_CREATED);
    }
}
