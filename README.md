# Sistema de Gestión de Inventario de Productos Perecederos

Este repositorio contiene un sistema completo de **gestión de inventario de productos perecederos**, dividido en:

- **Backend** → API RESTful desarrollada en **CodeIgniter 4** (PHP + MySQL).  
- **Frontend** → Interfaz de usuario desarrollada en **Angular 17**.

El objetivo es permitir registrar productos, gestionar entradas y salidas de inventario, listar el stock y mostrar el estado de vencimiento de cada lote.

---

## Funcionalidades

- **Productos**
  - Crear productos con nombre, código y unidad.
  - Listar productos existentes.

- **Inventario**
  - **Entradas** → registrar lotes con fecha de caducidad.
  - **Salidas** → aplicar regla (primero en expirar, primero en salir).
  - **Estado de productos**:
    - `VIGENTE`: aún no vence.
    - `POR_VENCER`: faltan ≤ 3 días para su vencimiento.
    - `VENCIDO`: ya pasó la fecha de caducidad.

---

## Estructura del repositorio

```
inventario-proyecto/
 ├── inventario-backend/   # API CodeIgniter 4
 │    ├── app/
 │    ├── public/
 │    ├── composer.json
 │    └── ...
 │
 ├── inventario-frontend/  # Angular frontend
 │    ├── src/
 │    ├── angular.json
 │    ├── package.json
 │    └── ...
 │
 ├── README.md             # Este archivo
 └── DETAILS.md            # Decisiones de diseño y mejoras
```

---

## Requisitos generales

- PHP >= 8.1  
- MySQL >= 8  
- Composer  
- Node.js >= 18  
- Angular CLI  

---

## Instalación

### Backend (CodeIgniter 4)
```terminal
cd inventario-backend
composer install
cp env .env
```

Configura la base de datos en `.env`:
```DATABASE
database.default.hostname = localhost
database.default.database = inventario
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

Ejecuta las migraciones:
```terminal
php spark migrate
```

Levanta el servidor:
```terminal
php spark serve --port 8080
```

---

### Frontend (Angular)
```terminal
cd inventario-frontend
npm install
npm start
```

Accede en el navegador:
```
http://localhost:4200
```

> Incluye un `proxy.conf.json` para enrutar las peticiones y evitar problemas de CORS en desarrollo.

---

## Pruebas

- Crear producto:
```
POST http://localhost:8080/pruebas/productos/crear  -H "Content-Type: application/json"  -d '{"nombre":"Pruebas producto","codigo":"PRU-123","unidad":"lt"}'
```

- Entrada de inventario:
```
POST http://localhost:8080/pruebas/inventario/entrada  -H "Content-Type: application/json"  -d '{"producto_id":1,"cantidad":1,"vence_en":"2025-09-02"}'
```

- Salida de inventario:
```
POST http://localhost:8080/pruebas/inventario/salida  -H "Content-Type: application/json"  -d '{"producto_id":1,"cantidad":1}'
```

- Listar inventario:
```
GET http://localhost:8080/pruebas/inventario/listar
```

---

## Autor
Julian Vargas
Prueba técnica – Ingeniero de Desarrollo  
Fecha de generación: **29/08/2025**
