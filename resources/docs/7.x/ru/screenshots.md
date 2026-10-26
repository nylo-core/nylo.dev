# Скриншоты
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в скриншоты")
- [Снимок экрана](#capturing-the-screen "Снимок всего экрана")
- [Снимок области](#capturing-an-area "Снимок одной части экрана с помощью ScreenshotArea")
- [Сохранение скриншота](#saving-a-screenshot "Сохранение скриншота на диск")
- [Страницы в магазинах](#store-listings "Подгонка скриншотов под App Store и Google Play")
  - [Размеры магазинов](#store-sizes "Размеры ScreenshotStore")
- [Скрытие содержимого](#hiding-content "Скрытие содержимого со скриншотов")
- [Снимок виджета](#capturing-a-widget "Снимок виджета без его показа")
- [Локали и темы](#locales-and-themes "Снимок в другой локали или теме")
- [Из Metro](#from-metro "Создание скриншотов из Metro")

<div id="introduction"></div>

## Введение

<x-doc-strip label="Требуется" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` делает PNG экрана вашего приложения или одной его части:

``` dart
// Весь экран, включая диалоги и панели
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Одна часть экрана
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Снимки работают в каждом режиме сборки, поэтому могут лежать в основе функций вашего приложения, например отправки чека или прикрепления экрана к отчёту об ошибке. [Metro](#from-metro) и [плейбуки](/docs/{{ $version }}/playbooks#screenshots) используют тот же код.

`Screenshot` хранит PNG как `bytes`, а его `width` и `height` -- в физических пикселях.

<div id="capturing-the-screen"></div>

## Снимок экрана

`Screenshot.capture()` считывает весь экран приложения таким, каким его видит пользователь:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Параметр | Описание |
|----------|----------|
| `pixelRatio` | Размер относительно логических пикселей. По умолчанию равен значению устройства, поэтому PNG имеет собственное разрешение экрана |
| `settle` | Дождаться следующего кадра перед снимком (по умолчанию `true`). Передайте `false`, если кадр только что был отрисован |
| `locale` | Показать экран на этом языке на время снимка, например `'es'` |
| `theme` | Показать экран в теме с этим id на время снимка, например `'dark'` |

Приложение, собранное с `NyApp.materialApp`, снимает ровно свои страницы. Если вы создаёте собственный `MaterialApp`, добавьте `ScreenshotRoot` в его `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Снимок области

Оберните часть экрана в `ScreenshotArea` и дайте ей имя:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Затем снимите её по этому имени:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` принимает те же параметры `pixelRatio`, `settle`, `locale` и `theme`, что и `capture`. `Screenshot.areas` перечисляет имена областей на экране.

<div id="saving-a-screenshot"></div>

## Сохранение скриншота

`store` сохраняет PNG на диск файлового хранилища и возвращает путь:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Без `disk` он сохраняет на диск по умолчанию. Чтобы поделиться изображением или загрузить его, читайте `shot.bytes`.

<div id="store-listings"></div>

## Страницы в магазинах

`forStore` возвращает новый скриншот точно того размера, который требует магазин приложений:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Экран почти той же формы заполняет размер целиком, а любой другой вписывается внутрь. С `frame: true` экран рисуется в контуре телефона под `caption`.

| Параметр | Описание |
|----------|----------|
| `frame` | Нарисовать экран внутри контура телефона |
| `caption` | Текст над телефоном, используется с `frame` |
| `background` | `Color` вокруг экрана |

Используйте `framed()`, чтобы нарисовать контур в собственном размере скриншота:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Размеры магазинов

| Размер | Id | Пиксели |
|--------|----|---------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` перечисляет все размеры, а `ScreenshotStore.named('ios-6.9')` находит один по его id.

<div id="hiding-content"></div>

## Скрытие содержимого

Оберните всё приватное в `ScreenshotHidden`. На экране оно отображается как обычно, а на каждом скриншоте его закрывает серый блок:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Передайте `replacement`, чтобы нарисовать собственную заглушку вместо серого блока.

<div id="capturing-a-widget"></div>

## Снимок виджета

`Screenshot.widget` снимает виджет, не показывая его, например карточку для публикации, созданную только ради изображения:

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

PNG имеет такой же размер, как виджет, поэтому оберните его в `SizedBox` для точного размера. Передайте `context`, чтобы дать виджету тему и локализации этого контекста, `size` -- чтобы задать пространство, в котором он компонуется, и `delay` -- чтобы дать изображениям время загрузиться.

<div id="locales-and-themes"></div>

## Локали и темы

Передайте `locale` или `theme`, чтобы показать экран иначе для одного снимка:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

Приложение переключается на месте, сохраняя страницу, её состояние и стек навигации, и сразу возвращается обратно. Ничего не сохраняется, поэтому собственный выбор пользователя остаётся. Для локали нужен языковой файл в ваших ресурсах, а для темы -- зарегистрированный id темы.

<div id="from-metro"></div>

## Из Metro

Сохраните скриншот вашего запущенного приложения из терминала:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Все опции см. в [Metro Live](/docs/{{ $version }}/metro-live#screenshots). [Плейбук](/docs/{{ $version }}/playbooks#screenshots) может делать скриншоты всего сценария на каждом языке и в каждой теме.
