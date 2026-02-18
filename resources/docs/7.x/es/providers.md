# Providers

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Crear un proveedor](#create-a-provider "Crear un proveedor")
- [Objeto proveedor](#provider-object "Objeto proveedor")


<div id="introduction"></div>

## Introduccion a los proveedores

En {{ config('app.name') }}, los proveedores se inicializan desde tu archivo <b>main.dart</b> cuando tu aplicacion se ejecuta. Todos tus proveedores residen en `/lib/app/providers/*`, puedes modificar estos archivos o crear tus proveedores usando <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Los proveedores se pueden usar cuando necesitas inicializar una clase, paquete o crear algo antes de que la aplicacion se cargue inicialmente. Es decir, la clase `route_provider.dart` es responsable de agregar todas las rutas a {{ config('app.name') }}.

### Profundizacion

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Ciclo de vida

- `Boot.{{ config('app.name') }}` recorrera tus proveedores registrados dentro del archivo <b>config/providers.dart</b> y los inicializara.

- `Boot.Finished` se llama inmediatamente despues de que **"Boot.{{ config('app.name') }}"** haya terminado, este metodo vinculara la instancia de {{ config('app.name') }} a `Backpack` con el valor 'nylo'.

Ej. Backpack.instance.read('nylo'); // instancia de {{ config('app.name') }}


<div id="create-a-provider"></div>

## Crear un nuevo proveedor

Puedes crear nuevos proveedores ejecutando el siguiente comando en la terminal.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Objeto proveedor

Tu proveedor tendra dos metodos, `setup(Nylo nylo)` y `boot(Nylo nylo)`.

Cuando la aplicacion se ejecuta por primera vez, cualquier codigo dentro de tu metodo **setup** se ejecutara primero. Tambien puedes manipular el objeto `Nylo` como en el ejemplo a continuacion.

Ejemplo: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Ciclo de vida

1. `setup(Nylo nylo)` - Inicializar tu proveedor. Devuelve la instancia de `Nylo` o `null`.
2. `boot(Nylo nylo)` - Se llama despues de que todos los proveedores hayan terminado su configuracion. Usa esto para inicializacion que depende de que otros proveedores esten listos.

> Dentro del metodo `setup`, debes **retornar** una instancia de `Nylo` o `null` como en el ejemplo anterior.
