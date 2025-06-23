<?php

namespace App\Tests\Carrito\Aplicacion\Command\ActualizarProducto;

use App\Carrito\Aplicacion\Command\ActualizarProducto\ActualizarProductoCommand;
use App\Carrito\Aplicacion\Command\ActualizarProducto\ActualizarProductoHandler;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\RepositorioCarrito;
use PHPUnit\Framework\TestCase;

class ActualizarProductoHandlerTest extends TestCase
{
    public function testActualizarCantidadExistente(): void
    {
        $carritoId = new CarritoId('carrito-1');
        $productoId = new ProductoId('producto-1');
        $item = new ItemCarrito($productoId, 2, 19.99);
        $carrito = new Carrito($carritoId, [$item]);

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->with($carritoId)->willReturn($carrito);
        $repositorio->expects($this->once())->method('guardar')->with($carrito);

        $handler = new ActualizarProductoHandler($repositorio);

        $command = new ActualizarProductoCommand(
            carritoId: 'carrito-1',
            productoId: 'producto-1',
            nuevaCantidad: 5
        );

        $handler->__invoke($command);

        $this->assertEquals(5, $carrito->items()[0]->cantidad());
    }

    public function testActualizarCantidadSinCarrito(): void
    {
        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn(null);

        $handler = new ActualizarProductoHandler($repositorio);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Carrito no encontrado');

        $command = new ActualizarProductoCommand(
            'inexistente',
            'producto-1',
            3
        );

        $handler->__invoke($command);
    }

    public function testActualizarCantidadSinProducto(): void
    {
        $carritoId = new CarritoId('carrito-2');
        $otroProducto = new ProductoId('otro-producto');
        $item = new ItemCarrito($otroProducto, 1, 9.99);
        $carrito = new Carrito($carritoId, [$item]);

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorio */
        $repositorio = $this->createMock(RepositorioCarrito::class);
        $repositorio->method('buscarPorId')->willReturn($carrito);

        $handler = new ActualizarProductoHandler($repositorio);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Producto no encontrado en el carrito.');

        $command = new ActualizarProductoCommand(
            'carrito-2',
            'producto-inexistente',
            2
        );

        $handler->__invoke($command);
    }
}
