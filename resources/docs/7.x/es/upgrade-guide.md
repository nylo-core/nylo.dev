# Upgrade Guide

---

<a name="section-1"></a>
- [Novedades en v7](#whats-new "Novedades en v7")
- [Resumen de cambios incompatibles](#breaking-changes "Resumen de cambios incompatibles")
- [Enfoque de migracion recomendado](#recommended-migration "Enfoque de migracion recomendado")
- [Lista de verificacion rapida de migracion](#checklist "Lista de verificacion rapida de migracion")
- [Guia de migracion paso a paso](#migration-guide "Guia de migracion")
  - [Paso 1: Actualizar dependencias](#step-1-dependencies "Actualizar dependencias")
  - [Paso 2: Configuracion de entorno](#step-2-environment "Configuracion de entorno")
  - [Paso 3: Actualizar main.dart](#step-3-main "Actualizar main.dart")
  - [Paso 4: Actualizar boot.dart](#step-4-boot "Actualizar boot.dart")
  - [Paso 5: Reorganizar archivos de configuracion](#step-5-config "Reorganizar archivos de configuracion")
  - [Paso 6: Actualizar AppProvider](#step-6-provider "Actualizar AppProvider")
  - [Paso 7: Actualizar configuracion de temas](#step-7-theme "Actualizar configuracion de temas")
  - [Paso 10: Migrar widgets](#step-10-widgets "Migrar widgets")
  - [Paso 11: Actualizar rutas de assets](#step-11-assets "Actualizar rutas de assets")
- [Funcionalidades eliminadas y alternativas](#removed-features "Funcionalidades eliminadas y alternativas")
- [Referencia de clases eliminadas](#deleted-classes "Referencia de clases eliminadas")
- [Referencia de migracion de widgets](#widget-reference "Referencia de migracion de widgets")
- [Solucion de problemas](#troubleshooting "Solucion de problemas")


<div id="whats-new"></div>

## Novedades en v7

{{ config('app.name') }} v7 es un lanzamiento mayor con mejoras significativas en la experiencia del desarrollador:

### Configuracion de entorno encriptada
- Las variables de entorno ahora se encriptan con XOR en tiempo de compilacion para mayor seguridad
- El nuevo `metro make:key` genera tu APP_KEY
- El nuevo `metro make:env` genera el `env.g.dart` encriptado
- Soporte para inyeccion de APP_KEY con `--dart-define` para pipelines CI/CD

### Proceso de arranque simplificado
- El nuevo patron `BootConfig` reemplaza los callbacks separados de setup/finished
- `Nylo.init()` mas limpio con parametro `env` para entorno encriptado
- Hooks de ciclo de vida de la aplicacion directamente en main.dart

### Nueva API nylo.configure()
- Un solo metodo consolida toda la configuracion de la aplicacion
- Sintaxis mas limpia reemplaza las llamadas individuales `nylo.add*()`
- Metodos de ciclo de vida separados `setup()` y `boot()` en los proveedores

### NyPage para paginas
- `NyPage` reemplaza `NyState` para widgets de pagina (sintaxis mas limpia)
- `view()` reemplaza el metodo `build()`
- El getter `get init =>` reemplaza los metodos `init()` y `boot()`
- `NyState` sigue disponible para widgets con estado que no son paginas

### Sistema LoadingStyle
- Nuevo enum `LoadingStyle` para estados de carga consistentes
- Opciones: `LoadingStyle.normal()`, `LoadingStyle.skeletonizer()`, `LoadingStyle.none()`
- Widgets de carga personalizados via `LoadingStyle.normal(child: ...)`

### Enrutamiento seguro con RouteView
- `static RouteView path` reemplaza `static const path`
- Definiciones de rutas con seguridad de tipos y factory de widgets

### Soporte multi-tema
- Registrar multiples temas claros y oscuros
- IDs de tema definidos en codigo en lugar del archivo `.env`
- Nuevo `NyThemeType.dark` / `NyThemeType.light` para clasificacion de temas
- API de tema preferido: `NyTheme.setPreferredDark()`, `NyTheme.setPreferredLight()`
- Enumeracion de temas: `NyTheme.lightThemes()`, `NyTheme.darkThemes()`, `NyTheme.all()`

### Nuevos comandos Metro
- `make:key` - Generar APP_KEY para encriptacion
- `make:env` - Generar archivo de entorno encriptado
- `make:bottom_sheet_modal` - Crear modales de hoja inferior
- `make:button` - Crear botones personalizados

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">Ver todos los cambios en GitHub</a>

<div id="breaking-changes"></div>

## Resumen de cambios incompatibles

| Cambio | v6 | v7 |
|--------|-----|-----|
| Widget raiz de la app | `LocalizedApp(child: Main(nylo))` | `Main(nylo)` (usa `NyApp.materialApp()`) |
| Clase de estado de pagina | `NyState` | `NyPage` para paginas |
| Metodo de vista | `build()` | `view()` |
| Metodo de inicializacion | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| Ruta | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| Boot del proveedor | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| Configuracion | Llamadas individuales `nylo.add*()` | Una sola llamada `nylo.configure()` |
| IDs de tema | Archivo `.env` (`LIGHT_THEME_ID`, `DARK_THEME_ID`) | Codigo (`type: NyThemeType.dark`) |
| Widget de carga | `useSkeletonizer` + `loading()` | Getter `LoadingStyle` |
| Ubicacion de config | `lib/config/` | `lib/bootstrap/` (decoders, events, providers, theme) |
| Ubicacion de assets | `public/` | `assets/` |

<div id="recommended-migration"></div>

## Enfoque de migracion recomendado

Para proyectos mas grandes, recomendamos crear un proyecto v7 nuevo y migrar los archivos:

1. Crear nuevo proyecto v7: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. Copiar tus paginas, controladores, modelos y servicios
3. Actualizar la sintaxis como se muestra arriba
4. Probar exhaustivamente

Esto asegura que tengas toda la estructura y configuraciones mas recientes del boilerplate.

Si te interesa ver un diff de los cambios entre v6 y v7, puedes ver la comparacion en GitHub: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## Lista de verificacion rapida de migracion

Usa esta lista de verificacion para seguir tu progreso de migracion:

- [ ] Actualizar `pubspec.yaml` (Dart >=3.10.7, Flutter >=3.24.0, nylo_framework: ^7.0.0)
- [ ] Ejecutar `flutter pub get`
- [ ] Ejecutar `metro make:key` para generar APP_KEY
- [ ] Ejecutar `metro make:env` para generar el entorno encriptado
- [ ] Actualizar `main.dart` con el parametro env y BootConfig
- [ ] Convertir la clase `Boot` para usar el patron `BootConfig`
- [ ] Mover archivos de configuracion de `lib/config/` a `lib/bootstrap/`
- [ ] Crear nuevos archivos de configuracion (`lib/config/app.dart`, `lib/config/storage_keys.dart`, `lib/config/toast_notification.dart`)
- [ ] Actualizar `AppProvider` para usar `nylo.configure()`
- [ ] Eliminar `LIGHT_THEME_ID` y `DARK_THEME_ID` de `.env`
- [ ] Agregar `type: NyThemeType.dark` a las configuraciones de tema oscuro
- [ ] Renombrar `NyState` a `NyPage` para todos los widgets de pagina
- [ ] Cambiar `build()` a `view()` en todas las paginas
- [ ] Cambiar `init()/boot()` a `get init =>` en todas las paginas
- [ ] Actualizar `static const path` a `static RouteView path`
- [ ] Cambiar `router.route()` a `router.add()` en las rutas
- [ ] Renombrar widgets (NyListView -> CollectionView, etc.)
- [ ] Mover assets de `public/` a `assets/`
- [ ] Actualizar rutas de assets en `pubspec.yaml`
- [ ] Eliminar importaciones de Firebase (si se usan - agregar paquetes directamente)
- [ ] Eliminar uso de NyDevPanel (usar Flutter DevTools)
- [ ] Ejecutar `flutter pub get` y probar

<div id="migration-guide"></div>

## Guia de migracion paso a paso

<div id="step-1-dependencies"></div>

### Paso 1: Actualizar dependencias

Actualiza tu `pubspec.yaml`:

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... other dependencies
```

Ejecuta `flutter pub get` para actualizar los paquetes.

<div id="step-2-environment"></div>

### Paso 2: Configuracion de entorno

v7 requiere variables de entorno encriptadas para mayor seguridad.

**1. Generar APP_KEY:**

``` bash
metro make:key
```

Esto agrega `APP_KEY` a tu archivo `.env`.

**2. Generar env.g.dart encriptado:**

``` bash
metro make:env
```

Esto crea `lib/bootstrap/env.g.dart` conteniendo tus variables de entorno encriptadas.

**3. Eliminar variables de tema obsoletas de .env:**

``` bash
# Remove these lines from your .env file:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### Paso 3: Actualizar main.dart

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // Optional: Add app lifecycle hooks
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**Cambios clave:**
- Importar el `env.g.dart` generado
- Pasar `Env.get` al parametro `env`
- `Boot.nylo` ahora es `Boot.nylo()` (retorna `BootConfig`)
- `setupFinished` se elimina (se maneja dentro de `BootConfig`)
- Hooks opcionales de `appLifecycle` para cambios de estado de la aplicacion

<div id="step-4-boot"></div>

### Paso 4: Actualizar boot.dart

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**Cambios clave:**
- Retorna `BootConfig` en lugar de `Future<Nylo>`
- `setup` y `finished` combinados en un solo objeto `BootConfig`
- `getEnv('SHOW_SPLASH_SCREEN')` -> `AppConfig.showSplashScreen`
- `bootApplication` -> `setupApplication`

<div id="step-5-config"></div>

### Paso 5: Reorganizar archivos de configuracion

v7 reorganiza los archivos de configuracion para una mejor estructura:

| Ubicacion v6 | Ubicacion v7 | Accion |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | Mover |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | Mover |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | Mover |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | Mover |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | Renombrar y refactorizar |
| (nuevo) | `lib/config/app.dart` | Crear |
| (nuevo) | `lib/config/toast_notification.dart` | Crear |

**Crear lib/config/app.dart:**

Referencia: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // The name of the application.
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // The version of the application.
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // Add other app configuration here
}
```

**Crear lib/config/storage_keys.dart:**

Referencia: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // Define the keys you want to be synced on boot
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // give the user 10 coins by default
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// Add your storage keys here...
}
```

**Crear lib/config/toast_notification.dart:**

Referencia: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // Customize toast styles here
  };
}
```

<div id="step-6-provider"></div>

### Paso 6: Actualizar AppProvider

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**Cambios clave:**
- `boot()` ahora es `setup()` para la configuracion inicial
- `boot()` ahora se usa para logica posterior al setup (anteriormente `afterBoot`)
- Todas las llamadas `nylo.add*()` consolidadas en un solo `nylo.configure()`
- La localizacion usa el objeto `NyLocalizationConfig`

<div id="step-7-theme"></div>

### Paso 7: Actualizar configuracion de temas

**v6 (archivo .env):**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6 (theme.dart):**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7 (theme.dart):**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**Cambios clave:**
- Eliminar `LIGHT_THEME_ID` y `DARK_THEME_ID` de `.env`
- Definir IDs de tema directamente en el codigo
- Agregar `type: NyThemeType.dark` a todas las configuraciones de tema oscuro
- Los temas claros tienen por defecto `NyThemeType.light`

**Nuevos metodos de API de temas (v7):**
``` dart
// Set and remember preferred theme
NyTheme.set(context, id: 'dark_theme', remember: true);

// Set preferred themes for system following
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// Get preferred theme IDs
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// Theme enumeration
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// Clear saved preferences
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### Paso 10: Migrar widgets

#### NyListView -> CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// With pagination (pull to refresh):
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder -> FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField -> InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText -> StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher -> LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### Paso 11: Actualizar rutas de assets

v7 cambia el directorio de assets de `public/` a `assets/`:

**1. Mover tus carpetas de assets:**
``` bash
# Move directories
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. Actualizar pubspec.yaml:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. Actualizar cualquier referencia de assets en el codigo:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### Widget LocalizedApp - Eliminado

Referencia: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Migracion:** Usa `Main(nylo)` directamente. `NyApp.materialApp()` maneja la localizacion internamente.

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## Referencia de clases eliminadas

| Clase eliminada | Alternativa |
|---------------|-------------|
| `NyTextStyle` | Usar `TextStyle` de Flutter directamente |
| `NyBaseApiService` | Usar `DioApiService` |
| `BaseColorStyles` | Usar `ThemeColor` |
| `LocalizedApp` | Usar `Main(nylo)` directamente |
| `NyException` | Usar excepciones estandar de Dart |
| `PushNotification` | Usar `flutter_local_notifications` directamente |
| `PushNotificationAttachments` | Usar `flutter_local_notifications` directamente |

<div id="widget-reference"></div>

## Referencia de migracion de widgets

### Widgets renombrados

| Widget v6 | Widget v7 | Notas |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | Nueva API con `builder` en lugar de `child` |
| `NyFutureBuilder` | `FutureWidget` | Widget asincrono simplificado |
| `NyTextField` | `InputField` | Usa `FormValidator` |
| `NyLanguageSwitcher` | `LanguageSwitcher` | Misma API |
| `NyRichText` | `StyledText` | Misma API |
| `NyFader` | `FadeOverlay` | Misma API |

### Widgets eliminados (sin reemplazo directo)

| Widget eliminado | Alternativa |
|----------------|-------------|
| `NyPullToRefresh` | Usar `CollectionView.pullable()` |

### Ejemplos de migracion de widgets

**NyPullToRefresh -> CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader -> AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## Solucion de problemas

### "Env.get not found" o "Env is not defined"

**Solucion:** Ejecuta los comandos de generacion de entorno:
``` bash
metro make:key
metro make:env
```
Luego importa el archivo generado en `main.dart`:
``` dart
import '/bootstrap/env.g.dart';
```

### "Theme not applying" o "Dark theme not working"

**Solucion:** Asegurate de que los temas oscuros tengan `type: NyThemeType.dark`:
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // Add this line
),
```

### "LocalizedApp not found"

Referencia: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**Solucion:** `LocalizedApp` ha sido eliminado. Cambia:
``` dart
// From:
runApp(LocalizedApp(child: Main(nylo)));

// To:
runApp(Main(nylo));
```

### "router.route is not defined"

**Solucion:** Usa `router.add()` en su lugar:
``` dart
// From:
router.route(HomePage.path, (context) => HomePage());

// To:
router.add(HomePage.path);
```

### "NyListView not found"

**Solucion:** `NyListView` ahora es `CollectionView`:
``` dart
// From:
NyListView(...)

// To:
CollectionView<MyModel>(...)
```

### Assets no cargan (imagenes, fuentes)

**Solucion:** Actualiza las rutas de assets de `public/` a `assets/`:
1. Mover archivos: `mv public/* assets/`
2. Actualizar rutas en `pubspec.yaml`
3. Actualizar referencias en el codigo

### "init() must return a value of type Future"

**Solucion:** Cambia a la sintaxis getter:
``` dart
// From:
@override
init() async { ... }

// To:
@override
get init => () async { ... };
```

---

**Necesitas ayuda?** Revisa la [Documentacion de Nylo](https://nylo.dev/docs/7.x) o abre un issue en [GitHub](https://github.com/nylo-core/nylo/issues).
