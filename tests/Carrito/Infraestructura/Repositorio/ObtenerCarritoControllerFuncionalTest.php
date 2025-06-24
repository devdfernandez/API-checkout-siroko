<?php
namespace App\Tests\Carrito\Infraestructura\Controlador;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito as CarritoEntidad;
use App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito as ItemCarritoEntidad;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class ObtenerCarritoControllerFuncionalTest extends WebTestCase
{
    public function testObtenerCarritoConProductos(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        $entityManager->createQuery('DELETE FROM App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito i')->execute();
        $entityManager->createQuery('DELETE FROM App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito c')->execute();

        $uuid = Uuid::uuid4()->toString();
        $carrito = new CarritoEntidad($uuid);

        $item = ItemCarritoEntidad::crear('p1', 2, 10.0);
        $carrito->addItem($item);

        $entityManager->persist($carrito);
        $entityManager->flush();

        $client->request('GET', "/carrito/{$uuid}");
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $contenido = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('items', $contenido);
        $this->assertCount(1, $contenido['items']);
        $this->assertEquals('p1', $contenido['items'][0]['productoId']);
    }
}
