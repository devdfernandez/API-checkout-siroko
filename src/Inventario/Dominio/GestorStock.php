<?php

namespace App\Inventario\Dominio;

use App\Pedido\Dominio\LineaPedido;

class GestorStock
{
    public function reservarStock(LineaPedido $lineaPedido): void
    {
        // Logica de comprobacion y decremento de stock
        // manteniendo una reserva de productos para evitar choques en el checkout
    }
}