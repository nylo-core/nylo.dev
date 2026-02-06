# Requisiti

---

<a name="section-1"></a>
- [Requisiti di Sistema](#system-requirements "Requisiti di Sistema")
- [Installazione di Flutter](#installing-flutter "Installazione di Flutter")
- [Verifica dell'Installazione](#verifying-installation "Verifica dell'Installazione")
- [Configurazione dell'Editor](#set-up-an-editor "Configurazione dell'Editor")


<div id="system-requirements"></div>

## Requisiti di Sistema

{{ config('app.name') }} v7 richiede le seguenti versioni minime:

| Requisito | Versione Minima |
|-----------|-----------------|
| **Flutter** | 3.24.0 o superiore |
| **Dart SDK** | 3.10.7 o superiore |

### Supporto Piattaforme

{{ config('app.name') }} supporta tutte le piattaforme supportate da Flutter:

| Piattaforma | Supporto |
|-------------|----------|
| iOS | ✓ Supporto completo |
| Android | ✓ Supporto completo |
| Web | ✓ Supporto completo |
| macOS | ✓ Supporto completo |
| Windows | ✓ Supporto completo |
| Linux | ✓ Supporto completo |

<div id="installing-flutter"></div>

## Installazione di Flutter

Se non hai Flutter installato, segui la guida ufficiale di installazione per il tuo sistema operativo:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Guida all'installazione di Flutter</a>

<div id="verifying-installation"></div>

## Verifica dell'Installazione

Dopo aver installato Flutter, verifica la tua configurazione:

### Controlla la Versione di Flutter

``` bash
flutter --version
```

Dovresti vedere un output simile a:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Aggiorna Flutter (se necessario)

Se la tua versione di Flutter è inferiore alla 3.24.0, aggiorna all'ultima versione stabile:

``` bash
flutter channel stable
flutter upgrade
```

### Esegui Flutter Doctor

Verifica che il tuo ambiente di sviluppo sia configurato correttamente:

``` bash
flutter doctor -v
```

Questo comando controlla:
- Installazione di Flutter SDK
- Android toolchain (per lo sviluppo Android)
- Xcode (per lo sviluppo iOS/macOS)
- Dispositivi connessi
- Plugin IDE

Risolvi eventuali problemi segnalati prima di procedere con l'installazione di {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Configurazione dell'Editor

Scegli un IDE con supporto Flutter:

### Visual Studio Code (Consigliato)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> è leggero e ha un eccellente supporto per Flutter.

1. Installa <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Installa l'<a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">estensione Flutter</a>
3. Installa l'<a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">estensione Dart</a>

Guida alla configurazione: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Configurazione Flutter per VS Code</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> fornisce un IDE completo con supporto emulatore integrato.

1. Installa <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Installa il plugin Flutter (Preferences → Plugins → Flutter)
3. Installa il plugin Dart

Guida alla configurazione: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Configurazione Flutter per Android Studio</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community o Ultimate) supporta anche lo sviluppo Flutter.

1. Installa IntelliJ IDEA
2. Installa il plugin Flutter (Preferences → Plugins → Flutter)
3. Installa il plugin Dart

Una volta configurato il tuo editor, sei pronto per [installare {{ config('app.name') }}](/docs/7.x/installation).
