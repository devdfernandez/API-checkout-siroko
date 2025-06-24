<?php
namespace App\Tests\Pedido\Infraestructura\Controlador;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido;
use Doctrine\ORM\EntityManagerInterface;

class ObtenerPedidoControllerFuncionalTest extends WebTestCase
{
    public function testObtenerListadoDePedidos(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        $entityManager->createQuery('DELETE FROM App\Pedido\Infraestructura\Persistencia\Doctrine\Pedido p')->execute();
        $entityManager->flush();
        $entityManager->clear();

        $pedidoId = 'pedido-id-test';
        $pedido = new Pedido($pedidoId, [['productoId' => 'p1', 'cantidad' => 1, 'precio' => 20.0]], 20.0);

        $entityManager->persist($pedido);
        $entityManager->flush();

        $client->request('GET', "/pedidos/$pedidoId");
        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $contenido = json_decode($response->getContent(), true);
        $this->assertIsArray($contenido);
        $this->assertEquals($pedidoId, $contenido['id']);
    }
}
