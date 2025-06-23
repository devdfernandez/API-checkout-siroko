<?php
namespace App\Carrito\Infraestructura\Controlador;

use App\Carrito\Aplicacion\Command\AnadirProducto\AnadirProductoCommand;
use App\Carrito\Aplicacion\Command\AnadirProducto\AnadirProductoHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AnadirProductoController
{
    private AnadirProductoHandler $handler;

    public function __construct(AnadirProductoHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/carrito/{carritoId}/producto", methods={"POST"})
     */
    public function __invoke(Request $request, string $carritoId): JsonResponse
    {
        $datos = json_decode($request->getContent(), true);

        $command = new AnadirProductoCommand(
            $carritoId,
            $datos['productoId'] ?? '',
            (int)($datos['cantidad'] ?? 1)
        );

        $this->handler->handle($command);

        return new JsonResponse(['status' => 'Producto añadido'], 200);
    }
}

?>