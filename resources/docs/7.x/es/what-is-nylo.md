# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- Desarrollo de Aplicaciones
    - [Nuevo en Flutter?](#new-to-flutter "Nuevo en Flutter?")
    - [Mantenimiento y Calendario de Lanzamientos](#maintenance-and-release-schedule "Mantenimiento y Calendario de Lanzamientos")
- Creditos
    - [Dependencias del Framework](#framework-dependencies "Dependencias del Framework")
    - [Colaboradores](#contributors "Colaboradores")


<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} es un micro-framework para Flutter disenado para ayudar a simplificar el desarrollo de aplicaciones. Proporciona un boilerplate estructurado con elementos esenciales preconfigurados para que puedas concentrarte en construir las funcionalidades de tu aplicacion en lugar de configurar la infraestructura.

De forma predeterminada, {{ config('app.name') }} incluye:

- **Enrutamiento** - Gestion de rutas simple y declarativa con guards y deep linking
- **Red** - Servicios API con Dio, interceptores y transformacion de respuestas
- **Gestion de Estado** - Estado reactivo con NyState y actualizaciones de estado globales
- **Localizacion** - Soporte multiidioma con archivos de traduccion JSON
- **Temas** - Modo claro/oscuro con cambio de tema
- **Almacenamiento Local** - Almacenamiento seguro con Backpack y NyStorage
- **Formularios** - Manejo de formularios con validacion y tipos de campos
- **Notificaciones Push** - Soporte de notificaciones locales y remotas
- **Herramienta CLI (Metro)** - Genera paginas, controladores, modelos y mas

<div id="new-to-flutter"></div>

## Nuevo en Flutter?

Si eres nuevo en Flutter, comienza con los recursos oficiales:

- <a href="https://flutter.dev" target="_BLANK">Documentacion de Flutter</a> - Guias completas y referencia de API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Canal de YouTube de Flutter</a> - Tutoriales y novedades
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Recetas practicas para tareas comunes

Una vez que te sientas comodo con los fundamentos de Flutter, {{ config('app.name') }} te resultara intuitivo ya que se basa en patrones estandar de Flutter.


<div id="maintenance-and-release-schedule"></div>

## Mantenimiento y Calendario de Lanzamientos

{{ config('app.name') }} sigue el <a href="https://semver.org" target="_BLANK">Versionado Semantico</a>:

- **Lanzamientos mayores** (7.x → 8.x) - Una vez al ano para cambios incompatibles
- **Lanzamientos menores** (7.0 → 7.1) - Nuevas funcionalidades, compatibles hacia atras
- **Lanzamientos de parche** (7.0.0 → 7.0.1) - Correcciones de errores y mejoras menores

Las correcciones de errores y parches de seguridad se manejan de forma inmediata a traves de los repositorios de GitHub.


<div id="framework-dependencies"></div>

## Dependencias del Framework

{{ config('app.name') }} v7 esta construido sobre estos paquetes de codigo abierto:

### Dependencias Principales

| Paquete | Proposito |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | Cliente HTTP para solicitudes API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Almacenamiento local seguro |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internacionalizacion y formato |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Extensiones reactivas para streams |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Igualdad de valores para objetos |

### UI y Widgets

| Paquete | Proposito |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Efectos de carga skeleton |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Notificaciones toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Funcionalidad pull-to-refresh |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Layouts de cuadricula escalonada |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Campos de selector de fecha |

### Notificaciones y Conectividad

| Paquete | Proposito |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Notificaciones push locales |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Estado de conectividad de red |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Insignias del icono de la aplicacion |

### Utilidades

| Paquete | Proposito |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Abrir URLs y aplicaciones |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Conversion de formato de cadenas |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Generacion de UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Rutas del sistema de archivos |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Enmascaramiento de entrada |


<div id="contributors"></div>

## Colaboradores

Gracias a todos los que han contribuido a {{ config('app.name') }}! Si has contribuido, comunicate a traves de <a href="mailto:support@nylo.dev">support@nylo.dev</a> para ser agregado aqui.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Creador)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
