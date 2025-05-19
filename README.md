# Sistema de Venta de Productos – Laravel + Filament

Este proyecto es un sistema simple de gestión de ventas desarrollado con Laravel y FilamentPHP. Permite la administración de productos e inventario, así como la creación y seguimiento de órdenes de venta. Se ha incluido autenticación de usuarios, control de permisos y patrones de diseño para mantener una arquitectura clara y escalable.

---

## 🧰 Tecnologías Utilizadas

- [Laravel](https://laravel.com/) – Framework PHP para backend
- [FilamentPHP](https://filamentphp.com/) – Panel administrativo moderno
- SQLite – Base de datos liviana usada en desarrollo
- Laravel Breeze + Filament Breezy – Para login y registro de usuarios

---

## 📦 Funcionalidades Principales

### 🔐 Autenticación de Usuarios

- Registro de nuevos usuarios desde `/admin/register`
- Inicio de sesión desde `/admin/login`
- Control de acceso a funcionalidades según el rol asignado

### 📦 Gestión de Inventario

- CRUD completo de productos
- Campos: nombre, precio, stock
- Validación de datos y restricciones mínimas

### 🧾 Registro de Órdenes de Venta

- Selección de múltiples productos por orden
- Cálculo automático de totales (precio x cantidad)
- Descuento automático del stock
- Vista de resumen de productos en la orden

---

## 🔐 Control de Acceso y Roles

Se ha implementado el paquete [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) para definir:

- Roles como `admin`, `cliente`, `vendedor`, etc.
- Permisos personalizados por recurso
- Ocultamiento dinámico de opciones del menú en función del permiso

---

## 🎯 Patrones de Diseño Aplicados

### 🧱 Command Pattern
- Aplicado en la clase `DecreaseStock` para encapsular la lógica de descuento de stock como un comando independiente.

### 🧰 Builder Pattern
- Utilizado en la construcción del formulario de órdenes (`Repeater`, `Select`, `TextInput`) mediante el enfoque encadenado de Filament (`Fluent Interface`).

### 👁️ Observer Pattern
- Reactividad en formularios mediante `->reactive()` y `->afterStateUpdated()` para recalcular automáticamente valores como el total por producto.

---

## 🧪 Base de Datos

- Se utilizó **SQLite** para facilitar la instalación y pruebas locales.
- Los archivos `.sqlite` y `database/database.sqlite` están listos para su uso.
- En caso de utilizar la bd sqlite database.sqlite las credenciales son:
  - ```
      email: admin@admin.com
      password: Admin123
      ```

---

## 🚀 Instalación

```bash
git clone https://github.com/tu-usuario/sistema-ventas.git
cd sistema-ventas

composer install
npm install && npm run build

cp .env.example .env
touch database/database.sqlite
php artisan migrate --seed

php artisan serve
