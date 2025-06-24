<?php
namespace App\Tests\Carrito\Infraestructura\Repositorio;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Infraestructura\Repositorio\RepositorioCarritoDoctrine;
use Ramsey\Uuid\Uuid;

class RepositorioCarritoDoctrineTest extends KernelTestCase
{
    private RepositorioCarritoDoctrine $repositorio;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Reinicia el esquema (vacía la base de datos)
        $this->reiniciarEsquema();

        // Instancia el repositorio con el EntityManager
        $this->repositorio = new RepositorioCarritoDoctrine($this->entityManager);
    }

    private function reiniciarEsquema(): void
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        if (!empty($metadata)) {
            $tool = new SchemaTool($this->entityManager);
            $tool->dropDatabase();
            $tool->createSchema($metadata);
        }
    }

    public function testGuardarCarritoConMultiplesItemsEnBD(): void
    {
        $carrito = new Carrito(new CarritoId());

        $item1 = new ItemCarrito(new ProductoId('producto-abc-123'), 1, 9.99);
        $item2 = new ItemCarrito(new ProductoId('producto-def-456'), 3, 19.50);
        $item3 = new ItemCarrito(new ProductoId('producto-ghi-789'), 2, 5.00);

        $carrito->anadirItem($item1);
        $carrito->anadirItem($item2);
        $carrito->anadirItem($item3);

        $this->repositorio->guardar($carrito);

        $carritoGuardado = $this->repositorio->buscarPorId($carrito->id());

        $this->assertNotNull($carritoGuardado);
        $this->assertCount(3, $carritoGuardado->items());

        $productoIds = array_map(
            fn($item) => $item->productoId()->valor(),
            $carritoGuardado->items()
        );

        $this->assertContains('producto-abc-123', $productoIds);
        $this->assertContains('producto-def-456', $productoIds);
        $this->assertContains('producto-ghi-789', $productoIds);
    }
}