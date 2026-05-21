# Was ist {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Einleitung](#introduction "Einleitung")
- App-Entwicklung
    - [Neu bei Flutter?](#new-to-flutter "Neu bei Flutter?")
    - [Wartung und Veröffentlichungsplan](#maintenance-and-release-schedule "Wartung und Veröffentlichungsplan")
- Credits
    - [Framework-Abhängigkeiten](#framework-dependencies "Framework-Abhängigkeiten")
    - [Mitwirkende](#contributors "Mitwirkende")


<div id="introduction"></div>

## Einleitung

{{ config('app.name') }} ist ein Micro-Framework für Flutter, das die App-Entwicklung vereinfachen soll. Es bietet ein strukturiertes Boilerplate mit vorkonfigurierten Grundlagen, damit Sie sich auf den Aufbau der Funktionen Ihrer App konzentrieren können, anstatt die Infrastruktur einzurichten.

Standardmäßig beinhaltet {{ config('app.name') }}:

- **Routing** - Einfache, deklarative Routenverwaltung mit Guards und Deep Linking
- **Networking** - API-Services mit Dio, Interceptors und Response-Morphing
- **State-Management** - Reaktiver State mit NyState und globalen State-Updates
- **Lokalisierung** - Mehrsprachige Unterstützung mit JSON-Übersetzungsdateien
- **Themes** - Heller/Dunkler Modus mit Theme-Wechsel
- **Lokaler Speicher** - Sicherer Speicher mit Backpack und NyStorage
- **Formulare** - Formularbehandlung mit Validierung und Feldtypen
- **Push-Benachrichtigungen** - Unterstützung für lokale und Remote-Benachrichtigungen
- **CLI-Tool (Metro)** - Generierung von Seiten, Controllern, Models und mehr

<div id="new-to-flutter"></div>

## Neu bei Flutter?

Wenn Sie neu bei Flutter sind, beginnen Sie mit den offiziellen Ressourcen:

- <a href="https://flutter.dev" target="_BLANK">Flutter-Dokumentation</a> - Umfassende Anleitungen und API-Referenz
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube-Kanal</a> - Tutorials und Updates
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Praktische Rezepte für gängige Aufgaben

Sobald Sie mit den Flutter-Grundlagen vertraut sind, wird sich {{ config('app.name') }} intuitiv anfühlen, da es auf Standard-Flutter-Patterns aufbaut.


<div id="maintenance-and-release-schedule"></div>

## Wartung und Veröffentlichungsplan

{{ config('app.name') }} folgt der <a href="https://semver.org" target="_BLANK">Semantischen Versionierung</a>:

- **Hauptversionen** (7.x → 8.x) - Einmal pro Jahr für grundlegende Änderungen
- **Nebenversionen** (7.0 → 7.1) - Neue Funktionen, rückwärtskompatibel
- **Patch-Versionen** (7.0.0 → 7.0.1) - Fehlerbehebungen und kleinere Verbesserungen

Fehlerbehebungen und Sicherheitspatches werden zeitnah über die GitHub-Repositories behandelt.


<div id="framework-dependencies"></div>

## Framework-Abhängigkeiten

{{ config('app.name') }} v7 basiert auf diesen Open-Source-Paketen:

### Kern-Abhängigkeiten

| Paket | Zweck |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | HTTP-Client für API-Anfragen |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Sicherer lokaler Speicher |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internationalisierung und Formatierung |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Reaktive Erweiterungen für Streams |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Wertgleichheit für Objekte |

### UI & Widgets

| Paket | Zweck |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Skeleton-Ladeeffekte |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Toast-Benachrichtigungen |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Pull-to-Refresh-Funktionalität |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Versetzte Grid-Layouts |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Datumsauswahlfelder |

### Benachrichtigungen & Konnektivität

| Paket | Zweck |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Lokale Push-Benachrichtigungen |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Netzwerk-Konnektivitätsstatus |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | App-Icon-Badges |

### Utilities

| Paket | Zweck |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | URLs und Apps öffnen |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | String-Gross-/Kleinschreibungskonvertierung |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID-Generierung |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Dateisystem-Pfade |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Eingabe-Maskierung |


<div id="contributors"></div>

## Mitwirkende

Vielen Dank an alle, die zu {{ config('app.name') }} beigetragen haben! Wenn Sie beigetragen haben, melden Sie sich über <a href="mailto:support@nylo.dev">support@nylo.dev</a>, um hier aufgeführt zu werden.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Ersteller)
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
