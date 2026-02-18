# Requirements

---

<a name="section-1"></a>
- [Requisitos del Sistema](#system-requirements "Requisitos del Sistema")
- [Instalar Flutter](#installing-flutter "Instalar Flutter")
- [Verificar tu Instalacion](#verifying-installation "Verificar tu Instalacion")
- [Configurar un Editor](#set-up-an-editor "Configurar un Editor")


<div id="system-requirements"></div>

## Requisitos del Sistema

{{ config('app.name') }} v7 requiere las siguientes versiones minimas:

| Requisito | Version Minima |
|-------------|-----------------|
| **Flutter** | 3.24.0 o superior |
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
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Actualizar Flutter (si es necesario)

Si tu version de Flutter es inferior a 3.24.0, actualiza a la ultima version estable:

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

Una vez que tu editor este configurado, estas listo para [instalar {{ config('app.name') }}](/docs/7.x/installation).
