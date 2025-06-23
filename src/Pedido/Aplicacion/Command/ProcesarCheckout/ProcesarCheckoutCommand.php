<?php

namespace App\Pedido\Aplicacion\Command\ProcesarCheckout;

final class ProcesarCheckoutCommand
{
    public function __construct(
        public readonly string $carritoId
    ) {}
}
