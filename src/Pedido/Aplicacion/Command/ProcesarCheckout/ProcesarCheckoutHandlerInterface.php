<?php

namespace App\Pedido\Aplicacion\Command\ProcesarCheckout;

interface ProcesarCheckoutHandlerInterface
{
    public function __invoke(ProcesarCheckoutCommand $command): void;
}