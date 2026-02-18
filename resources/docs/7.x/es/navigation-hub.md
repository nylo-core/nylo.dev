# Navigation Hub

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Uso básico](#basic-usage "Uso básico")
  - [Crear un Navigation Hub](#creating-a-navigation-hub "Crear un Navigation Hub")
  - [Crear pestañas de navegación](#creating-navigation-tabs "Crear pestañas de navegación")
  - [Navegación inferior](#bottom-navigation "Navegación inferior")
    - [Constructor personalizado de barra de navegación](#custom-nav-bar-builder "Constructor personalizado de barra de navegación")
  - [Navegación superior](#top-navigation "Navegación superior")
  - [Navegación de recorrido](#journey-navigation "Navegación de recorrido")
    - [Estilos de progreso](#journey-progress-styles "Estilos de progreso de recorrido")
    - [JourneyState](#journey-state "JourneyState")
    - [Métodos helper de JourneyState](#journey-state-helper-methods "Métodos helper de JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Navegar dentro de una pestaña](#navigating-within-a-tab "Navegar dentro de una pestaña")
- [Pestañas](#tabs "Pestañas")
  - [Agregar insignias a pestañas](#adding-badges-to-tabs "Agregar insignias a pestañas")
  - [Agregar alertas a pestañas](#adding-alerts-to-tabs "Agregar alertas a pestañas")
- [Índice inicial](#initial-index "Índice inicial")
- [Mantener estado](#maintaining-state "Mantener estado")
- [onTap](#on-tap "onTap")
- [Acciones de estado](#state-actions "Acciones de estado")
- [Estilo de carga](#loading-style "Estilo de carga")

<div id="introduction"></div>

## Introducción

Los Navigation Hubs son un lugar central donde puedes **gestionar** la navegación de todos tus widgets.
De forma predeterminada puedes crear diseños de navegación inferior, superior y de recorrido en segundos.

**Imagina** que tienes una aplicación y quieres agregar una barra de navegación inferior y permitir que los usuarios naveguen entre diferentes pestañas en tu aplicación.

Puedes usar un Navigation Hub para construir esto.

Veamos cómo puedes usar un Navigation Hub en tu aplicación.

<div id="basic-usage"></div>

## Uso básico

Puedes crear un Navigation Hub usando el siguiente comando.

``` bash
metro make:navigation_hub base
```

El comando te guiará a través de una configuración interactiva:

1. **Elige un tipo de diseño** - Selecciona entre `navigation_tabs` (navegación inferior) o `journey_states` (flujo secuencial).
2. **Ingresa nombres de pestañas/estados** - Proporciona nombres separados por comas para tus pestañas o estados de recorrido.

Esto creará archivos en tu directorio `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` - El widget principal del hub
- `tabs/` o `states/` - Contiene los widgets hijos para cada pestaña o estado de recorrido

Así se ve un Navigation Hub generado:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Puedes ver que el Navigation Hub tiene **dos** pestañas, Home y Settings.

El método `layout` devuelve el tipo de diseño del hub. Recibe un `BuildContext` para que puedas acceder a datos del tema y media queries al configurar tu diseño.

Puedes crear más pestañas agregando `NavigationTab`'s al Navigation Hub.

Primero, necesitas crear un nuevo widget usando Metro.

``` bash
metro make:stateful_widget news_tab
```

También puedes crear múltiples widgets a la vez.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Luego, puedes agregar el nuevo widget al Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Para usar el Navigation Hub, agrégalo a tu router como la ruta inicial:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// o navega al Navigation Hub desde cualquier lugar de tu aplicación

routeTo(BaseNavigationHub.path);
```

Hay mucho **más** que puedes hacer con un Navigation Hub, veamos algunas de las funcionalidades.

<div id="bottom-navigation"></div>

### Navegación inferior

Puedes establecer el diseño como una barra de navegación inferior devolviendo `NavigationHubLayout.bottomNav` desde el método `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Puedes personalizar la barra de navegación inferior configurando propiedades como las siguientes:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

Puedes aplicar un estilo predefinido a tu barra de navegación inferior usando el parámetro `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Estilo material predeterminado de Flutter
);
```

<div id="custom-nav-bar-builder"></div>

### Constructor personalizado de barra de navegación

Para tener control completo sobre tu barra de navegación, puedes usar el parámetro `navBarBuilder`.

Esto te permite construir cualquier widget personalizado mientras recibes los datos de navegación.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

El objeto `NavBarData` contiene:

| Propiedad | Tipo | Descripción |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Los elementos de la barra de navegación |
| `currentIndex` | `int` | El índice actualmente seleccionado |
| `onTap` | `ValueChanged<int>` | Callback cuando se toca una pestaña |

Aquí tienes un ejemplo de una barra de navegación personalizada con efecto de vidrio:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Nota:** Cuando usas `navBarBuilder`, el parámetro `style` se ignora.

<div id="top-navigation"></div>

### Navegación superior

Puedes cambiar el diseño a una barra de navegación superior devolviendo `NavigationHubLayout.topNav` desde el método `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Puedes personalizar la barra de navegación superior configurando propiedades como las siguientes:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Navegación de recorrido

Puedes cambiar el diseño a una navegación de recorrido devolviendo `NavigationHubLayout.journey` desde el método `layout`.

Esto es ideal para flujos de onboarding o formularios de múltiples pasos.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

También puedes establecer un `backgroundGradient` para el diseño de recorrido:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Nota:** Cuando se establece `backgroundGradient`, tiene prioridad sobre `backgroundColor`.

Si quieres usar el diseño de navegación de recorrido, tus **widgets** deben usar `JourneyState` ya que contiene muchos métodos helper para gestionar el recorrido.

Puedes crear el recorrido completo usando el comando `make:navigation_hub` con el diseño `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Selecciona: journey_states
# Ingresa: welcome, personal_info, add_photos
```

Esto creará el hub y todos los widgets de estado de recorrido en `resources/pages/navigation_hubs/onboarding/states/`.

O puedes crear widgets de recorrido individuales usando:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Luego puedes agregar los nuevos widgets al Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Estilos de progreso de recorrido

Puedes personalizar el estilo del indicador de progreso usando la clase `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Puedes usar los siguientes indicadores de progreso:

- `JourneyProgressIndicator.none()`: No muestra nada - útil para ocultar el indicador en una pestaña específica.
- `JourneyProgressIndicator.linear()`: Barra de progreso lineal.
- `JourneyProgressIndicator.dots()`: Indicador de progreso basado en puntos.
- `JourneyProgressIndicator.numbered()`: Indicador de progreso con pasos numerados.
- `JourneyProgressIndicator.segments()`: Estilo de barra de progreso segmentada.
- `JourneyProgressIndicator.circular()`: Indicador de progreso circular.
- `JourneyProgressIndicator.timeline()`: Indicador de progreso estilo línea de tiempo.
- `JourneyProgressIndicator.custom()`: Indicador de progreso personalizado usando una función builder.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Puedes personalizar la posición y el relleno del indicador de progreso dentro de `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Puedes usar las siguientes posiciones del indicador de progreso:

- `ProgressIndicatorPosition.top`: Indicador de progreso en la parte superior de la pantalla.
- `ProgressIndicatorPosition.bottom`: Indicador de progreso en la parte inferior de la pantalla.

#### Anulación de estilo de progreso por pestaña

Puedes anular el `progressStyle` a nivel de diseño en pestañas individuales usando `NavigationTab.journey(progressStyle: ...)`. Las pestañas sin su propio `progressStyle` heredan el valor predeterminado del diseño. Las pestañas sin valor predeterminado de diseño ni estilo por pestaña no mostrarán un indicador de progreso.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // anula el valor predeterminado del diseño solo para esta pestaña
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

La clase `JourneyState` extiende `NyState` con funcionalidad específica de recorrido para facilitar la creación de flujos de onboarding y recorridos de múltiples pasos.

Para crear un nuevo `JourneyState`, puedes usar el siguiente comando.

``` bash
metro make:journey_widget onboard_user_dob
```

O si quieres crear múltiples widgets a la vez, puedes usar el siguiente comando.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Así se ve un widget JourneyState generado:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Tu lógica de inicialización aquí
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

Notarás que la clase **JourneyState** usa `nextStep` para navegar hacia adelante y `onBackPressed` para retroceder.

El método `nextStep` ejecuta el ciclo de vida completo de validación: `canContinue()` -> `onBeforeNext()` -> navegar (o `onComplete()` si es el último paso) -> `onAfterNext()`.

También puedes usar `buildJourneyContent` para construir un diseño estructurado con botones de navegación opcionales:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Estas son las propiedades que puedes usar en el método `buildJourneyContent`.

| Propiedad | Tipo | Descripción |
| --- | --- | --- |
| `content` | `Widget` | El contenido principal de la página. |
| `nextButton` | `Widget?` | El widget del botón siguiente. |
| `backButton` | `Widget?` | El widget del botón atrás. |
| `contentPadding` | `EdgeInsetsGeometry` | El relleno del contenido. |
| `header` | `Widget?` | El widget de encabezado. |
| `footer` | `Widget?` | El widget de pie de página. |
| `crossAxisAlignment` | `CrossAxisAlignment` | La alineación del eje cruzado del contenido. |

<div id="journey-state-helper-methods"></div>

### Métodos helper de JourneyState

La clase `JourneyState` tiene métodos helper y propiedades que puedes usar para personalizar el comportamiento de tu recorrido.

| Método / Propiedad | Descripción |
| --- | --- |
| [`nextStep()`](#next-step) | Navegar al siguiente paso con validación. Devuelve `Future<bool>`. |
| [`previousStep()`](#previous-step) | Navegar al paso anterior. Devuelve `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper simple para navegar al paso anterior. |
| [`onComplete()`](#on-complete) | Se ejecuta cuando el recorrido se completa (en el último paso). |
| [`onBeforeNext()`](#on-before-next) | Se ejecuta antes de navegar al siguiente paso. |
| [`onAfterNext()`](#on-after-next) | Se ejecuta después de navegar al siguiente paso. |
| [`canContinue()`](#can-continue) | Verificación de validación antes de navegar al siguiente paso. |
| [`isFirstStep`](#is-first-step) | Devuelve true si este es el primer paso del recorrido. |
| [`isLastStep`](#is-last-step) | Devuelve true si este es el último paso del recorrido. |
| [`currentStep`](#current-step) | Devuelve el índice del paso actual (base 0). |
| [`totalSteps`](#total-steps) | Devuelve el número total de pasos. |
| [`completionPercentage`](#completion-percentage) | Devuelve el porcentaje de completado (0.0 a 1.0). |
| [`goToStep(int index)`](#go-to-step) | Saltar a un paso específico por índice. |
| [`goToNextStep()`](#go-to-next-step) | Saltar al siguiente paso (sin validación). |
| [`goToPreviousStep()`](#go-to-previous-step) | Saltar al paso anterior (sin validación). |
| [`goToFirstStep()`](#go-to-first-step) | Saltar al primer paso. |
| [`goToLastStep()`](#go-to-last-step) | Saltar al último paso. |
| [`exitJourney()`](#exit-journey) | Salir del recorrido cerrando el navegador raíz. |
| [`resetCurrentStep()`](#reset-current-step) | Restablecer el estado del paso actual. |
| [`onJourneyComplete`](#on-journey-complete) | Callback cuando el recorrido se completa (anular en el último paso). |
| [`buildJourneyPage()`](#build-journey-page) | Construir una página de recorrido a pantalla completa con Scaffold. |


<div id="next-step"></div>

#### nextStep

El método `nextStep` navega al siguiente paso con validación completa. Ejecuta el ciclo de vida: `canContinue()` -> `onBeforeNext()` -> navegar o `onComplete()` -> `onAfterNext()`.

Puedes pasar `force: true` para omitir la validación y navegar directamente.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // ejecuta validación y luego navega
        ),
    );
}
```

Para omitir la validación:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

El método `previousStep` navega al paso anterior. Devuelve `true` si fue exitoso, `false` si ya está en el primer paso.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Ya está en el primer paso
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

El método `onBackPressed` es un helper simple que llama a `previousStep()` internamente.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

El método `onComplete` se ejecuta cuando se activa `nextStep()` en el último paso (después de que la validación pasa).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

El método `onBeforeNext` se ejecuta antes de navegar al siguiente paso.

Ej., si quieres guardar datos antes de navegar al siguiente paso, puedes hacerlo aquí.

``` dart
@override
Future<void> onBeforeNext() async {
    // Ej. guardar datos en la sesión
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

El método `onAfterNext` se ejecuta después de navegar al siguiente paso.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

El método `canContinue` se ejecuta cuando se activa `nextStep()`. Devuelve `false` para evitar la navegación.

``` dart
@override
Future<bool> canContinue() async {
    // Realiza tu lógica de validación aquí
    // Devuelve true si el recorrido puede continuar, false en caso contrario
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

La propiedad `isFirstStep` devuelve true si este es el primer paso del recorrido.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

La propiedad `isLastStep` devuelve true si este es el último paso del recorrido.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

La propiedad `currentStep` devuelve el índice del paso actual (base 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

La propiedad `totalSteps` devuelve el número total de pasos en el recorrido.

<div id="completion-percentage"></div>

#### completionPercentage

La propiedad `completionPercentage` devuelve el porcentaje de completado como un valor de 0.0 a 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

El método `goToStep` salta directamente a un paso específico por índice. Esto **no** activa la validación.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // saltar al paso con índice 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

El método `goToNextStep` salta al siguiente paso sin validación. Si ya está en el último paso, no hace nada.

``` dart
onPressed: () {
    goToNextStep(); // omitir validación e ir al siguiente paso
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

El método `goToPreviousStep` salta al paso anterior sin validación. Si ya está en el primer paso, no hace nada.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

El método `goToFirstStep` salta al primer paso.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

El método `goToLastStep` salta al último paso.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

El método `exitJourney` sale del recorrido cerrando el navegador raíz.

``` dart
onPressed: () {
    exitJourney(); // cerrar el navegador raíz
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

El método `resetCurrentStep` restablece el estado del paso actual.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

El getter `onJourneyComplete` puede ser anulado en el **último paso** de tu recorrido para definir qué sucede cuando el usuario completa el flujo.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navegar a tu página de inicio o siguiente destino
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // activa el callback de completado
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

El método `buildJourneyPage` construye una página de recorrido a pantalla completa envuelta en un `Scaffold` con `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Propiedad | Tipo | Descripción |
| --- | --- | --- |
| `content` | `Widget` | El contenido principal de la página. |
| `nextButton` | `Widget?` | El widget del botón siguiente. |
| `backButton` | `Widget?` | El widget del botón atrás. |
| `contentPadding` | `EdgeInsetsGeometry` | El relleno del contenido. |
| `header` | `Widget?` | El widget de encabezado. |
| `footer` | `Widget?` | El widget de pie de página. |
| `backgroundColor` | `Color?` | El color de fondo del Scaffold. |
| `appBar` | `Widget?` | Un widget AppBar opcional. |
| `crossAxisAlignment` | `CrossAxisAlignment` | La alineación del eje cruzado del contenido. |

<div id="navigating-within-a-tab"></div>

## Navegar a widgets dentro de una pestaña

Puedes navegar a widgets dentro de una pestaña usando el helper `pushTo`.

Dentro de tu pestaña, puedes usar el helper `pushTo` para navegar a otro widget.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

También puedes pasar datos al widget al que navegas.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Pestañas

Las pestañas son los bloques de construcción principales de un Navigation Hub.

Puedes agregar pestañas a un Navigation Hub usando la clase `NavigationTab` y sus constructores con nombre.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

En el ejemplo anterior, hemos agregado dos pestañas al Navigation Hub, Home y Settings.

Puedes usar diferentes tipos de pestañas:

- `NavigationTab.tab()` - Una pestaña de navegación estándar.
- `NavigationTab.badge()` - Una pestaña con contador de insignia.
- `NavigationTab.alert()` - Una pestaña con indicador de alerta.
- `NavigationTab.journey()` - Una pestaña para diseños de navegación de recorrido.

<div id="adding-badges-to-tabs"></div>

## Agregar insignias a pestañas

Hemos facilitado agregar insignias a tus pestañas.

Las insignias son una excelente forma de mostrar a los usuarios que hay algo nuevo en una pestaña.

Por ejemplo, si tienes una aplicación de chat, puedes mostrar el número de mensajes sin leer en la pestaña de chat.

Para agregar una insignia a una pestaña, puedes usar el constructor `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

En el ejemplo anterior, hemos agregado una insignia a la pestaña de Chat con un conteo inicial de 10.

También puedes actualizar el conteo de la insignia programáticamente.

``` dart
/// Incrementar el conteo de la insignia
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Actualizar el conteo de la insignia
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Limpiar el conteo de la insignia
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

De forma predeterminada, el conteo de la insignia se recordará. Si quieres **limpiar** el conteo de la insignia en cada sesión, puedes establecer `rememberCount` en `false`.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Agregar alertas a pestañas

Puedes agregar alertas a tus pestañas.

A veces podrías no querer mostrar un conteo de insignia, pero sí quieres mostrar un indicador de alerta al usuario.

Para agregar una alerta a una pestaña, puedes usar el constructor `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Esto agregará una alerta a la pestaña de Chat con color rojo.

También puedes actualizar la alerta programáticamente.

``` dart
/// Habilitar la alerta
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Deshabilitar la alerta
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Índice inicial

De forma predeterminada, el Navigation Hub inicia en la primera pestaña (índice 0). Puedes cambiar esto anulando el getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Iniciar en la segunda pestaña
    ...
}
```

<div id="maintaining-state"></div>

## Mantener estado

De forma predeterminada, el estado del Navigation Hub se mantiene.

Esto significa que cuando navegas a una pestaña, el estado de la pestaña se preserva.

Si quieres limpiar el estado de la pestaña cada vez que navegas a ella, puedes establecer `maintainState` en `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Puedes anular el método `onTap` para agregar lógica personalizada cuando se toca una pestaña.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Agregar lógica personalizada aquí
        // Ej. rastrear analíticas, mostrar confirmación, etc.
        super.onTap(index); // Siempre llamar a super para manejar el cambio de pestaña
    }
}
```

<div id="state-actions"></div>

## Acciones de estado

Las acciones de estado son una forma de interactuar con el Navigation Hub desde cualquier lugar de tu aplicación.

Estas son las acciones de estado que puedes usar:

``` dart
/// Restablecer la pestaña en un índice dado
/// Ej. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Cambiar la pestaña actual programáticamente
/// Ej. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Actualizar el conteo de la insignia
/// Ej. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Incrementar el conteo de la insignia
/// Ej. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Limpiar el conteo de la insignia
/// Ej. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Habilitar la alerta para una pestaña
/// Ej. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Deshabilitar la alerta para una pestaña
/// Ej. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navegar a la siguiente página en un diseño de recorrido
/// Ej. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navegar a la página anterior en un diseño de recorrido
/// Ej. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Para usar una acción de estado, puedes hacer lo siguiente:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Cambiar a la pestaña 2

await MyNavigationHub.stateActions.nextPage(); // Recorrido: ir a la siguiente página
```

<div id="loading-style"></div>

## Estilo de carga

De forma predeterminada, el Navigation Hub mostrará tu Widget de carga **predeterminado** (resources/widgets/loader_widget.dart) cuando la pestaña está cargando.

Puedes personalizar el `loadingStyle` para actualizar el estilo de carga.

| Estilo | Descripción |
| --- | --- |
| normal | Estilo de carga predeterminado |
| skeletonizer | Estilo de carga esqueleto |
| none | Sin estilo de carga |

Puedes cambiar el estilo de carga así:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// o
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Si quieres actualizar el Widget de carga en uno de los estilos, puedes pasar un `child` al `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Ahora, cuando la pestaña esté cargando, se mostrará el texto "Loading...".

Ejemplo a continuación:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simular carga por 3 segundos

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Crear un Navigation Hub

Para crear un Navigation Hub, puedes usar [Metro](/docs/{{$version}}/metro), usa el siguiente comando.

``` bash
metro make:navigation_hub base
```

El comando te guiará a través de una configuración interactiva donde puedes elegir el tipo de diseño y definir tus pestañas o estados de recorrido.

Esto creará un archivo `base_navigation_hub.dart` en tu directorio `resources/pages/navigation_hubs/base/` con widgets hijos organizados en subcarpetas `tabs/` o `states/`.
