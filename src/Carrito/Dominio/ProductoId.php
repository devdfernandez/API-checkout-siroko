<?php 
namespace App\Carrito\Dominio;

class ProductoId
{
    private string $valor;

    public function __construct(string $valor)
    {
        $this->valor = $valor;
    }

    public function valor(): string
    {
        return $this->valor;
    }
}

?>