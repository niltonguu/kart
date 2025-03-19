/index.php
/login.php
/logout.php
/config/
/api/
/view/
/controllers/
/helpers/
/public/
/routes/
/tests/


## Detalles por Carpeta

### /config/
/config/
├── database.php # Configuración de la base de datos
├── config.php # Configuración general del sistema
├── env.php # Variables de entorno (claves, rutas, etc.)


### /api/
/api/
├── /preparador/
│ ├── salidasApi.php # API para salidas del preparador
│ ├── 5ultimasApi.php # API para las 5 últimas
│ ├── 5mejoresApi.php # API para las 5 mejores
│ ├── tipsApi.php # API para tips
├── /piloto/
│ ├── salidasApi.php
│ ├── 5ultimasApi.php
│ ├── 5mejoresApi.php
│ ├── tipsApi.php
├── /mecanico/
│ ├── salidasApi.php


### /view/
/view/
├── /layouts/
│ ├── dashboard.php # Layout principal del dashboard
│ ├── preparador.php # Layout principal del módulo preparador
│ ├── mecanico.php # Layout principal del módulo mecánico
│ ├── piloto.php # Layout principal del módulo piloto
├── /dashboard/
│ ├── /usuarios/
│ │ ├── usuariosJS.php
│ │ ├── usuariosMD.php
│ │ ├── usuariosV.php
│ ├── /circuitos/
│ │ ├── circuitosJS.php
│ │ ├── circuitosMD.php
│ │ ├── circuitosV.php
│ ├── /trazados/
│ │ ├── trazadosJS.php
│ │ ├── trazadosMD.php
│ │ ├── trazadosV.php
├── /preparador/
│ ├── /salidas/
│ │ ├── salidasJS.php
│ │ ├── salidasMD.php
│ │ ├── salidasV.php
│ ├── /5ultimas/
│ ├── /5mejores/
│ ├── /tips/
├── /piloto/
│ ├── /salidas/
│ ├── /5ultimas/
│ ├── /5mejores/
│ ├── /tips/
├── /mecanico/
│ ├── /salidas/


### /controllers/
/controllers/
├── BaseController.php # Controlador base con métodos comunes
├── /dashboard/
│ ├── dash_usuariosController.php
│ ├── dash_circuitosController.php
│ ├── dash_trazadosController.php
├── /preparador/
│ ├── pre_salidasController.php
│ ├── pre_5ultimasController.php
│ ├── pre_5mejoresController.php
│ ├── pre_tipsController.php
├── /piloto/
├── /mecanico/
├── authController.php # Controlador para autenticación


### /helpers/
/helpers/
├── exportHelper.php # Funciones para exportar a Excel y PDF
├── datatableHelper.php # Configuración y manejo de DataTables
├── authHelper.php # Funciones para autenticación y sesiones


### /public/
/public/
├── /css/ # Archivos CSS
├── /js/ # Archivos JS
├── /images/ # Imágenes y assets


### /routes/
/routes/
├── web.php # Rutas principales de la aplicación
├── api.php # Rutas para las APIs


### /tests/
/tests/
├── /unit/ # Pruebas unitarias
├── /functional/ # Pruebas funcionales
├── /integration/ # Pruebas de integración


## Explicación de Componentes

### Convenciones de Nombres
- **Controladores**: `modulo_funcionalidadController.php`
- **Vistas**: `funcionalidadV.php`
- **JavaScript**: `funcionalidadJS.php`
- **Modales**: `funcionalidadMD.php`
- **APIs**: `funcionalidadApi.php`

### Estructura de Módulos
Cada módulo (dashboard, preparador, piloto, mecánico) sigue una estructura consistente:
- **Vista (V)**: Contiene la interfaz de usuario
- **JavaScript (JS)**: Maneja la lógica del cliente
- **Modales (MD)**: Define las ventanas modales
- **Controlador**: Maneja la lógica del servidor

### Características Principales
1. **Modularidad**: Organización por módulos funcionales
2. **Separación de Responsabilidades**: MVC claro
3. **Escalabilidad**: Fácil de expandir
4. **Mantenibilidad**: Estructura clara y consistente
5. **Seguridad**: Separación de archivos públicos y privados

### Notas de Implementación
- Los archivos en `/api/` manejan peticiones AJAX y DataTables
- Los helpers contienen funciones reutilizables
- El sistema usa autenticación centralizada
- Incluye soporte para exportación a Excel y PDF
- Implementa DataTables en español