<?php

namespace App\Tests\Pedido\Infraestructura\Controlador;

use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutCommand;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandler;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandlerInterface;
use App\Pedido\Infraestructura\Controlador\ProcesarCheckoutController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProcesarCheckoutControllerTest extends TestCase
{
    public function testProcesarCheckoutExitoso(): void
    {
        $carritoId = 'c1';
        $request = new Request([], [], ['carritoId' => $carritoId]);
        
        /** @var ProcesarCheckoutHandlerInterface&\PHPUnit\Framework\MockObject\MockObject $handlerMock */
        $handlerMock = $this->createMock(ProcesarCheckoutHandlerInterface::class);
        $handlerMock->expects($this->once())
            ->method('__invoke')
            ->with($this->callback(function ($command) use ($carritoId) {
                return $command instanceof ProcesarCheckoutCommand
                    && $command->carritoId === $carritoId;
            }));

        $controller = new ProcesarCheckoutController($handlerMock);
        $response = $controller($request, $carritoId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['status' => 'Pedido generado correctamente']),
            $response->getContent()
        );
    }

    public function testProcesarCheckoutError(): void
    {
        $carritoId = 'c2';
        $request = new Request([], [], ['carritoId' => $carritoId]);

        /** @var ProcesarCheckoutHandlerInterface&\PHPUnit\Framework\MockObject\MockObject $handlerMock */
        $handlerMock = $this->createMock(ProcesarCheckoutHandlerInterface::class);
        $handlerMock->expects($this->once())
            ->method('__invoke')
            ->willThrowException(new \RuntimeException('Carrito no encontrado'));

        $controller = new ProcesarCheckoutController($handlerMock);
        $response = $controller($request, $carritoId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Carrito no encontrado']),
            $response->getContent()
        );
    }
}
