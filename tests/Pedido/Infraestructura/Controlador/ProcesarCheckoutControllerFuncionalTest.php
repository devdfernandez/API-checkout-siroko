<?php
namespace App\Tests\Pedido\Infraestructura\Controlador;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito as CarritoEntidad;
use App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito as ItemCarritoEntidad;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ProcesarCheckoutControllerFuncionalTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get(EntityManagerInterface::class);

        // Limpiar base de datos de prueba
        $this->entityManager->createQuery('DELETE FROM App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido p')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito i')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito c')->execute();
    }

    public function testCheckoutDesdeElControlador(): void
    {
        $uuid = Uuid::uuid4()->toString();

        $carrito = new CarritoEntidad($uuid);

        $item1 = ItemCarritoEntidad::crear('p1', 2, 25.0);
        $item2 = ItemCarritoEntidad::crear('p2', 1, 50.0);

        $carrito->addItem($item1);
        $carrito->addItem($item2);

        $this->entityManager->persist($carrito);
        $this->entityManager->persist($item1);
        $this->entityManager->persist($item2);
        $this->entityManager->flush();

        $this->client->request('POST', "/checkout/{$uuid}");

        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode(), 'Respuesta HTTP incorrecta');

        $contenido = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('status', $contenido);
        $this->assertEquals('Pedido generado correctamente', $contenido['status']);
    }
}
