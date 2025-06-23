<?php 
namespace App\Carrito\Dominio;

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
}

?>