# Configuration

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a la configuracion")
- Entorno
  - [El archivo .env](#env-file "El archivo .env")
  - [Generar configuracion de entorno](#generating-env "Generar configuracion de entorno")
  - [Recuperar valores](#retrieving-values "Recuperar valores del entorno")
  - [Crear clases de configuracion](#creating-config-classes "Crear clases de configuracion")
  - [Tipos de variables](#variable-types "Tipos de variables de entorno")
- [Variantes de entorno](#environment-flavours "Variantes de entorno")
- [Inyeccion en tiempo de compilacion](#build-time-injection "Inyeccion en tiempo de compilacion")


<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} v7 utiliza un sistema de configuracion de entorno seguro. Tus variables de entorno se almacenan en un archivo `.env` y luego se cifran en un archivo Dart generado (`env.g.dart`) para su uso en tu aplicacion.

Este enfoque proporciona:
- **Seguridad**: Los valores del entorno se cifran con XOR en la aplicacion compilada
- **Seguridad de tipos**: Los valores se analizan automaticamente a los tipos apropiados
- **Flexibilidad en tiempo de compilacion**: Diferentes configuraciones para desarrollo, staging y produccion

<div id="env-file"></div>

## El archivo .env

El archivo `.env` en la raiz de tu proyecto contiene tus variables de configuracion:

``` bash
# Environment configuration
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Variables disponibles

| Variable | Descripcion |
|----------|-------------|
| `APP_KEY` | **Requerido**. Clave secreta de 32 caracteres para cifrado |
| `APP_NAME` | Nombre de tu aplicacion |
| `APP_ENV` | Entorno: `developing` o `production` |
| `APP_DEBUG` | Habilitar modo de depuracion (`true`/`false`) |
| `APP_URL` | URL de tu aplicacion |
| `API_BASE_URL` | URL base para solicitudes API |
| `ASSET_PATH` | Ruta a la carpeta de recursos |
| `DEFAULT_LOCALE` | Codigo de idioma predeterminado |

<div id="generating-env"></div>

## Generar configuracion de entorno

{{ config('app.name') }} v7 requiere que generes un archivo de entorno cifrado antes de que tu aplicacion pueda acceder a los valores de entorno.

### Paso 1: Generar un APP_KEY

Primero, genera un APP_KEY seguro:

``` bash
metro make:key
```

Esto agrega un `APP_KEY` de 32 caracteres a tu archivo `.env`.

### Paso 2: Generar env.g.dart

Genera el archivo de entorno cifrado:

``` bash
metro make:env
```

Esto crea `lib/bootstrap/env.g.dart` con tus variables de entorno cifradas.

Tu entorno se registra automaticamente cuando tu aplicacion se inicia -- `Nylo.init(env: Env.get, ...)` en `main.dart` se encarga de esto. No se necesita configuracion adicional.

### Regenerar despues de cambios

Cuando modifiques tu archivo `.env`, regenera la configuracion:

``` bash
metro make:env --force
```

La bandera `--force` sobrescribe el `env.g.dart` existente.

<div id="retrieving-values"></div>

## Recuperar valores

La forma recomendada de acceder a los valores del entorno es a traves de **clases de configuracion**. Tu archivo `lib/config/app.dart` usa `getEnv()` para exponer los valores del entorno como campos estaticos con tipo:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Luego en el codigo de tu aplicacion, accede a los valores a traves de la clase de configuracion:

``` dart
// Anywhere in your app
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Este patron mantiene el acceso al entorno centralizado en tus clases de configuracion. El helper `getEnv()` debe usarse dentro de las clases de configuracion en lugar de directamente en el codigo de la aplicacion.

<div id="creating-config-classes"></div>

## Crear clases de configuracion

Puedes crear clases de configuracion personalizadas para servicios de terceros o configuracion especifica de funcionalidades usando Metro:

``` bash
metro make:config RevenueCat
```

Esto crea un nuevo archivo de configuracion en `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Ejemplo: Configuracion de RevenueCat

**Paso 1:** Agrega las variables de entorno a tu archivo `.env`:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Paso 2:** Actualiza tu clase de configuracion para referenciar estos valores:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Paso 3:** Regenera tu configuracion de entorno:

``` bash
metro make:env --force
```

**Paso 4:** Usa la clase de configuracion en tu aplicacion:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Este enfoque mantiene tus claves API y valores de configuracion seguros y centralizados, facilitando la gestion de diferentes valores entre entornos.

<div id="variable-types"></div>

## Tipos de variables

Los valores en tu archivo `.env` se analizan automaticamente:

| Valor .env | Tipo Dart | Ejemplo |
|------------|-----------|---------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (cadena vacia) |


<div id="environment-flavours"></div>

## Variantes de entorno

Crea diferentes configuraciones para desarrollo, staging y produccion.

### Paso 1: Crear archivos de entorno

Crea archivos `.env` separados:

``` bash
.env                  # Development (default)
.env.staging          # Staging
.env.production       # Production
```

Ejemplo `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Paso 2: Generar configuracion de entorno

Genera desde un archivo de entorno especifico:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Paso 3: Compilar tu aplicacion

Compila con la configuracion apropiada:

``` bash
# Development
flutter run

# Production build
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Inyeccion en tiempo de compilacion

Para mayor seguridad, puedes inyectar el APP_KEY en tiempo de compilacion en lugar de incrustarlo en el codigo fuente.

### Generar con modo --dart-define

``` bash
metro make:env --dart-define
```

Esto genera `env.g.dart` sin incrustar el APP_KEY.

### Compilar con inyeccion de APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Este enfoque mantiene el APP_KEY fuera de tu codigo fuente, lo cual es util para:
- Pipelines CI/CD donde se inyectan secretos
- Proyectos de codigo abierto
- Requisitos de seguridad mejorada

### Mejores practicas

1. **Nunca hagas commit de `.env` al control de versiones** -- Agregalo a `.gitignore`
2. **Usa `.env-example`** -- Haz commit de una plantilla sin valores sensibles
3. **Regenera despues de cambios** -- Siempre ejecuta `metro make:env --force` despues de modificar `.env`
4. **Diferentes claves por entorno** -- Usa APP_KEYs unicos para desarrollo, staging y produccion
