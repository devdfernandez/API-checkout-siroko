<?php

namespace App\Carrito\Infraestructura\Repositorio;

use App\Carrito\Dominio\Carrito;
use App\Carrito\Dominio\ItemCarrito;
use App\Carrito\Dominio\CarritoId;
use App\Carrito\Dominio\ProductoId;
use App\Carrito\Dominio\RepositorioCarrito;
use App\Carrito\Infraestructura\Persistencia\Doctrine\Carrito as CarritoEntity;
use App\Carrito\Infraestructura\Persistencia\Doctrine\ItemCarrito as ItemCarritoEntity;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class RepositorioCarritoDoctrine implements RepositorioCarrito
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function guardar(Carrito $carrito): void
    {
        // Verifica si ya existe el carrito en Doctrine para evitar colisión
        $carritoExistente = $this->entityManager
            ->getRepository(CarritoEntity::class)
            ->find($carrito->id()->valor());

        if ($carritoExistente) {
            $this->entityManager->remove($carritoExistente);
            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        $carritoEntity = new CarritoEntity($carrito->id()->valor());

        $itemsEntity = [];
        foreach ($carrito->items() as $itemDominio) {
            $itemEntity = ItemCarritoEntity::crear(
                $itemDominio->productoId()->valor(),
                $itemDominio->cantidad(),
                $itemDominio->precio()
            );
            $itemEntity->setCarrito($carritoEntity);
            $itemsEntity[] = $itemEntity;
        }

        $carritoEntity->setItems($itemsEntity);

        $this->entityManager->persist($carritoEntity);
        $this->entityManager->flush();
    }

    public function buscarPorId(CarritoId $id): ?Carrito
    {
    return $this->buscar($id->valor());
    }

    public function buscar(string $id): ?Carrito
    {
        $qb = $this->entityManager->createQueryBuilder();

        /** @var CarritoEntity|null $carritoEntity */
        $carritoEntity = $qb
            ->select('c', 'i')
            ->from(CarritoEntity::class, 'c')
            ->leftJoin('c.items', 'i')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$carritoEntity) {
            return null;
        }

        $itemsDominio = [];
        foreach ($carritoEntity->getItems() as $itemEntity) {
            $itemsDominio[] = new ItemCarrito(
                new ProductoId($itemEntity->getProductoId()),
                $itemEntity->getCantidad(),
                $itemEntity->getPrecioUnitario()
            );
        }

        return new Carrito(
            new CarritoId(Uuid::fromString($carritoEntity->getId())),
            $itemsDominio
        );
    }
}
