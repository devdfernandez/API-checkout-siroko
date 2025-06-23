<?php

namespace App\Tests\Carrito\Aplicacion\Command\AnadirProducto;

use App\Carrito\Aplicacion\Command\AnadirProducto\AnadirProductoCommand;
use App\Carrito\Aplicacion\Command\AnadirProducto\AnadirProductoHandler;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\RepositorioCarrito;
use PHPUnit\Framework\TestCase;

class AnadirProductoHandlerTest extends TestCase
{
    public function testAnadirProductoExistente(): void
    {
        $carritoId = new CarritoId('11111111-1111-1111-1111-111111111111');
        $productoId = new ProductoId('22222222-2222-2222-2222-222222222222');
        $carrito = new Carrito($carritoId, []);

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->with($carritoId)->willReturn($carrito);
        $repositorio->expects($this->once())->method('guardar')->with($carrito);

        $handler = new AnadirProductoHandler($repositorio);

        $command = new AnadirProductoCommand(
            carritoId: $carritoId->valor(),
            productoId: $productoId->valor(),
            cantidad: 2
        );

        $handler->handle($command);

        $this->assertCount(1, $carrito->items());
        $this->assertEquals(2, $carrito->items()[0]->cantidad());
    }

    public function testAnadirProductoSinCarrito(): void
    {
        $carritoId = new CarritoId('33333333-3333-3333-3333-333333333333');
        $productoId = new ProductoId('44444444-4444-4444-4444-444444444444');

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn(null);

        $handler = new AnadirProductoHandler($repositorio);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Carrito no encontrado');

        $command = new AnadirProductoCommand(
            carritoId: $carritoId->valor(),
            productoId: $productoId->valor(),
            cantidad: 1
        );

        $handler->handle($command);
    }
}
