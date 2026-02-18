# Button

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Доступные типы кнопок](#button-types "Доступные типы кнопок")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Асинхронное состояние загрузки](#async-loading "Асинхронное состояние загрузки")
- [Стили анимации](#animation-styles "Стили анимации")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Стили всплеска](#splash-styles "Стили всплеска")
- [Стили загрузки](#loading-styles "Стили загрузки")
- [Отправка формы](#form-submission "Отправка формы")
- [Настройка кнопок](#customizing-buttons "Настройка кнопок")
- [Справочник параметров](#parameters "Справочник параметров")


<div id="introduction"></div>

## Введение

{{ config('app.name') }} предоставляет класс `Button` с восемью готовыми стилями кнопок из коробки. Каждая кнопка имеет встроенную поддержку:

- **Асинхронных состояний загрузки** -- верните `Future` из `onPressed`, и кнопка автоматически покажет индикатор загрузки
- **Стилей анимации** -- выбирайте из эффектов clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph и shake
- **Стилей всплеска** -- добавляйте обратную связь ripple, highlight, glow или ink при касании
- **Отправки формы** -- подключите кнопку напрямую к экземпляру `NyFormData`

Определения кнопок вашего приложения находятся в `lib/resources/widgets/buttons/buttons.dart`. Этот файл содержит класс `Button` со статическими методами для каждого типа кнопок, что упрощает настройку значений по умолчанию для вашего проекта.

<div id="basic-usage"></div>

## Базовое использование

Используйте класс `Button` в любом месте ваших виджетов. Вот простой пример на странице:

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

Каждый тип кнопки следует одному и тому же паттерну -- передайте текстовую метку `text` и обратный вызов `onPressed`.

<div id="button-types"></div>

## Доступные типы кнопок

Все кнопки доступны через класс `Button` с использованием статических методов.

<div id="primary"></div>

### Primary

Заполненная кнопка с тенью, использующая основной цвет вашей темы. Лучше всего подходит для главных элементов призыва к действию.

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

Заполненная кнопка с более мягким цветом поверхности и лёгкой тенью. Хорошо подходит для второстепенных действий рядом с основной кнопкой.

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

Прозрачная кнопка с обводкой. Полезна для менее заметных действий или кнопок отмены.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Вы можете настроить цвета обводки и текста:

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

Минимальная кнопка без фона и обводки. Идеальна для встроенных действий или ссылок.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Вы можете настроить цвет текста:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Заполненная кнопка, отображающая иконку рядом с текстом. По умолчанию иконка отображается перед текстом.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Вы можете настроить цвет фона:

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

Кнопка с линейным градиентным фоном. По умолчанию использует основной и третичный цвета вашей темы.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Вы можете указать пользовательские цвета градиента:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Кнопка в форме таблетки с полностью закруглёнными углами. Радиус скругления по умолчанию равен половине высоты кнопки.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Вы можете настроить цвет фона и радиус скругления:

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

Кнопка в стиле матового стекла с эффектом размытия фона. Хорошо работает при размещении поверх изображений или цветных фонов.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Вы можете настроить цвет текста:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Асинхронное состояние загрузки

Одна из самых мощных функций кнопок {{ config('app.name') }} -- **автоматическое управление состоянием загрузки**. Когда ваш обратный вызов `onPressed` возвращает `Future`, кнопка автоматически отображает индикатор загрузки и отключает взаимодействие до завершения операции.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Пока асинхронная операция выполняется, кнопка показывает эффект скелетной загрузки (по умолчанию). После завершения `Future` кнопка возвращается в нормальное состояние.

Это работает с любой асинхронной операцией -- API-вызовами, записью в базу данных, загрузкой файлов или чем-либо, что возвращает `Future`:

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

Нет необходимости управлять переменными состояния `isLoading`, вызывать `setState` или оборачивать что-либо в `StatefulWidget` -- {{ config('app.name') }} делает всё за вас.

### Как это работает

Когда кнопка обнаруживает, что `onPressed` возвращает `Future`, она использует механизм `lockRelease` для:

1. Отображения индикатора загрузки (управляется `LoadingStyle`)
2. Отключения кнопки для предотвращения повторных нажатий
3. Ожидания завершения `Future`
4. Восстановления кнопки в нормальное состояние

<div id="animation-styles"></div>

## Стили анимации

Кнопки поддерживают анимации нажатия через `ButtonAnimationStyle`. Эти анимации обеспечивают визуальную обратную связь при взаимодействии пользователя с кнопкой. Вы можете установить стиль анимации при настройке кнопок в `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

3D-эффект нажатия в стиле Duolingo. Кнопка перемещается вниз при нажатии и возвращается на место при отпускании. Лучше всего подходит для основных действий и игрового UX.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Тонкая настройка эффекта:

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

Уменьшает кнопку при нажатии и пружинит обратно при отпускании. Лучше всего подходит для кнопок добавления в корзину, лайков и избранного.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Тонкая настройка эффекта:

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

Лёгкая непрерывная пульсация масштаба, пока кнопка удерживается. Лучше всего подходит для действий долгого нажатия или привлечения внимания.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Тонкая настройка эффекта:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Сжимает кнопку по горизонтали и расширяет по вертикали при нажатии. Лучше всего подходит для игривых и интерактивных интерфейсов.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Тонкая настройка эффекта:

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

Колеблющийся эластичный эффект деформации. Лучше всего подходит для развлекательных и казуальных приложений.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Тонкая настройка эффекта:

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

Глянцевый блик, скользящий по кнопке при нажатии. Лучше всего подходит для премиальных функций или CTA, на которые вы хотите обратить внимание.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Тонкая настройка эффекта:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Улучшенный эффект ряби, расширяющийся из точки касания. Лучше всего подходит для акцентирования в стиле Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Тонкая настройка эффекта:

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

Радиус скругления кнопки увеличивается при нажатии, создавая эффект изменения формы. Лучше всего подходит для тонкой элегантной обратной связи.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Тонкая настройка эффекта:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Горизонтальная анимация встряхивания. Лучше всего подходит для состояний ошибки или недопустимых действий -- встряхните кнопку, чтобы сигнализировать о проблеме.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Тонкая настройка эффекта:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Отключение анимаций

Для использования кнопки без анимации:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Изменение анимации по умолчанию

Чтобы изменить анимацию по умолчанию для типа кнопки, отредактируйте файл `lib/resources/widgets/buttons/buttons.dart`:

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

## Стили всплеска

Эффекты всплеска обеспечивают визуальную обратную связь при касании кнопок. Настраивайте их через `ButtonSplashStyle`. Стили всплеска можно комбинировать со стилями анимации для многослойной обратной связи.

### Доступные стили всплеска

| Всплеск | Фабрика | Описание |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Стандартная рябь Material из точки касания |
| Highlight | `ButtonSplashStyle.highlight()` | Лёгкое выделение без анимации ряби |
| Glow | `ButtonSplashStyle.glow()` | Мягкое свечение, исходящее из точки касания |
| Ink | `ButtonSplashStyle.ink()` | Быстрый чернильный всплеск, более быстрый и отзывчивый |
| None | `ButtonSplashStyle.none()` | Без эффекта всплеска |
| Custom | `ButtonSplashStyle.custom()` | Полный контроль над фабрикой всплеска |

### Пример

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

Вы можете настроить цвета и прозрачность всплеска:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Стили загрузки

Индикатор загрузки, отображаемый во время асинхронных операций, управляется `LoadingStyle`. Вы можете установить его для каждого типа кнопки в файле кнопок.

### Skeletonizer (по умолчанию)

Отображает мерцающий скелетный эффект поверх кнопки:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Показывает виджет загрузки (по умолчанию -- загрузчик приложения):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Оставляет кнопку видимой, но отключает взаимодействие во время загрузки:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Отправка формы

Все кнопки поддерживают параметр `submitForm`, который подключает кнопку к `NyForm`. При нажатии кнопка проверит форму и вызовет обработчик успеха с данными формы.

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

Параметр `submitForm` принимает запись с двумя значениями:
1. Экземпляр `NyFormData` (или имя формы в виде `String`)
2. Обратный вызов, получающий проверенные данные

По умолчанию `showToastError` имеет значение `true`, что отображает всплывающее уведомление при ошибке валидации формы. Установите `false` для тихой обработки ошибок:

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

Когда обратный вызов `submitForm` возвращает `Future`, кнопка автоматически показывает состояние загрузки до завершения асинхронной операции.

<div id="customizing-buttons"></div>

## Настройка кнопок

Все значения по умолчанию для кнопок определены в вашем проекте по пути `lib/resources/widgets/buttons/buttons.dart`. Каждый тип кнопки имеет соответствующий класс виджета в `lib/resources/widgets/buttons/partials/`.

### Изменение стилей по умолчанию

Чтобы изменить внешний вид кнопки по умолчанию, отредактируйте класс `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
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

### Настройка виджета кнопки

Чтобы изменить визуальный вид типа кнопки, отредактируйте соответствующий виджет в `lib/resources/widgets/buttons/partials/`. Например, для изменения радиуса скругления или тени основной кнопки:

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

### Создание нового типа кнопки

Чтобы добавить новый тип кнопки:

1. Создайте новый файл виджета в `lib/resources/widgets/buttons/partials/`, наследующий `StatefulAppButton`.
2. Реализуйте метод `buildButton`.
3. Добавьте статический метод в класс `Button`.

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

Затем зарегистрируйте его в классе `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
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

## Справочник параметров

### Общие параметры (все типы кнопок)

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `text` | `String` | обязательный | Текстовая метка кнопки |
| `onPressed` | `VoidCallback?` | `null` | Обратный вызов при нажатии. Верните `Future` для автоматического состояния загрузки |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Запись отправки формы (экземпляр формы, обратный вызов успеха) |
| `onFailure` | `Function(dynamic)?` | `null` | Вызывается при ошибке валидации формы |
| `showToastError` | `bool` | `true` | Показать всплывающее уведомление при ошибке валидации формы |
| `width` | `double?` | `null` | Ширина кнопки (по умолчанию -- полная ширина) |

### Параметры для конкретных типов

#### Button.outlined

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Цвет обводки темы | Цвет обводки |
| `textColor` | `Color?` | Основной цвет темы | Цвет текста |

#### Button.textOnly

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Основной цвет темы | Цвет текста |

#### Button.icon

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `icon` | `Widget` | обязательный | Виджет иконки для отображения |
| `color` | `Color?` | Основной цвет темы | Цвет фона |

#### Button.gradient

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Основной и третичный цвета | Цветовые точки градиента |

#### Button.rounded

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Цвет основного контейнера темы | Цвет фона |
| `borderRadius` | `BorderRadius?` | Форма таблетки (высота / 2) | Радиус скругления |

#### Button.transparency

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `color` | `Color?` | Адаптивный к теме | Цвет текста |

### Параметры ButtonAnimationStyle

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Зависит от типа | Длительность анимации |
| `curve` | `Curve` | Зависит от типа | Кривая анимации |
| `enableHapticFeedback` | `bool` | Зависит от типа | Тактильная обратная связь при нажатии |
| `translateY` | `double` | `4.0` | Clickable: расстояние вертикального нажатия |
| `shadowOffset` | `double` | `4.0` | Clickable: глубина тени |
| `scaleMin` | `double` | `0.92` | Bounce: минимальный масштаб при нажатии |
| `pulseScale` | `double` | `1.05` | Pulse: максимальный масштаб при пульсации |
| `squeezeX` | `double` | `0.95` | Squeeze: горизонтальное сжатие |
| `squeezeY` | `double` | `1.05` | Squeeze: вертикальное расширение |
| `jellyStrength` | `double` | `0.15` | Jelly: интенсивность колебания |
| `shineColor` | `Color` | `Colors.white` | Shine: цвет блика |
| `shineWidth` | `double` | `0.3` | Shine: ширина полосы блика |
| `rippleScale` | `double` | `2.0` | Ripple: масштаб расширения |
| `morphRadius` | `double` | `24.0` | Morph: целевой радиус скругления |
| `shakeOffset` | `double` | `8.0` | Shake: горизонтальное смещение |
| `shakeCount` | `int` | `3` | Shake: количество колебаний |

### Параметры ButtonSplashStyle

| Параметр | Тип | По умолчанию | Описание |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Цвет поверхности темы | Цвет эффекта всплеска |
| `highlightColor` | `Color?` | Цвет поверхности темы | Цвет эффекта выделения |
| `splashOpacity` | `double` | `0.12` | Прозрачность всплеска |
| `highlightOpacity` | `double` | `0.06` | Прозрачность выделения |
| `borderRadius` | `BorderRadius?` | `null` | Радиус обрезки всплеска |
