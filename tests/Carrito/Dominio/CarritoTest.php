<?php

namespace App\Tests\Carrito\Dominio;

use PHPUnit\Framework\TestCase;
use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\ProductoId;

class CarritoTest extends TestCase
{   
    private const TEST_PRODUCTO_ID = 'sirokocart-prodid-1234-test-12fghjk345';

    public function testAnadirItemNuevoAlCarrito()
    {
        $carritoId = new CarritoId();
        $carrito = new Carrito($carritoId);

        $productoId = new ProductoId(self::TEST_PRODUCTO_ID);
        $item = new ItemCarrito($productoId, 2, 10.0);

        $carrito->anadirItem($item);

        $items = $carrito->items();

        $this->assertCount(1, $items);
        $this->assertEquals(self::TEST_PRODUCTO_ID, $items[0]->productoId()->valor());
        $this->assertEquals(2, $items[0]->cantidad());
        $this->assertEquals(10.0, $items[0]->precio());
        $this->assertEquals(20.0, $carrito->total());
    }

    public function testAnadirItemExistenteSumaCantidad()
    {
        $carritoId = new CarritoId();
        $carrito = new Carrito($carritoId);

        $productoId = new ProductoId(self::TEST_PRODUCTO_ID);
        $item1 = new ItemCarrito($productoId, 2, 10.0);
        $item2 = new ItemCarrito($productoId, 3, 10.0);

        $carrito->anadirItem($item1);
        $carrito->anadirItem($item2);

        $items = $carrito->items();

        $this->assertCount(1, $items);
        $this->assertEquals(5, $items[0]->cantidad());
        $this->assertEquals(50.0, $carrito->total());
    }
}
