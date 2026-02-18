# Alerts

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Встроенные стили](#built-in-styles "Встроенные стили")
- [Показ уведомлений со страниц](#from-pages "Показ уведомлений со страниц")
- [Показ уведомлений из контроллеров](#from-controllers "Показ уведомлений из контроллеров")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Позиционирование](#positioning "Позиционирование")
- [Пользовательские стили уведомлений](#custom-styles "Пользовательские стили уведомлений")
  - [Регистрация стилей](#registering-styles "Регистрация стилей")
  - [Создание фабрики стилей](#creating-a-style-factory "Создание фабрики стилей")
- [AlertTab](#alert-tab "AlertTab")
- [Примеры](#examples "Примеры")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} предоставляет систему всплывающих уведомлений (toast) для отображения оповещений пользователям. В комплекте идут четыре встроенных стиля -- **success**, **warning**, **info** и **danger** -- а также поддержка пользовательских стилей через паттерн реестра.

Уведомления можно вызывать со страниц, из контроллеров или из любого места, где доступен `BuildContext`.

<div id="basic-usage"></div>

## Базовое использование

Покажите всплывающее уведомление с помощью удобных методов на любой странице `NyState`:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

Или используйте глобальную функцию с идентификатором стиля:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Встроенные стили

{{ config('app.name') }} регистрирует четыре стиля уведомлений по умолчанию:

| ID стиля | Иконка | Цвет | Заголовок по умолчанию |
|----------|--------|------|------------------------|
| `success` | Галочка | Зелёный | Success |
| `warning` | Восклицательный знак | Оранжевый | Warning |
| `info` | Иконка информации | Бирюзовый | Info |
| `danger` | Иконка предупреждения | Красный | Error |

Они настраиваются в `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Показ уведомлений со страниц

На любой странице, наследующей `NyState` или `NyBaseState`, используйте следующие удобные методы:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Общий метод уведомлений

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Показ уведомлений из контроллеров

Контроллеры, наследующие `NyController`, имеют те же удобные методы:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Доступные методы: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

Глобальная функция `showToastNotification()` отображает уведомление из любого места, где доступен `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `context` | `BuildContext` | обязательный | Контекст сборки |
| `id` | `String` | `'success'` | ID стиля уведомления |
| `title` | `String?` | null | Переопределение заголовка по умолчанию |
| `description` | `String?` | null | Текст описания |
| `duration` | `Duration?` | null | Длительность отображения |
| `position` | `ToastNotificationPosition?` | null | Положение на экране |
| `action` | `VoidCallback?` | null | Обратный вызов при нажатии |
| `onDismiss` | `VoidCallback?` | null | Обратный вызов при закрытии |
| `onShow` | `VoidCallback?` | null | Обратный вызов при показе |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` инкапсулирует все данные для всплывающего уведомления:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Свойства

| Свойство | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `icon` | `Widget?` | null | Виджет иконки |
| `title` | `String` | `''` | Текст заголовка |
| `style` | `String` | `''` | Идентификатор стиля |
| `description` | `String` | `''` | Текст описания |
| `color` | `Color?` | null | Цвет фона секции иконки |
| `action` | `VoidCallback?` | null | Обратный вызов при нажатии |
| `dismiss` | `VoidCallback?` | null | Обратный вызов кнопки закрытия |
| `onDismiss` | `VoidCallback?` | null | Обратный вызов при автоматическом/ручном закрытии |
| `onShow` | `VoidCallback?` | null | Обратный вызов при отображении |
| `duration` | `Duration` | 5 секунд | Длительность отображения |
| `position` | `ToastNotificationPosition` | `top` | Положение на экране |
| `metaData` | `Map<String, dynamic>?` | null | Пользовательские метаданные |

### copyWith

Создание модифицированной копии `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Позиционирование

Управляйте расположением уведомлений на экране:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Пользовательские стили уведомлений

<div id="registering-styles"></div>

### Регистрация стилей

Зарегистрируйте пользовательские стили в вашем `AppProvider`:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Или добавьте стили в любой момент:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Затем используйте:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Создание фабрики стилей

`ToastNotification.style()` создаёт `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Параметр | Тип | Описание |
|----------|-----|----------|
| `icon` | `Widget` | Виджет иконки для уведомления |
| `color` | `Color` | Цвет фона секции иконки |
| `defaultTitle` | `String` | Заголовок, отображаемый если не указан другой |
| `position` | `ToastNotificationPosition?` | Положение по умолчанию |
| `duration` | `Duration?` | Длительность по умолчанию |
| `builder` | `Widget Function(ToastMeta)?` | Пользовательский построитель виджета для полного контроля |

### Полностью пользовательский построитель

Для полного контроля над виджетом уведомления:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` -- это виджет-значок для добавления индикаторов уведомлений на вкладки навигации. Он отображает значок, который можно переключать и при необходимости сохранять в хранилище.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Параметры

| Параметр | Тип | По умолчанию | Описание |
|----------|-----|--------------|----------|
| `state` | `String` | обязательный | Имя состояния для отслеживания |
| `alertEnabled` | `bool?` | null | Показывать ли значок |
| `rememberAlert` | `bool?` | `true` | Сохранять состояние значка в хранилище |
| `icon` | `Widget?` | null | Иконка вкладки |
| `backgroundColor` | `Color?` | null | Фон вкладки |
| `textColor` | `Color?` | null | Цвет текста значка |
| `alertColor` | `Color?` | null | Цвет фона значка |
| `smallSize` | `double?` | null | Размер малого значка |
| `largeSize` | `double?` | null | Размер большого значка |
| `textStyle` | `TextStyle?` | null | Стиль текста значка |
| `padding` | `EdgeInsetsGeometry?` | null | Отступы значка |
| `alignment` | `Alignment?` | null | Выравнивание значка |
| `offset` | `Offset?` | null | Смещение значка |
| `isLabelVisible` | `bool?` | `true` | Показывать метку значка |

### Фабричный конструктор

Создание из `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Примеры

### Уведомление об успехе после сохранения

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Интерактивное уведомление с действием

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Предупреждение внизу экрана

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
