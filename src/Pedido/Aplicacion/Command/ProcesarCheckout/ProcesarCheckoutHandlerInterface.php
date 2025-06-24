<?php

namespace App\Pedido\Aplicacion\Command\ProcesarCheckout;

use App\Pedido\Dominio\PedidoId;

interface ProcesarCheckoutHandlerInterface
{
    public function __invoke(ProcesarCheckoutCommand $command): PedidoId;
}