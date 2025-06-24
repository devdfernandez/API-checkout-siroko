<?php

namespace App\Pedido\Dominio;

interface RepositorioPedido
{
    public function guardar(Pedido $pedido): void;

    public function buscarPorId(PedidoId $id): ?Pedido;

}
