<?php
namespace App\Carrito\Infraestructura\Persistencia\Doctrine;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito;

#[ORM\Entity]
#[ORM\Table(name: "carrito")]
class Carrito
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 36)]
    private string $id;

    #[ORM\OneToMany(mappedBy: "carrito", targetEntity: ItemCarrito::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $items;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }

    public function setItems(array $items): void
    {
        $this->items = new ArrayCollection();
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    public function addItem(ItemCarrito $item): void
    {   
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCarrito($this);
        }
    }

        public function removeItem(ItemCarrito $item): void
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }
    }
}
