# Requirements

---

<a name="section-1"></a>
- [Requisitos del Sistema](#system-requirements "Requisitos del Sistema")
- [Instalar Flutter](#installing-flutter "Instalar Flutter")
- [Verificar tu Instalacion](#verifying-installation "Verificar tu Instalacion")
- [Configurar un Editor](#set-up-an-editor "Configurar un Editor")
  - [Plugins de editor de Nylo](#nylo-editor-plugins "Plugins de editor de Nylo")


<div id="system-requirements"></div>

## Requisitos del Sistema

<x-doc-strip label="De un vistazo" items="Flutter 3.38.4+, Dart SDK 3.10.7+, Todas las plataformas de Flutter" linkText="Instalar Flutter" linkHref="https://docs.flutter.dev/get-started/install" />

{{ config('app.name') }} v7 requiere las siguientes versiones minimas:

| Requisito | Version Minima |
|-------------|-----------------|
| **Flutter** | 3.38.4 o superior |
| **Dart SDK** | 3.10.7 o superior |

### Soporte de Plataformas

{{ config('app.name') }} soporta todas las plataformas que Flutter soporta:

| Plataforma | Soporte |
|----------|---------|
| iOS | ✓ Soporte completo |
| Android | ✓ Soporte completo |
| Web | ✓ Soporte completo |
| macOS | ✓ Soporte completo |
| Windows | ✓ Soporte completo |
| Linux | ✓ Soporte completo |

<div id="installing-flutter"></div>

## Instalar Flutter

Si no tienes Flutter instalado, sigue la guia oficial de instalacion para tu sistema operativo:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Guia de Instalacion de Flutter</a>

<div id="verifying-installation"></div>

## Verificar tu Instalacion

Despues de instalar Flutter, verifica tu configuracion:

### Comprobar la Version de Flutter

``` bash
flutter --version
```

Deberias ver una salida similar a:

```
Flutter 3.38.4 • channel stable
Dart SDK version: 3.10.7
```

### Actualizar Flutter (si es necesario)

Si tu version de Flutter es inferior a 3.38.4, actualiza a la ultima version estable:

``` bash
flutter channel stable
flutter upgrade
```

### Ejecutar Flutter Doctor

Verifica que tu entorno de desarrollo este correctamente configurado:

``` bash
flutter doctor -v
```

Este comando comprueba:
- Instalacion del SDK de Flutter
- Herramientas de Android (para desarrollo Android)
- Xcode (para desarrollo iOS/macOS)
- Dispositivos conectados
- Plugins del IDE

Soluciona cualquier problema reportado antes de proceder con la instalacion de {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Configurar un Editor

Elige un IDE con soporte para Flutter:

### Visual Studio Code (Recomendado)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> es ligero y tiene un excelente soporte para Flutter.

1. Instala <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Instala la <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">extension de Flutter</a>
3. Instala la <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">extension de Dart</a>

Guia de configuracion: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Configuracion de Flutter en VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> proporciona un IDE completo con soporte de emulador integrado.

1. Instala <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Instala el plugin de Flutter (Preferencias → Plugins → Flutter)
3. Instala el plugin de Dart

Guia de configuracion: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Configuracion de Flutter en Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community o Ultimate) tambien soporta el desarrollo con Flutter.

1. Instala IntelliJ IDEA
2. Instala el plugin de Flutter (Preferencias → Plugins → Flutter)
3. Instala el plugin de Dart

<div id="nylo-editor-plugins"></div>

### Plugins de editor de Nylo

{{ config('app.name') }} ofrece sus propios plugins para VS Code y los IDE de JetBrains:

| Editor | Plugin |
|--------|--------|
| VS Code | <a href="https://marketplace.visualstudio.com/items?itemName=Nylo.vscode-nylo" target="_BLANK">Nylo for VS Code</a> |
| Android Studio, IntelliJ IDEA | <a href="https://plugins.jetbrains.com/plugin/33988-nylo" target="_BLANK">Nylo</a> |

El plugin agrega una configuracion de ejecucion por cada archivo `.env` de tu proyecto, que lleva el nombre del archivo: `.env` se convierte en **Default** y `.env.staging` en **Staging**. Iniciar una de ellas ejecuta `metro make:env` con ese archivo antes de que arranque la aplicacion, asi que no tienes que regenerar manualmente tu configuracion de entorno cuando cambias de entorno. La lista se actualiza cuando agregas o eliminas archivos `.env`.

En VS Code, elige un entorno en el menu desplegable **Run and Debug**, o ejecuta **Nylo: Run Environment…** desde la paleta de comandos. En Android Studio e IntelliJ IDEA, eligelo en el menu desplegable de configuraciones de ejecucion.

Una vez que tu editor este configurado, estas listo para [instalar {{ config('app.name') }}](/docs/7.x/installation).
