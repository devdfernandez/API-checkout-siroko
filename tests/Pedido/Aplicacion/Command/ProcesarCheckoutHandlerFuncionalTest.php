<?php

namespace App\Tests\Pedido\Aplicacion\Command;

use App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\ProductoId;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutCommand;
use App\Pedido\Aplicacion\Command\ProcesarCheckout\ProcesarCheckoutHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ProcesarCheckoutHandlerFuncionalTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testPersistenciaDePedido(): void
    {
        $uuid = Uuid::uuid4()->toString();
        $carritoId = new CarritoId($uuid);
        $carrito = new Carrito($carritoId);
        $carrito->anadirItem(new ItemCarrito(new ProductoId('p1'), 2, 10.0));
        $carrito->anadirItem(new ItemCarrito(new ProductoId('p2'), 1, 30.0));

        $repositorioCarrito = static::getContainer()->get('App\Carrito\Infraestructura\Repositorio\RepositorioCarritoDoctrine');
        $repositorioCarrito->guardar($carrito);

        $handler = static::getContainer()->get(ProcesarCheckoutHandler::class);
        $command = new ProcesarCheckoutCommand(
            $uuid,
            [
                ['productoId' => 'p1', 'cantidad' => 2, 'precio' => 10.0],
                ['productoId' => 'p2', 'cantidad' => 1, 'precio' => 30.0],
            ]
        );
        $pedidoId = $handler->__invoke($command);

        $pedido = $this->entityManager->getRepository(Pedido::class)->find($pedidoId->valor());
        $this->assertNotNull($pedido);
        $this->assertEquals(50.0, $pedido->getTotal());
        $this->assertCount(2, $pedido->getLineas());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
