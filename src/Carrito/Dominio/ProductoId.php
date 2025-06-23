<?php 
namespace App\Carrito\Dominio;

use Ramsey\Uuid\Uuid;

class ProductoId
{
    private string $valor;

    public function __construct(string $valor)
    {
        if (empty($valor))
            throw new \InvalidArgumentException('ProductoId no puede ser vacío.');
        
        $this->valor = $valor;
    }

    public function valor(): string
    {
        return $this->valor;
    }

    public function equals(ProductoId $otro): bool
    {
        return $this->valor === $otro->valor;
    }
}

?>