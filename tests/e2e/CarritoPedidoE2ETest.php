<?php

namespace App\Tests\e2e;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CarritoPedidoE2ETest extends WebTestCase
{
    public function testFlujoCompletoCarritoCheckoutPedido(): void
    {
        $client = static::createClient();

        $crearCarrito = '/carrito';
        $client->request('POST', $crearCarrito);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $contenido = json_decode($client->getResponse()->getContent(), true);
        $carritoId = $contenido['id'] ?? null;
        $this->assertNotNull($carritoId);

        $añadirProductoACarrito = "/carrito/$carritoId/producto";
        $client->request(
            'POST',
            $añadirProductoACarrito,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'productoId' => 'p1',
                'cantidad' => 2,
                'precio' => 20.0
            ])
        );
        $this->assertResponseIsSuccessful();

        $consultarCarrito = "/carrito/$carritoId";
        $client->request('GET', $consultarCarrito);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $carrito = json_decode($response->getContent(), true);
        $this->assertEquals($carritoId, $carrito['id']);
        $this->assertCount(1, $carrito['items']);

        $procesarPago = "/checkout";
        $client->request(
            'POST',
            $procesarPago,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'carritoId' => $carritoId
            ])
        );
        $this->assertResponseIsSuccessful();

        $pedido = json_decode($client->getResponse()->getContent(), true);
        $pedidoId = $pedido['id'] ?? null;
        $this->assertNotNull($pedidoId);

        $consultarPedido = "/pedidos/$pedidoId";
        $client->request('GET', $consultarPedido);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $pedidoDetalle = json_decode($response->getContent(), true);
        $this->assertEquals($pedidoId, $pedidoDetalle['id']);
        $this->assertEquals(40.0, $pedidoDetalle['total']);
        $this->assertCount(1, $pedidoDetalle['lineas']);
    }
}
