<?php

namespace App\Tests\Carrito\Aplicacion\Command\EliminarProducto;

use App\Carrito\Aplicacion\Command\EliminarProducto\EliminarProductoCommand;
use App\Carrito\Aplicacion\Command\EliminarProducto\EliminarProductoHandler;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\RepositorioCarrito;
use PHPUnit\Framework\TestCase;

class EliminarProductoHandlerTest extends TestCase
{
    public function testEliminarProductoExistente(): void
    {
        $carritoId = new CarritoId('11111111-1111-1111-1111-111111111111');
        $productoId = new ProductoId('22222222-2222-2222-2222-222222222222');
        $item = new ItemCarrito($productoId, 2, 19.99);
        $carrito = new Carrito($carritoId, [$item]);

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn($carrito);
        $repositorio->expects($this->once())->method('guardar')->with($carrito);

        $handler = new EliminarProductoHandler($repositorio);

        $command = new EliminarProductoCommand(
            carritoId: $carritoId->valor(),
            productoId: $productoId->valor()
        );

        $handler->__invoke($command);

        $this->assertCount(0, $carrito->items());
    }

    public function testEliminarProductoSinCarrito(): void
    {
        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn(null);

        $handler = new EliminarProductoHandler($repositorio);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Carrito no encontrado');

        $command = new EliminarProductoCommand(
            carritoId: '11111111-1111-1111-1111-111111111111',
            productoId: '22222222-2222-2222-2222-222222222222'
        );

        $handler->__invoke($command);
    }

    public function testEliminarProductoSinProducto(): void
    {
        $carritoId = new CarritoId('11111111-1111-1111-1111-111111111111');
        $item = new ItemCarrito(new ProductoId('producto-diferente-uuid-1234-1234-1234-1234567890ab'), 1, 9.99);
        $carrito = new Carrito($carritoId, [$item]);

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn($carrito);

        $handler = new EliminarProductoHandler($repositorio);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Producto no encontrado en el carrito.');

        $command = new EliminarProductoCommand(
            carritoId: $carritoId->valor(),
            productoId: '22222222-2222-2222-2222-222222222222'
        );

        $handler->__invoke($command);
    }
}
