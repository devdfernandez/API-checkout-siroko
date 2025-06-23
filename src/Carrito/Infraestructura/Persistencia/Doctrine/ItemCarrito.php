<?php
namespace App\Carrito\Infraestructura\Persistencia\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito;

#[ORM\Entity]
#[ORM\Table(name: "carrito_items")]
class ItemCarrito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", name: "producto_id")]
    private string $productoId;

    #[ORM\Column(type: "integer")]
    private int $cantidad;

    #[ORM\Column(type: "float", name: "precio_unitario")]
    private float $precioUnitario;

    #[ORM\ManyToOne(targetEntity: Carrito::class, inversedBy: "items")]
    #[ORM\JoinColumn(name: "carrito_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Carrito $carrito;

    public function __construct(){}

    public static function crear(string $productoId, int $cantidad, float $precio): self
    {
        $item = new self();
        $item->productoId = $productoId;
        $item->cantidad = $cantidad;
        $item->precioUnitario = $precio;
        return $item;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductoId(): string
    {
        return $this->productoId;
    }

    public function setProductoId(string $productoId): void
    {
        $this->productoId = $productoId;
    }

    public function getCantidad(): int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): void
    {
        $this->cantidad = $cantidad;
    }

    public function getPrecioUnitario(): float
    {
        return $this->precioUnitario;
    }

    public function setPrecioUnitario(float $precioUnitario): void
    {
        $this->precioUnitario = $precioUnitario;
    }

    public function getCarrito(): Carrito
    {
        return $this->carrito;
    }

    public function setCarrito(Carrito $carrito): void
    {
        $this->carrito = $carrito;
    }
}
