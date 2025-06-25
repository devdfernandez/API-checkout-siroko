# 🛒 Siroko - Checkout API (Prueba técnica)

Este proyecto implementa una API desacoplada para la gestión de la cesta de la compra y el proceso de checkout de la plataforma e-commerce de Siroko. El objetivo es permitir añadir, actualizar y eliminar productos del carrito, así como procesar pedidos.

---

## 📦 Características principales

- Añadir, actualizar y eliminar productos del carrito.
- Obtener el estado actual de un carrito.
- Procesar el checkout y generar una orden persistida.
- API desacoplada de la UI.
- Arquitectura orientada al dominio y preparada para escalar.

---

## 🧾 Documentación Swagger

Puedes acceder a la documentación de la API en formato OpenAPI a través de Swagger UI:

👉 https://devdfernandez.github.io/API-checkout-siroko/

---

## 🧱 Modelado del Dominio

### 📦 Módulo `Carrito`

- **Entidad**: `Carrito`  
  Representa el carrito de la compra. Contiene una colección de productos (`ItemCarrito`) y está identificado por un `CarritoId`.

- **Objeto de Valor**:
  - `CarritoId`: identificador único del carrito.
  - `ProductoId`: identificador único de producto.

- **Entidad**: `ItemCarrito`  
  Representa un ítem dentro del carrito. Contiene:
  - `productoId`
  - `cantidad`
  - `precio_unitario`

---

### 📦 Módulo `Pedido`

- **Entidad**: `Pedido`  
  Representa una orden de compra procesada a partir de un carrito válido. Contiene múltiples `LineaPedido` y un `PedidoId`.

- **Entidad**: `LineaPedido`  
  Línea individual del pedido. Contiene:
  - `productoId`
  - `cantidad`
  - `precio`

- **Objeto de Valor**:
  - `PedidoId`: identificador único del pedido.

---

### 📦 Módulo `Inventario` (simulado)

- **Servicio de Dominio**: `GestorStock`  
  Servicio que simula la reserva de stock al procesar un pedido (no modifica inventario real, creada por lógica pero no requerida).

---

## ⚙️ Tecnología utilizada
- PHP 8.1
- Symfony 6.x
- PHPUnit 10
- Docker + Docker Compose
- Doctrine ORM
- Symfony Messenger
- Arquitectura Hexagonal + DDD
- CQRS

---

## ⚙️ Montar el entorno

```bash
docker-compose up -d --build
docker exec -it siroko-app bash -> Si el contenedor se llama distinto (`api-checkout-siroko-php`, por ejemplo), sustituye `siroko-app` por el nombre real
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```
Puedes consultar su base de datos desde la interfaz web:

🌐 http://localhost:8081

Use las credenciales de docker-compose.yml para acceder

---

## ✅ Ejecutar los tests

Este proyecto incluye pruebas unitarias, de integración y un test E2E que simula el flujo completo del carrito y checkout.

### 🚀 Ejecutar el test E2E principal

```bash
php bin/phpunit tests/e2e/CarritoPedidoE2ETest.php
