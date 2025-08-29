# DETAILS.md

## Diseño

1. **Separación de capas**
   - Se implementó un **backend REST** con CodeIgniter 4 para garantizar orden y validaciones.
   - El frontend se desarrolló en **Angular standalone** para simplicidad y modularidad.

2. **Modelo de datos**
   - Se definieron tres tablas:
     - `productos`: catálogo base.
     - `lotes`: cada entrada de inventario genera un lote con fecha de vencimiento.
     - `movimientos`: histórico de entradas y salidas.
   - Esta estructura permite crecer a un esquema multi-bodega o multi-sucursal en el futuro.

3. **Reglas de negocio**
   - Se implementó la lógica **FEFO** (First Expire, First Out) en las salidas.
   - El estado (`VIGENTE`, `POR_VENCER`, `VENCIDO`) se calcula dinámicamente en la API, no se almacena, garantizando exactitud.

4. **Buenas prácticas**
   - Validaciones en backend (`required`, `is_natural_no_zero`, `valid_date`).
   - Separación de responsabilidades (controladores, modelos, helper).
   - Uso de `proxy.conf.json` en Angular para evitar problemas de CORS en desarrollo.

5. **Frontend**
   - Se usaron **componentes standalone** para cada vista:
     - `lista-productos`
     - `entradas-inventario`
     - `salidas-inventario`
     - `busqueda-inventario`
   - Esto permite que cada parte sea fácilmente escalable y testeable.

---

## Mejoras y escalabilidad

- **Autenticación y roles**  
  Integrar un módulo de usuarios con JWT para controlar quién puede registrar entradas/salidas.

- **Notificaciones**  
  Agregar alertas por correo o dashboard cuando un lote esté por vencer.

- **Reportes**  
  Exportación en PDF/Excel de inventarios, movimientos y vencimientos próximos.

- **Infraestructura**  
  Migrar a contenedores Docker para facilitar despliegues.

- **UI/UX**  
  Mejorar estilos con librerías de componentes (Material, Bootstrap).

---

## Fecha de generación
**29/08/2025**
