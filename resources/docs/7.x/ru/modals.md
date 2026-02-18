# Modals

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Создание модального окна](#creating-a-modal "Создание модального окна")
- [Базовое использование](#basic-usage "Базовое использование")
- [Создание модального окна](#creating-a-modal "Создание модального окна")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Параметры](#parameters "Параметры")
  - [Действия](#actions "Действия")
  - [Заголовок](#header "Заголовок")
  - [Кнопка закрытия](#close-button "Кнопка закрытия")
  - [Пользовательское оформление](#custom-decoration "Пользовательское оформление")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Примеры](#examples "Примеры")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} предоставляет систему модальных окон, построенную на основе **нижних модальных окон**.

Класс `BottomSheetModal` даёт вам гибкий API для отображения оверлеев с содержимым, действиями, заголовками, кнопками закрытия и пользовательской стилизацией.

Модальные окна полезны для:
- Диалогов подтверждения (например, выход из аккаунта, удаление)
- Быстрых форм ввода
- Листов действий с несколькими вариантами
- Информационных оверлеев

<div id="creating-a-modal"></div>

## Создание модального окна

Вы можете создать новое модальное окно с помощью Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

Это создаёт два элемента:

1. **Виджет содержимого модального окна** в `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **Статический метод**, добавленный в ваш класс `BottomSheetModal` в `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

Затем вы можете отобразить модальное окно из любого места:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Если модальное окно с таким именем уже существует, используйте флаг `--force` для перезаписи:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Базовое использование

Отображение модального окна с помощью `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Создание модального окна

Рекомендуемый паттерн — создание класса `BottomSheetModal` со статическими методами для каждого типа модального окна. Шаблон предоставляет следующую структуру:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Вызов из любого места:

``` dart
BottomSheetModal.showLogout(context);

// С пользовательскими обратными вызовами
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Пользовательская логика выхода
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` — основной метод для отображения модальных окон.

<div id="parameters"></div>

### Параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `context` | `BuildContext` | обязательный | Контекст для модального окна |
| `child` | `Widget` | обязательный | Виджет основного содержимого |
| `actionsRow` | `List<Widget>` | `[]` | Виджеты действий в горизонтальном ряду |
| `actionsColumn` | `List<Widget>` | `[]` | Виджеты действий по вертикали |
| `height` | `double?` | null | Фиксированная высота модального окна |
| `header` | `Widget?` | null | Виджет заголовка сверху |
| `useSafeArea` | `bool` | `true` | Оборачивать содержимое в SafeArea |
| `isScrollControlled` | `bool` | `false` | Разрешить прокрутку модального окна |
| `showCloseButton` | `bool` | `false` | Показать кнопку закрытия X |
| `headerPadding` | `EdgeInsets?` | null | Отступы при наличии заголовка |
| `backgroundColor` | `Color?` | null | Цвет фона модального окна |
| `showHandle` | `bool` | `true` | Показать ручку перетаскивания сверху |
| `closeButtonColor` | `Color?` | null | Цвет фона кнопки закрытия |
| `closeButtonIconColor` | `Color?` | null | Цвет иконки кнопки закрытия |
| `modalDecoration` | `BoxDecoration?` | null | Пользовательское оформление контейнера |
| `handleColor` | `Color?` | null | Цвет ручки перетаскивания |

<div id="actions"></div>

### Действия

Действия — это кнопки, отображаемые в нижней части модального окна.

**Действия в ряд** размещаются рядом, каждое занимая равное пространство:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Действия в столбец** располагаются вертикально:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Заголовок

Добавление заголовка, расположенного над основным содержимым:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Кнопка закрытия

Отображение кнопки закрытия в правом верхнем углу:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Пользовательское оформление

Настройка внешнего вида контейнера модального окна:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` настраивает внешний вид нижних модальных окон, используемых при выборе в формах и других компонентах:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Свойство | Тип | Описание |
|----------|-----|----------|
| `backgroundColor` | `NyColor?` | Цвет фона модального окна |
| `barrierColor` | `NyColor?` | Цвет затемнения за модальным окном |
| `useRootNavigator` | `bool` | Использовать корневой навигатор (по умолчанию: `false`) |
| `routeSettings` | `RouteSettings?` | Настройки маршрута для модального окна |
| `titleStyle` | `TextStyle?` | Стиль текста заголовка |
| `itemStyle` | `TextStyle?` | Стиль текста элементов списка |
| `clearButtonStyle` | `TextStyle?` | Стиль текста кнопки очистки |

<div id="examples"></div>

## Примеры

### Модальное окно подтверждения

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Использование
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // удалить элемент
}
```

### Модальное окно с прокручиваемым содержимым

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Лист действий

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
