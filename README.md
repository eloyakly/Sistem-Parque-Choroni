# 🏢 Sistem Parque Choroni

Sistema de gestión de condominio desarrollado con **Laravel 12** y **CSS nativo**. Diseñado para uso diario por administradores de edificios, con una interfaz minimalista, elegante y soporte de **tema claro y oscuro**.

---

## 🎯 Descripción

**Sistem Parque Choroni** permite a la administración de un condominio gestionar propietarios, apartamentos, gastos mensuales y la facturación de cuotas de condominio de manera organizada y eficiente.

El flujo de facturación funciona en dos pasos:

1. El administrador carga los **Gastos del Mes** (agua, vigilancia, reparaciones, etc.).
2. Al generar una factura, solo selecciona el mes cargado y la **alícuota** del apartamento, y el sistema calcula automáticamente el monto a cobrar.

---

## 🧱 Módulos del Sistema

| Módulo                   | Descripción                            |
| :----------------------- | :------------------------------------- |
| 🔐 **Acceso**            | Pantalla de inicio de sesión           |
| 🏠 **Inicio**            | Dashboard con resumen estadístico      |
| 👤 **Propietarios**      | Directorio de residentes               |
| 🏢 **Apartamentos**      | Gestión de unidades (con Torre y Tipo) |
| 📋 **Tipos de Inmueble** | Categorías con su alícuota base        |
| 💰 **Gastos del Mes**    | Carga dinámica de gastos generales     |
| 📄 **Facturas**          | Generación de recibos por alícuota     |
| 💳 **Pagos**             | Registro de cobros recibidos           |

---

## 🗃️ Base de Datos

### Orden de dependencias (migraciones)

```
propietarios
tipo_apartamentos
    └── apartamentos (torre, número, alícuota)
            ├── facturas
            └── pagos
gasto_mes
    └── gasto_detalles
```

### Tablas principales

| Tabla               | Campos clave                                                                         |
| :------------------ | :----------------------------------------------------------------------------------- |
| `propietarios`      | nombre, apellido, cedula, telefono, email                                            |
| `tipo_apartamentos` | nombre, alicuota                                                                     |
| `apartamentos`      | torre, numero, tipo_apartamento_id, propietario_id, deuda_actual                     |
| `facturas`          | apartamento_id, descripcion, monto_total, saldo_pendiente, estado, fecha_vencimiento |
| `pagos`             | apartamento_id, monto, fecha_pago, referencia, metodo_pago                           |
| `gasto_mes`         | mes_anio, total_gastos, procesado                                                    |
| `gasto_detalles`    | gasto_mes_id, descripcion, monto                                                     |

---

## 🚀 Instalación

### Requisitos

- PHP >= 8.2
- Node.js >= 18
- MySQL / MariaDB
- Composer

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-del-repositorio>
cd Sistem-Parque-Choroni

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar el entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=parque
DB_USERNAME=root
DB_PASSWORD=

# 6. Ejecutar migraciones
php artisan migrate

# 7. Iniciar los servidores de desarrollo
php artisan serve   # Backend: http://localhost:8000
npm run dev         # Frontend (Vite)
```

---

## 🗂️ Estructura de Vistas

```
resources/views/
├── acceso.blade.php                 # Inicio de sesión
├── layouts/
│   └── plantilla.blade.php          # Layout principal
├── components/
│   ├── navegacion_lateral.blade.php # Menú lateral
│   └── navegacion_superior.blade.php# Barra superior + cambio de tema
├── inicio/
│   └── index.blade.php              # Dashboard
├── propietarios/
│   ├── index.blade.php
│   └── crear.blade.php
├── apartamentos/
│   ├── index.blade.php
│   └── crear.blade.php
├── tipos_apartamentos/
│   ├── index.blade.php
│   └── crear.blade.php
├── gastos_mensuales/
│   ├── index.blade.php
│   ├── crear.blade.php
│   └── editar.blade.php
├── facturas/
│   ├── index.blade.php
│   └── crear.blade.php
└── pagos/
    ├── index.blade.php
    └── crear.blade.php
```

---

## 🎨 Sistema de Diseño

Los estilos se gestionan en `resources/css/app.css` usando **CSS nativo con variables** para soportar los dos temas:

```css
/* Tema Claro */
:root { --color-fondo: #f8f9fa; --color-acentuar: #0984e3; ... }

/* Tema Oscuro */
[data-tema="oscuro"] { --color-fondo: #121212; --color-acentuar: #3498db; ... }
```

El tema se guarda en `localStorage` y se aplica automáticamente al recargar. El usuario lo cambia con el botón 🌓 en la barra superior.

---

## 🏷️ Convenciones del Proyecto

- **Nombres en español**: vistas, componentes, clases CSS, rutas con nombre.
- **Clases CSS en español**: `.boton`, `.boton-primario`, `.tarjeta`, `.barra-lateral`, `.item-menu`, etc.
- **Rutas resource en español**: `/propietarios`, `/apartamentos`, `/gastos-mensuales`, etc.

---

## 📡 Rutas Disponibles

| Método   | URI                   | Nombre                 | Descripción                  |
| :------- | :-------------------- | :--------------------- | :--------------------------- |
| GET      | `/`                   | —                      | Redirige al acceso           |
| GET      | `/acceso`             | `login`                | Pantalla de inicio de sesión |
| GET      | `/inicio`             | `inicio`               | Dashboard                    |
| RESOURCE | `/propietarios`       | `propietarios.*`       | CRUD propietarios            |
| RESOURCE | `/apartamentos`       | `apartamentos.*`       | CRUD apartamentos            |
| RESOURCE | `/tipos-apartamentos` | `tipos-apartamentos.*` | CRUD tipos                   |
| RESOURCE | `/gastos-mensuales`   | `gastos-mensuales.*`   | CRUD gastos                  |
| RESOURCE | `/facturas`           | `facturas.*`           | CRUD facturas                |
| RESOURCE | `/pagos`              | `pagos.*`              | CRUD pagos                   |

---

## 🛠️ Tecnologías

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + CSS nativo + JavaScript vanilla
- **Base de datos**: MySQL
- **Bundler**: Vite
- **Tipografía**: Inter (Google Fonts)

---

## 📄 Licencia

Proyecto desarrollado para uso interno del Condominio **Parque Choroni**.
