<?php

namespace App\Tests\Pedido\Aplicacion\Command;

use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutCommand;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandler;
use App\Pedido\Dominio\Pedido;
use App\Pedido\Dominio\RepositorioPedido;
use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Inventario\Dominio\GestorStock;
use PHPUnit\Framework\TestCase;

class ProcesarCheckoutHandlerTest extends TestCase
{
    public function testProcesarCheckoutCorrectamente(): void
    {
        $carritoId = new CarritoId('c1');
        $carrito = new Carrito($carritoId);
        $carrito->anadirItem(new ItemCarrito(new ProductoId('p1'), 2, 25.0));
        $carrito->anadirItem(new ItemCarrito(new ProductoId('p2'), 1, 50.0));

        /** @var Pedido|null $capturado */
        $capturado = null;

        /** @var RepositorioCarrito&\PHPUnit\Framework\MockObject\MockObject $repositorioCarrito */
        $repositorioCarrito = $this->createMock(RepositorioCarrito::class);
        $repositorioCarrito->method('buscarPorId')->willReturn($carrito);

        /** @var RepositorioPedido&\PHPUnit\Framework\MockObject\MockObject $repositorioPedido */
        $repositorioPedido = $this->createMock(RepositorioPedido::class);
        $repositorioPedido->expects($this->once())
            ->method('guardar')
            ->with($this->callback(function ($pedido) use (&$capturado) {
                $capturado = $pedido;
                return true;
            }));
        
        /** @var GestorStock&\PHPUnit\Framework\MockObject\MockObject $gestorStock */
        $gestorStock = $this->createMock(GestorStock::class);
        $gestorStock->expects($this->exactly(2))->method('reservarStock');

        $handler = new ProcesarCheckoutHandler($repositorioCarrito, $repositorioPedido, $gestorStock);

        $command = new ProcesarCheckoutCommand('c1');
        $handler->__invoke($command);

        $this->assertInstanceOf(Pedido::class, $capturado);
        $this->assertEquals(100.0, $capturado->total());
        $this->assertCount(2, $capturado->lineas());
    }
}
