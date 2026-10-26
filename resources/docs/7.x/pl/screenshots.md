# Zrzuty ekranu
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie do zrzutów ekranu")
- [Przechwytywanie ekranu](#capturing-the-screen "Przechwytywanie całego ekranu")
- [Przechwytywanie obszaru](#capturing-an-area "Przechwytywanie jednej części ekranu za pomocą ScreenshotArea")
- [Zapisywanie zrzutu ekranu](#saving-a-screenshot "Zapisywanie zrzutu ekranu na dysku")
- [Strony sklepów](#store-listings "Dopasowywanie rozmiaru zrzutów ekranu do App Store i Google Play")
  - [Rozmiary sklepów](#store-sizes "Rozmiary ScreenshotStore")
- [Ukrywanie treści](#hiding-content "Ukrywanie treści przed zrzutami ekranu")
- [Przechwytywanie widgetu](#capturing-a-widget "Przechwytywanie widgetu bez jego wyświetlania")
- [Języki i motywy](#locales-and-themes "Przechwytywanie w innym języku lub motywie")
- [Z Metro](#from-metro "Robienie zrzutów ekranu z Metro")

<div id="introduction"></div>

## Wprowadzenie

<x-doc-strip label="Wymagania" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` wykonuje PNG ekranu Twojej aplikacji lub jednej jego części:

``` dart
// Cały ekran, wraz z dialogami i arkuszami
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Jedna część ekranu
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Przechwytywanie działa w każdym trybie builda, więc może zasilać funkcje Twojej aplikacji, takie jak udostępnianie paragonu lub dołączanie ekranu do zgłoszenia błędu. [Metro](#from-metro) i [playbooki](/docs/{{ $version }}/playbooks#screenshots) używają tego samego kodu.

`Screenshot` przechowuje PNG jako `bytes`, wraz z jego `width` i `height` w pikselach fizycznych.

<div id="capturing-the-screen"></div>

## Przechwytywanie ekranu

`Screenshot.capture()` odczytuje cały ekran aplikacji tak, jak widzi go użytkownik:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parametr | Opis |
|-----------|-------------|
| `pixelRatio` | Rozmiar względem pikseli logicznych. Domyślnie taki jak urządzenia, więc PNG ma natywną rozdzielczość ekranu |
| `settle` | Poczekaj na następną klatkę przed przechwyceniem (domyślnie `true`). Przekaż `false`, gdy klatka została właśnie narysowana |
| `locale` | Pokaż ekran w tym języku na czas przechwycenia, np. `'es'` |
| `theme` | Pokaż ekran w motywie o tym identyfikatorze na czas przechwycenia, np. `'dark'` |

Aplikacja zbudowana za pomocą `NyApp.materialApp` przechwytuje dokładnie swoje strony. Jeśli budujesz własny `MaterialApp`, dodaj `ScreenshotRoot` do jego `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Przechwytywanie obszaru

Opakuj część ekranu w `ScreenshotArea` i nadaj jej nazwę:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Następnie przechwyć ją po tej nazwie:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` przyjmuje te same parametry `pixelRatio`, `settle`, `locale` i `theme` co `capture`. `Screenshot.areas` wyświetla nazwy obszarów na ekranie.

<div id="saving-a-screenshot"></div>

## Zapisywanie zrzutu ekranu

`store` zapisuje PNG na dysku pamięci plików i zwraca ścieżkę:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Bez `disk` zapisuje na dysku domyślnym. Aby udostępnić obraz lub go wysłać, odczytaj `shot.bytes`.

<div id="store-listings"></div>

## Strony sklepów

`forStore` zwraca nowy zrzut ekranu w dokładnym rozmiarze, o który prosi sklep z aplikacjami:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Ekran o niemal takim samym kształcie wypełnia dany rozmiar, a każdy inny jest w nim dopasowywany. Z `frame: true` ekran jest rysowany w obrysie telefonu pod `caption`.

| Parametr | Opis |
|-----------|-------------|
| `frame` | Rysuj ekran wewnątrz obrysu telefonu |
| `caption` | Tekst nad telefonem, używany z `frame` |
| `background` | `Color` wokół ekranu |

Użyj `framed()`, aby narysować obrys w rozmiarze samego zrzutu ekranu:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Rozmiary sklepów

| Rozmiar | Id | Piksele |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` wyświetla każdy rozmiar, a `ScreenshotStore.named('ios-6.9')` znajduje jeden po jego identyfikatorze.

<div id="hiding-content"></div>

## Ukrywanie treści

Opakuj wszystko, co prywatne, w `ScreenshotHidden`. Na ekranie wyświetla się jak zwykle, a na każdym zrzucie ekranu zakrywa to szary blok:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Przekaż `replacement`, aby narysować własny element zastępczy zamiast szarego bloku.

<div id="capturing-a-widget"></div>

## Przechwytywanie widgetu

`Screenshot.widget` przechwytuje widget bez jego wyświetlania, na przykład kartę udostępniania zbudowaną tylko na potrzeby obrazu:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG jest tak duży jak widget, więc opakuj go w `SizedBox`, aby uzyskać dokładny rozmiar. Przekaż `context`, aby nadać widgetowi motyw i lokalizacje tego kontekstu, `size`, aby ustawić przestrzeń, w której jest rozmieszczany, oraz `delay`, aby dać obrazom czas na załadowanie.

<div id="locales-and-themes"></div>

## Języki i motywy

Przekaż `locale` lub `theme`, aby pokazać ekran inaczej na czas jednego przechwycenia:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Aplikacja przełącza się w miejscu, zachowując stronę, jej stan i stos nawigacji, i zaraz potem wraca. Nic nie jest zapisywane, więc wybory samego użytkownika pozostają nienaruszone. Język wymaga pliku językowego w Twoich zasobach, a motyw wymaga zarejestrowanego identyfikatora motywu.

<div id="from-metro"></div>

## Z Metro

Zapisz zrzut ekranu uruchomionej aplikacji z terminala:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Wszystkie opcje znajdziesz w [Metro Live](/docs/{{ $version }}/metro-live#screenshots). [Playbook](/docs/{{ $version }}/playbooks#screenshots) może robić zrzuty ekranu całej ścieżki, w każdym języku i motywie.
