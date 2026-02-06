# Button

---

<a name="section-1"></a>
- [Introduzione](#introduction "Introduzione")
- [Utilizzo Base](#basic-usage "Utilizzo Base")
- [Tipi di Button Disponibili](#button-types "Tipi di Button Disponibili")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Stato di Caricamento Async](#async-loading "Stato di Caricamento Async")
- [Stili di Animazione](#animation-styles "Stili di Animazione")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Stili Splash](#splash-styles "Stili Splash")
- [Stili di Caricamento](#loading-styles "Stili di Caricamento")
- [Invio Moduli](#form-submission "Invio Moduli")
- [Personalizzare i Button](#customizing-buttons "Personalizzare i Button")
- [Riferimento Parametri](#parameters "Riferimento Parametri")


<div id="introduction"></div>

## Introduzione

{{ config('app.name') }} fornisce una classe `Button` con otto stili di button predefiniti. Ogni button include il supporto integrato per:

- **Stati di caricamento async** -- restituisci un `Future` da `onPressed` e il button mostra automaticamente un indicatore di caricamento
- **Stili di animazione** -- scegli tra effetti clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph e shake
- **Stili splash** -- aggiungi feedback tattile ripple, highlight, glow o ink
- **Invio moduli** -- collega un button direttamente a un'istanza `NyFormData`

Puoi trovare le definizioni dei button della tua app in `lib/resources/widgets/buttons/buttons.dart`. Questo file contiene una classe `Button` con metodi statici per ogni tipo di button, rendendo semplice personalizzare i valori predefiniti per il tuo progetto.

<div id="basic-usage"></div>

## Utilizzo Base

Usa la classe `Button` ovunque nei tuoi widget. Ecco un esempio semplice all'interno di una pagina:

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

Ogni tipo di button segue lo stesso schema -- passa un'etichetta `text` e un callback `onPressed`.

<div id="button-types"></div>

## Tipi di Button Disponibili

Tutti i button sono accessibili tramite la classe `Button` usando metodi statici.

<div id="primary"></div>

### Primary

Un button riempito con ombra, che utilizza il colore primario del tuo tema. Ideale per gli elementi call-to-action principali.

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

Un button riempito con un colore di superficie piu morbido e un'ombra sottile. Adatto per azioni secondarie accanto a un button primario.

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

Un button trasparente con un bordo. Utile per azioni meno prominenti o button di annullamento.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Puoi personalizzare i colori del bordo e del testo:

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

Un button minimale senza sfondo o bordo. Ideale per azioni inline o link.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Puoi personalizzare il colore del testo:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Un button riempito che visualizza un'icona accanto al testo. L'icona appare prima del testo per impostazione predefinita.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Puoi personalizzare il colore di sfondo:

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

Un button con uno sfondo a gradiente lineare. Utilizza i colori primario e terziario del tuo tema per impostazione predefinita.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Puoi fornire colori di gradiente personalizzati:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Un button a forma di pillola con angoli completamente arrotondati. Il raggio del bordo e per impostazione predefinita la meta dell'altezza del button.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Puoi personalizzare il colore di sfondo e il raggio del bordo:

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

Un button in stile vetro smerigliato con effetto di sfocatura dello sfondo. Funziona bene quando posizionato sopra immagini o sfondi colorati.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Puoi personalizzare il colore del testo:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Stato di Caricamento Async

Una delle funzionalita piu potenti dei button {{ config('app.name') }} e la **gestione automatica dello stato di caricamento**. Quando il tuo callback `onPressed` restituisce un `Future`, il button mostrera automaticamente un indicatore di caricamento e disabilitera l'interazione fino al completamento dell'operazione.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Durante l'operazione async, il button mostrera un effetto di caricamento skeleton (per impostazione predefinita). Una volta completato il `Future`, il button torna al suo stato normale.

Funziona con qualsiasi operazione async -- chiamate API, scritture su database, upload di file o qualsiasi cosa che restituisca un `Future`:

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

Non c'e bisogno di gestire variabili di stato `isLoading`, chiamare `setState` o avvolgere qualsiasi cosa in un `StatefulWidget` -- {{ config('app.name') }} gestisce tutto per te.

### Come Funziona

Quando il button rileva che `onPressed` restituisce un `Future`, utilizza il meccanismo `lockRelease` per:

1. Mostrare un indicatore di caricamento (controllato da `LoadingStyle`)
2. Disabilitare il button per prevenire tap duplicati
3. Attendere il completamento del `Future`
4. Ripristinare il button al suo stato normale

<div id="animation-styles"></div>

## Stili di Animazione

I button supportano animazioni alla pressione tramite `ButtonAnimationStyle`. Queste animazioni forniscono feedback visivo quando un utente interagisce con un button. Puoi impostare lo stile di animazione quando personalizzi i tuoi button in `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Un effetto di pressione 3D in stile Duolingo. Il button si sposta verso il basso alla pressione e rimbalza al rilascio. Ideale per azioni principali e UX in stile gioco.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Regola l'effetto:

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

Riduce la scala del button alla pressione e rimbalza al rilascio. Ideale per button aggiungi-al-carrello, mi piace e preferiti.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Regola l'effetto:

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

Un sottile impulso di scala continuo mentre il button e tenuto premuto. Ideale per azioni a pressione prolungata o per attirare l'attenzione.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Regola l'effetto:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Comprime il button orizzontalmente e lo espande verticalmente alla pressione. Ideale per UI giocose e interattive.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Regola l'effetto:

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

Un effetto di deformazione elastica oscillante. Ideale per app divertenti, casual o di intrattenimento.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Regola l'effetto:

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

Un riflesso lucido che scorre attraverso il button alla pressione. Ideale per funzionalita premium o CTA a cui vuoi attirare l'attenzione.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Regola l'effetto:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Un effetto onda potenziato che si espande dal punto di tocco. Ideale per l'enfasi Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Regola l'effetto:

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

Il raggio del bordo del button aumenta alla pressione, creando un effetto di trasformazione della forma. Ideale per un feedback sottile ed elegante.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Regola l'effetto:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Un'animazione di scuotimento orizzontale. Ideale per stati di errore o azioni non valide -- scuoti il button per segnalare che qualcosa e andato storto.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Regola l'effetto:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Disabilitare le Animazioni

Per usare un button senza animazione:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Cambiare l'Animazione Predefinita

Per cambiare l'animazione predefinita per un tipo di button, modifica il file `lib/resources/widgets/buttons/buttons.dart`:

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

## Stili Splash

Gli effetti splash forniscono feedback tattile visivo sui button. Configurali tramite `ButtonSplashStyle`. Gli stili splash possono essere combinati con gli stili di animazione per un feedback stratificato.

### Stili Splash Disponibili

| Splash | Factory | Descrizione |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Onda Material standard dal punto di tocco |
| Highlight | `ButtonSplashStyle.highlight()` | Evidenziazione sottile senza animazione onda |
| Glow | `ButtonSplashStyle.glow()` | Bagliore morbido che si irradia dal punto di tocco |
| Ink | `ButtonSplashStyle.ink()` | Splash di inchiostro rapido, piu veloce e reattivo |
| None | `ButtonSplashStyle.none()` | Nessun effetto splash |
| Custom | `ButtonSplashStyle.custom()` | Controllo completo sulla factory splash |

### Esempio

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

Puoi personalizzare i colori splash e l'opacita:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Stili di Caricamento

L'indicatore di caricamento mostrato durante le operazioni async e controllato da `LoadingStyle`. Puoi impostarlo per tipo di button nel tuo file buttons.

### Skeletonizer (Predefinito)

Visualizza un effetto shimmer skeleton sul button:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Mostra un widget di caricamento (per impostazione predefinita il loader dell'app):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Mantiene il button visibile ma disabilita l'interazione durante il caricamento:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Invio Moduli

Tutti i button supportano il parametro `submitForm`, che collega il button a un `NyForm`. Quando toccato, il button validera il modulo e chiamera il tuo handler di successo con i dati del modulo.

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

Il parametro `submitForm` accetta un record con due valori:
1. Un'istanza `NyFormData` (o nome del modulo come `String`)
2. Un callback che riceve i dati validati

Per impostazione predefinita, `showToastError` e `true`, che mostra una notifica toast quando la validazione del modulo fallisce. Impostalo a `false` per gestire gli errori silenziosamente:

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

Quando il callback `submitForm` restituisce un `Future`, il button mostrera automaticamente uno stato di caricamento fino al completamento dell'operazione async.

<div id="customizing-buttons"></div>

## Personalizzare i Button

Tutti i valori predefiniti dei button sono definiti nel tuo progetto in `lib/resources/widgets/buttons/buttons.dart`. Ogni tipo di button ha una classe widget corrispondente in `lib/resources/widgets/buttons/partials/`.

### Modificare gli Stili Predefiniti

Per modificare l'aspetto predefinito di un button, modifica la classe `Button`:

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

### Personalizzare un Widget Button

Per cambiare l'aspetto visivo di un tipo di button, modifica il widget corrispondente in `lib/resources/widgets/buttons/partials/`. Ad esempio, per cambiare il raggio del bordo o l'ombra del button primario:

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

### Creare un Nuovo Tipo di Button

Per aggiungere un nuovo tipo di button:

1. Crea un nuovo file widget in `lib/resources/widgets/buttons/partials/` che estende `StatefulAppButton`.
2. Implementa il metodo `buildButton`.
3. Aggiungi un metodo statico nella classe `Button`.

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

Poi registralo nella classe `Button`:

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

## Riferimento Parametri

### Parametri Comuni (Tutti i Tipi di Button)

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `text` | `String` | obbligatorio | Il testo dell'etichetta del button |
| `onPressed` | `VoidCallback?` | `null` | Callback quando il button viene toccato. Restituisci un `Future` per lo stato di caricamento automatico |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Record di invio modulo (istanza modulo, callback successo) |
| `onFailure` | `Function(dynamic)?` | `null` | Chiamato quando la validazione del modulo fallisce |
| `showToastError` | `bool` | `true` | Mostra notifica toast in caso di errore di validazione del modulo |
| `width` | `double?` | `null` | Larghezza del button (predefinito larghezza piena) |

### Parametri Specifici per Tipo

#### Button.outlined

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Colore outline del tema | Colore del bordo |
| `textColor` | `Color?` | Colore primario del tema | Colore del testo |

#### Button.textOnly

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Colore primario del tema | Colore del testo |

#### Button.icon

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `icon` | `Widget` | obbligatorio | Il widget icona da visualizzare |
| `color` | `Color?` | Colore primario del tema | Colore di sfondo |

#### Button.gradient

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Colori primario e terziario | Punti di colore del gradiente |

#### Button.rounded

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Colore primary container del tema | Colore di sfondo |
| `borderRadius` | `BorderRadius?` | Forma pillola (altezza / 2) | Raggio degli angoli |

#### Button.transparency

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `color` | `Color?` | Adattivo al tema | Colore del testo |

### Parametri ButtonAnimationStyle

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Varia per tipo | Durata dell'animazione |
| `curve` | `Curve` | Varia per tipo | Curva dell'animazione |
| `enableHapticFeedback` | `bool` | Varia per tipo | Attiva feedback aptico alla pressione |
| `translateY` | `double` | `4.0` | Clickable: distanza di pressione verticale |
| `shadowOffset` | `double` | `4.0` | Clickable: profondita dell'ombra |
| `scaleMin` | `double` | `0.92` | Bounce: scala minima alla pressione |
| `pulseScale` | `double` | `1.05` | Pulse: scala massima durante il pulse |
| `squeezeX` | `double` | `0.95` | Squeeze: compressione orizzontale |
| `squeezeY` | `double` | `1.05` | Squeeze: espansione verticale |
| `jellyStrength` | `double` | `0.15` | Jelly: intensita dell'oscillazione |
| `shineColor` | `Color` | `Colors.white` | Shine: colore del riflesso |
| `shineWidth` | `double` | `0.3` | Shine: larghezza della banda di lucentezza |
| `rippleScale` | `double` | `2.0` | Ripple: scala di espansione |
| `morphRadius` | `double` | `24.0` | Morph: raggio del bordo target |
| `shakeOffset` | `double` | `8.0` | Shake: spostamento orizzontale |
| `shakeCount` | `int` | `3` | Shake: numero di oscillazioni |

### Parametri ButtonSplashStyle

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Colore surface del tema | Colore dell'effetto splash |
| `highlightColor` | `Color?` | Colore surface del tema | Colore dell'effetto highlight |
| `splashOpacity` | `double` | `0.12` | Opacita dello splash |
| `highlightOpacity` | `double` | `0.06` | Opacita dell'highlight |
| `borderRadius` | `BorderRadius?` | `null` | Raggio di clip dello splash |
