<?php

namespace App\Pedido\Dominio;

interface RepositorioPedido
{
    public function guardar(Pedido $pedido): void;
}
