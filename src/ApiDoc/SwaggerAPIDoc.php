<?php

namespace App\ApiDoc;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Siroko Checkout API",
    version: "1.0.0",
    description: "Documentación de la API de carrito y pedidos"
)]
#[OA\Tag(name: "Carrito", description: "Operaciones relacionadas con el carrito")]
#[OA\Tag(name: "Pedido", description: "Operaciones relacionadas con pedidos")]
final class SwaggerAPIDoc
{
    // --- Carrito ---

    #[OA\Post(
        path: "/carrito",
        summary: "Crear un carrito",
        tags: ["Carrito"],
        responses: [
            new OA\Response(response: 201, description: "Carrito creado")
        ]
    )]
    public function crearCarrito(): void {}

    #[OA\Post(
        path: "/carrito/{carritoId}/producto",
        summary: "Añadir producto al carrito",
        tags: ["Carrito"],
        parameters: [
            new OA\Parameter(name: "carritoId", in: "path", required: true, schema: new OA\Schema(type: "string"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["productoId", "cantidad"],
                properties: [
                    new OA\Property(property: "productoId", type: "string"),
                    new OA\Property(property: "cantidad", type: "integer"),
                    new OA\Property(property: "precio", type: "number", format: "float")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Producto añadido correctamente"),
            new OA\Response(response: 400, description: "Carrito no encontrado")
        ]
    )]
    public function anadirProducto(): void {}

    #[OA\Put(
        path: "/carrito/{carritoId}/producto",
        summary: "Actualizar producto del carrito",
        tags: ["Carrito"],
        parameters: [
            new OA\Parameter(name: "carritoId", in: "path", required: true, schema: new OA\Schema(type: "string"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["productoId", "cantidad"],
                properties: [
                    new OA\Property(property: "productoId", type: "string"),
                    new OA\Property(property: "cantidad", type: "integer"),
                    new OA\Property(property: "precio", type: "number", format: "float")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Producto actualizado correctamente")
        ]
    )]
    public function actualizarProducto(): void {}

    #[OA\Delete(
        path: "/carrito/{carritoId}/producto",
        summary: "Eliminar producto del carrito",
        tags: ["Carrito"],
        parameters: [
            new OA\Parameter(name: "carritoId", in: "path", required: true, schema: new OA\Schema(type: "string"))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["productoId"],
                properties: [
                    new OA\Property(property: "productoId", type: "string")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Producto eliminado del carrito")
        ]
    )]
    public function eliminarProducto(): void {}

    #[OA\Get(
        path: "/carrito/{carritoId}",
        summary: "Consultar carrito",
        tags: ["Carrito"],
        parameters: [
            new OA\Parameter(name: "carritoId", in: "path", required: true, schema: new OA\Schema(type: "string"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Contenido del carrito")
        ]
    )]
    public function consultarCarrito(): void {}

    // --- Pedido ---

    #[OA\Post(
        path: "/checkout/{carritoId}",
        summary: "Procesa el carrito y genera un pedido",
        tags: ["Pedido"],
        parameters: [
            new OA\Parameter(
                name: "carritoId",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string"),
                description: "ID del carrito"
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Pedido generado correctamente"
            ),
            new OA\Response(
                response: 400,
                description: "Carrito vacío o no encontrado"
            )
        ]
    )]
    public function procesarCheckout(): void {}

    #[OA\Get(
        path: "/pedido/{pedidoId}",
        summary: "Consultar pedido",
        tags: ["Pedido"],
        parameters: [
            new OA\Parameter(name: "pedidoId", in: "path", required: true, schema: new OA\Schema(type: "string"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Pedido encontrado"),
            new OA\Response(response: 404, description: "Pedido no encontrado")
        ]
    )]
    public function consultarPedido(): void {}
}
