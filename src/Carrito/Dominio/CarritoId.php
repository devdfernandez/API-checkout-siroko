<?php 
namespace App\Carrito\Dominio;

use Ramsey\Uuid\Uuid;

class CarritoId
{
    private string $valor;

    public function __construct(?string $valor = null)
    {
        $this->valor = $valor ?? Uuid::uuid4()->toString();
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(CarritoId $otro): bool
    {
        return $this->valor === $otro->valor;
    }
}

?>