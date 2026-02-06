# Button

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- [Grundlegende Verwendung](#basic-usage "Grundlegende Verwendung")
- [Verfuegbare Button-Typen](#button-types "Verfuegbare Button-Typen")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Asynchroner Ladezustand](#async-loading "Asynchroner Ladezustand")
- [Animationsstile](#animation-styles "Animationsstile")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Splash-Stile](#splash-styles "Splash-Stile")
- [Ladestile](#loading-styles "Ladestile")
- [Formularuebermittlung](#form-submission "Formularuebermittlung")
- [Buttons anpassen](#customizing-buttons "Buttons anpassen")
- [Parameter-Referenz](#parameters "Parameter-Referenz")


<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} stellt eine `Button`-Klasse mit acht vorgefertigten Button-Stilen bereit. Jeder Button bietet integrierte Unterstuetzung fuer:

- **Asynchrone Ladezustaende** -- geben Sie ein `Future` von `onPressed` zurueck und der Button zeigt automatisch einen Ladeindikator an
- **Animationsstile** -- waehlen Sie aus Clickable, Bounce, Pulse, Squeeze, Jelly, Shine, Ripple, Morph und Shake-Effekten
- **Splash-Stile** -- fuegen Sie Ripple-, Highlight-, Glow- oder Ink-Touch-Feedback hinzu
- **Formularuebermittlung** -- verbinden Sie einen Button direkt mit einer `NyFormData`-Instanz

Sie finden die Button-Definitionen Ihrer App in `lib/resources/widgets/buttons/buttons.dart`. Diese Datei enthaelt eine `Button`-Klasse mit statischen Methoden fuer jeden Button-Typ, die es einfach machen, die Standardwerte fuer Ihr Projekt anzupassen.

<div id="basic-usage"></div>

## Grundlegende Verwendung

Verwenden Sie die `Button`-Klasse ueberall in Ihren Widgets. Hier ist ein einfaches Beispiel innerhalb einer Seite:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Jeder Button-Typ folgt demselben Muster -- uebergeben Sie ein `text`-Label und einen `onPressed`-Callback.

<div id="button-types"></div>

## Verfuegbare Button-Typen

Alle Buttons werden ueber die `Button`-Klasse mit statischen Methoden aufgerufen.

<div id="primary"></div>

### Primary

Ein gefuellter Button mit Schatten, der die Primaerfarbe Ihres Themes verwendet. Am besten fuer Haupt-Call-to-Action-Elemente geeignet.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Ein gefuellter Button mit einer weicheren Oberflaechenfarbe und dezentem Schatten. Gut fuer sekundaere Aktionen neben einem primaeren Button.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Ein transparenter Button mit einem Rahmen. Nuetzlich fuer weniger prominente Aktionen oder Abbrechen-Buttons.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen die Rahmen- und Textfarben anpassen:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Ein minimaler Button ohne Hintergrund oder Rahmen. Ideal fuer Inline-Aktionen oder Links.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen die Textfarbe anpassen:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Ein gefuellter Button, der ein Symbol neben dem Text anzeigt. Das Symbol erscheint standardmaessig vor dem Text.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen die Hintergrundfarbe anpassen:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Ein Button mit linearem Farbverlauf als Hintergrund. Verwendet standardmaessig die Primaer- und Tertiaerfarben Ihres Themes.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen benutzerdefinierte Verlaufsfarben angeben:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Ein pillenfoermiger Button mit vollstaendig abgerundeten Ecken. Der Rahmenradius ist standardmaessig die Haelfte der Button-Hoehe.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen die Hintergrundfarbe und den Rahmenradius anpassen:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Ein Button im Milchglas-Stil mit Unschaerfe-Effekt. Funktioniert gut, wenn er ueber Bildern oder farbigen Hintergruenden platziert wird.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Sie koennen die Textfarbe anpassen:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Asynchroner Ladezustand

Eine der leistungsfaehigsten Funktionen der {{ config('app.name') }}-Buttons ist die **automatische Ladezustandsverwaltung**. Wenn Ihr `onPressed`-Callback ein `Future` zurueckgibt, zeigt der Button automatisch einen Ladeindikator an und deaktiviert die Interaktion, bis die Operation abgeschlossen ist.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Waehrend der asynchronen Operation zeigt der Button einen Skeleton-Ladeeffekt an (standardmaessig). Sobald das `Future` abgeschlossen ist, kehrt der Button in seinen normalen Zustand zurueck.

Dies funktioniert mit jeder asynchronen Operation -- API-Aufrufe, Datenbankschreibvorgaenge, Datei-Uploads oder alles, was ein `Future` zurueckgibt:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

Es ist nicht noetig, `isLoading`-Zustandsvariablen zu verwalten, `setState` aufzurufen oder etwas in ein `StatefulWidget` zu wrappen -- {{ config('app.name') }} erledigt alles fuer Sie.

### Funktionsweise

Wenn der Button erkennt, dass `onPressed` ein `Future` zurueckgibt, verwendet er den `lockRelease`-Mechanismus, um:

1. Einen Ladeindikator anzuzeigen (gesteuert durch `LoadingStyle`)
2. Den Button zu deaktivieren, um doppelte Taps zu verhindern
3. Auf den Abschluss des `Future` zu warten
4. Den Button in seinen normalen Zustand zurueckzuversetzen

<div id="animation-styles"></div>

## Animationsstile

Buttons unterstuetzen Druckanimationen ueber `ButtonAnimationStyle`. Diese Animationen bieten visuelles Feedback, wenn ein Benutzer mit einem Button interagiert. Sie koennen den Animationsstil festlegen, wenn Sie Ihre Buttons in `lib/resources/widgets/buttons/buttons.dart` anpassen.

<div id="anim-clickable"></div>

### Clickable

Ein Duolingo-artiger 3D-Druckeffekt. Der Button bewegt sich beim Druecken nach unten und springt beim Loslassen zurueck. Am besten fuer primaere Aktionen und spielaehnliche UX.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Skaliert den Button beim Druecken herunter und springt beim Loslassen zurueck. Am besten fuer In-den-Warenkorb-, Like- und Favoriten-Buttons.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Ein subtiler kontinuierlicher Skalierungspuls, waehrend der Button gehalten wird. Am besten fuer Langdruck-Aktionen oder um Aufmerksamkeit zu erregen.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Komprimiert den Button horizontal und dehnt ihn vertikal beim Druecken aus. Am besten fuer verspielte und interaktive UIs.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Ein wackeliger elastischer Verformungseffekt. Am besten fuer lustige, lassige oder Unterhaltungs-Apps.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Ein glaenzendes Highlight, das beim Druecken ueber den Button gleitet. Am besten fuer Premium-Funktionen oder CTAs, auf die Sie aufmerksam machen moechten.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Ein verbesserter Welleneffekt, der sich vom Beruehrungspunkt aus ausbreitet. Am besten fuer Material-Design-Betonung.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Der Rahmenradius des Buttons vergroessert sich beim Druecken und erzeugt einen Formwandel-Effekt. Am besten fuer subtiles, elegantes Feedback.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Eine horizontale Schuettel-Animation. Am besten fuer Fehlerzustaende oder ungueltige Aktionen -- schuetteln Sie den Button, um zu signalisieren, dass etwas schiefgelaufen ist.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Effekt fein abstimmen:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Animationen deaktivieren

Um einen Button ohne Animation zu verwenden:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Standard-Animation aendern

Um die Standard-Animation fuer einen Button-Typ zu aendern, bearbeiten Sie Ihre `lib/resources/widgets/buttons/buttons.dart`-Datei:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Splash-Stile

Splash-Effekte bieten visuelles Touch-Feedback auf Buttons. Konfigurieren Sie sie ueber `ButtonSplashStyle`. Splash-Stile koennen mit Animationsstilen fuer geschichtetes Feedback kombiniert werden.

### Verfuegbare Splash-Stile

| Splash | Factory | Beschreibung |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Standard-Material-Ripple vom Beruehrungspunkt |
| Highlight | `ButtonSplashStyle.highlight()` | Dezentes Highlight ohne Ripple-Animation |
| Glow | `ButtonSplashStyle.glow()` | Sanftes Leuchten vom Beruehrungspunkt |
| Ink | `ButtonSplashStyle.ink()` | Schneller Tintenklecks, schneller und reaktionsfreudiger |
| None | `ButtonSplashStyle.none()` | Kein Splash-Effekt |
| Custom | `ButtonSplashStyle.custom()` | Volle Kontrolle ueber die Splash-Factory |

### Beispiel

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Sie koennen Splash-Farben und Deckkraft anpassen:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Ladestile

Der Ladeindikator, der waehrend asynchroner Operationen angezeigt wird, wird durch `LoadingStyle` gesteuert. Sie koennen ihn pro Button-Typ in Ihrer Buttons-Datei festlegen.

### Skeletonizer (Standard)

Zeigt einen Shimmer-Skeleton-Effekt ueber dem Button an:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Zeigt ein Lade-Widget an (standardmaessig der App-Loader):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Haelt den Button sichtbar, deaktiviert aber die Interaktion waehrend des Ladens:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Formularuebermittlung

Alle Buttons unterstuetzen den `submitForm`-Parameter, der den Button mit einem `NyForm` verbindet. Beim Antippen validiert der Button das Formular und ruft Ihren Erfolgshandler mit den Formulardaten auf.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

Der `submitForm`-Parameter akzeptiert einen Record mit zwei Werten:
1. Eine `NyFormData`-Instanz (oder Formularname als `String`)
2. Einen Callback, der die validierten Daten empfaengt

Standardmaessig ist `showToastError` `true`, was eine Toast-Benachrichtigung anzeigt, wenn die Formularvalidierung fehlschlaegt. Setzen Sie es auf `false`, um Fehler stillschweigend zu behandeln:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Wenn der `submitForm`-Callback ein `Future` zurueckgibt, zeigt der Button automatisch einen Ladezustand an, bis die asynchrone Operation abgeschlossen ist.

<div id="customizing-buttons"></div>

## Buttons anpassen

Alle Button-Standardwerte sind in Ihrem Projekt unter `lib/resources/widgets/buttons/buttons.dart` definiert. Jeder Button-Typ hat eine entsprechende Widget-Klasse in `lib/resources/widgets/buttons/partials/`.

### Standardstile aendern

Um das Standard-Erscheinungsbild eines Buttons zu aendern, bearbeiten Sie die `Button`-Klasse:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Ein Button-Widget anpassen

Um das visuelle Erscheinungsbild eines Button-Typs zu aendern, bearbeiten Sie das entsprechende Widget in `lib/resources/widgets/buttons/partials/`. Um beispielsweise den Rahmenradius oder Schatten des primaeren Buttons zu aendern:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Einen neuen Button-Typ erstellen

Um einen neuen Button-Typ hinzuzufuegen:

1. Erstellen Sie eine neue Widget-Datei in `lib/resources/widgets/buttons/partials/`, die `StatefulAppButton` erweitert.
2. Implementieren Sie die `buildButton`-Methode.
3. Fuegen Sie eine statische Methode in der `Button`-Klasse hinzu.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Dann registrieren Sie ihn in der `Button`-Klasse:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Parameter-Referenz

### Allgemeine Parameter (alle Button-Typen)

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `text` | `String` | erforderlich | Der Button-Label-Text |
| `onPressed` | `VoidCallback?` | `null` | Callback beim Antippen des Buttons. Geben Sie ein `Future` zurueck fuer automatischen Ladezustand |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Formularuebermittlungs-Record (Formularinstanz, Erfolgs-Callback) |
| `onFailure` | `Function(dynamic)?` | `null` | Wird aufgerufen, wenn die Formularvalidierung fehlschlaegt |
| `showToastError` | `bool` | `true` | Toast-Benachrichtigung bei Formularvalidierungsfehler anzeigen |
| `width` | `double?` | `null` | Button-Breite (standardmaessig volle Breite) |

### Typspezifische Parameter

#### Button.outlined

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `borderColor` | `Color?` | Theme-Rahmenfarbe | Rahmenfarbe |
| `textColor` | `Color?` | Theme-Primaerfarbe | Textfarbe |

#### Button.textOnly

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `textColor` | `Color?` | Theme-Primaerfarbe | Textfarbe |

#### Button.icon

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `icon` | `Widget` | erforderlich | Das anzuzeigende Symbol-Widget |
| `color` | `Color?` | Theme-Primaerfarbe | Hintergrundfarbe |

#### Button.gradient

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `gradientColors` | `List<Color>?` | Primaer- und Tertiaerfarben | Farbverlaufs-Stopps |

#### Button.rounded

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `backgroundColor` | `Color?` | Theme Primary Container Farbe | Hintergrundfarbe |
| `borderRadius` | `BorderRadius?` | Pillenform (Hoehe / 2) | Eckenradius |

#### Button.transparency

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `color` | `Color?` | Theme-adaptiv | Textfarbe |

### ButtonAnimationStyle-Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `duration` | `Duration` | Variiert pro Typ | Animationsdauer |
| `curve` | `Curve` | Variiert pro Typ | Animationskurve |
| `enableHapticFeedback` | `bool` | Variiert pro Typ | Haptisches Feedback beim Druecken ausloesen |
| `translateY` | `double` | `4.0` | Clickable: vertikale Druckdistanz |
| `shadowOffset` | `double` | `4.0` | Clickable: Schattentiefe |
| `scaleMin` | `double` | `0.92` | Bounce: minimale Skalierung beim Druecken |
| `pulseScale` | `double` | `1.05` | Pulse: maximale Skalierung waehrend des Pulses |
| `squeezeX` | `double` | `0.95` | Squeeze: horizontale Kompression |
| `squeezeY` | `double` | `1.05` | Squeeze: vertikale Ausdehnung |
| `jellyStrength` | `double` | `0.15` | Jelly: Wackel-Intensitaet |
| `shineColor` | `Color` | `Colors.white` | Shine: Highlight-Farbe |
| `shineWidth` | `double` | `0.3` | Shine: Breite des Glanzstreifens |
| `rippleScale` | `double` | `2.0` | Ripple: Ausbreitungsskala |
| `morphRadius` | `double` | `24.0` | Morph: Ziel-Rahmenradius |
| `shakeOffset` | `double` | `8.0` | Shake: horizontale Verschiebung |
| `shakeCount` | `int` | `3` | Shake: Anzahl der Schwingungen |

### ButtonSplashStyle-Parameter

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|---------|-------------|
| `splashColor` | `Color?` | Theme-Oberflaechenfarbe | Splash-Effekt-Farbe |
| `highlightColor` | `Color?` | Theme-Oberflaechenfarbe | Highlight-Effekt-Farbe |
| `splashOpacity` | `double` | `0.12` | Deckkraft des Splash |
| `highlightOpacity` | `double` | `0.06` | Deckkraft des Highlights |
| `borderRadius` | `BorderRadius?` | `null` | Splash-Clip-Radius |
