# Navigation Hub

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
  - [Создание Navigation Hub](#creating-a-navigation-hub "Создание Navigation Hub")
  - [Создание вкладок навигации](#creating-navigation-tabs "Создание вкладок навигации")
  - [Нижняя навигация](#bottom-navigation "Нижняя навигация")
    - [Пользовательский конструктор панели навигации](#custom-nav-bar-builder "Пользовательский конструктор панели навигации")
  - [Верхняя навигация](#top-navigation "Верхняя навигация")
  - [Навигация Journey](#journey-navigation "Навигация Journey")
    - [Стили прогресса](#journey-progress-styles "Стили прогресса Journey")
    - [JourneyState](#journey-state "JourneyState")
    - [Вспомогательные методы JourneyState](#journey-state-helper-methods "Вспомогательные методы JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Навигация внутри вкладки](#navigating-within-a-tab "Навигация внутри вкладки")
- [Вкладки](#tabs "Вкладки")
  - [Добавление значков к вкладкам](#adding-badges-to-tabs "Добавление значков к вкладкам")
  - [Добавление оповещений к вкладкам](#adding-alerts-to-tabs "Добавление оповещений к вкладкам")
- [Начальный индекс](#initial-index "Начальный индекс")
- [Сохранение состояния](#maintaining-state "Сохранение состояния")
- [onTap](#on-tap "onTap")
- [Действия состояния](#state-actions "Действия состояния")
- [Стиль загрузки](#loading-style "Стиль загрузки")

<div id="introduction"></div>

## Введение

Navigation Hub -- это центральное место, где вы можете **управлять** навигацией для всех ваших виджетов.
Из коробки вы можете создавать макеты с нижней, верхней и пошаговой (journey) навигацией за считанные секунды.

Давайте **представим**, что у вас есть приложение и вы хотите добавить нижнюю панель навигации, позволяющую пользователям переключаться между различными вкладками.

Для этого вы можете использовать Navigation Hub.

Давайте разберёмся, как использовать Navigation Hub в вашем приложении.

<div id="basic-usage"></div>

## Базовое использование

Вы можете создать Navigation Hub с помощью следующей команды.

``` bash
metro make:navigation_hub base
```

Команда проведёт вас через интерактивную настройку:

1. **Выберите тип макета** -- выберите между `navigation_tabs` (нижняя навигация) или `journey_states` (последовательный поток).
2. **Введите названия вкладок/состояний** -- укажите названия через запятую для ваших вкладок или состояний journey.

Это создаст файлы в директории `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` -- основной виджет хаба
- `tabs/` или `states/` -- содержит дочерние виджеты для каждой вкладки или состояния journey

Вот как выглядит сгенерированный Navigation Hub:

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

Как видите, Navigation Hub содержит **две** вкладки: Home и Settings.

Метод `layout` возвращает тип макета для хаба. Он принимает `BuildContext`, чтобы вы могли получить доступ к данным темы и медиазапросам при настройке макета.

Вы можете создать дополнительные вкладки, добавив `NavigationTab` в Navigation Hub.

Сначала нужно создать новый виджет с помощью Metro.

``` bash
metro make:stateful_widget news_tab
```

Вы также можете создать несколько виджетов одновременно.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Затем вы можете добавить новый виджет в Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Чтобы использовать Navigation Hub, добавьте его в ваш маршрутизатор в качестве начального маршрута:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// или перейдите к Navigation Hub из любого места приложения

routeTo(BaseNavigationHub.path);
```

С Navigation Hub можно сделать **гораздо больше**, давайте рассмотрим некоторые возможности.

<div id="bottom-navigation"></div>

### Нижняя навигация

Вы можете установить макет нижней панели навигации, вернув `NavigationHubLayout.bottomNav` из метода `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Вы можете настроить нижнюю панель навигации, установив следующие свойства:

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

Вы можете применить готовый стиль к нижней панели навигации с помощью параметра `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Пользовательский конструктор панели навигации

Для полного контроля над панелью навигации вы можете использовать параметр `navBarBuilder`.

Это позволяет создать любой пользовательский виджет, получая при этом данные навигации.

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

Объект `NavBarData` содержит:

| Свойство | Тип | Описание |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Элементы панели навигации |
| `currentIndex` | `int` | Текущий выбранный индекс |
| `onTap` | `ValueChanged<int>` | Обратный вызов при нажатии на вкладку |

Вот пример полностью пользовательской стеклянной панели навигации:

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

> **Примечание:** При использовании `navBarBuilder` параметр `style` игнорируется.

<div id="top-navigation"></div>

### Верхняя навигация

Вы можете изменить макет на верхнюю панель навигации, вернув `NavigationHubLayout.topNav` из метода `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Вы можете настроить верхнюю панель навигации, установив следующие свойства:

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

### Навигация Journey

Вы можете изменить макет на пошаговую навигацию journey, вернув `NavigationHubLayout.journey` из метода `layout`.

Это отлично подходит для процессов онбординга или многошаговых форм.

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

Вы также можете задать `backgroundGradient` для макета journey:

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

> **Примечание:** Когда установлен `backgroundGradient`, он имеет приоритет над `backgroundColor`.

Если вы хотите использовать макет навигации journey, ваши **виджеты** должны использовать `JourneyState`, так как он содержит множество вспомогательных методов для управления пошаговым процессом.

Вы можете создать весь journey с помощью команды `make:navigation_hub` с макетом `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Это создаст хаб и все виджеты состояний journey в `resources/pages/navigation_hubs/onboarding/states/`.

Или вы можете создать отдельные виджеты journey с помощью:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Затем вы можете добавить новые виджеты в Navigation Hub.

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

### Стили прогресса Journey

Вы можете настроить стиль индикатора прогресса с помощью класса `JourneyProgressStyle`.

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

Вы можете использовать следующие индикаторы прогресса:

- `JourneyProgressIndicator.none()`: Ничего не отображает -- полезно для скрытия индикатора на определённой вкладке.
- `JourneyProgressIndicator.linear()`: Линейный индикатор прогресса.
- `JourneyProgressIndicator.dots()`: Индикатор прогресса на основе точек.
- `JourneyProgressIndicator.numbered()`: Нумерованный пошаговый индикатор.
- `JourneyProgressIndicator.segments()`: Сегментированный индикатор прогресса.
- `JourneyProgressIndicator.circular()`: Круговой индикатор прогресса.
- `JourneyProgressIndicator.timeline()`: Индикатор прогресса в стиле временной шкалы.
- `JourneyProgressIndicator.custom()`: Пользовательский индикатор прогресса с использованием функции-конструктора.

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

Вы можете настроить позицию и отступы индикатора прогресса внутри `JourneyProgressStyle`:

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

Вы можете использовать следующие позиции индикатора прогресса:

- `ProgressIndicatorPosition.top`: Индикатор прогресса в верхней части экрана.
- `ProgressIndicatorPosition.bottom`: Индикатор прогресса в нижней части экрана.

#### Переопределение стиля прогресса для отдельной вкладки

Вы можете переопределить `progressStyle` на уровне макета для отдельных вкладок, используя `NavigationTab.journey(progressStyle: ...)`. Вкладки без собственного `progressStyle` наследуют значение по умолчанию из макета. Вкладки без значения по умолчанию макета и без собственного стиля не будут отображать индикатор прогресса.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // overrides the layout default for this tab only
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

Класс `JourneyState` расширяет `NyState` функциональностью, специфичной для пошаговых процессов, упрощая создание потоков онбординга и многошаговых journey.

Чтобы создать новый `JourneyState`, вы можете использовать следующую команду.

``` bash
metro make:journey_widget onboard_user_dob
```

Или если вы хотите создать несколько виджетов одновременно, используйте следующую команду.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Вот как выглядит сгенерированный виджет JourneyState:

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
    // Your initialization logic here
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

Обратите внимание, что класс **JourneyState** использует `nextStep` для перехода вперёд и `onBackPressed` для возврата назад.

Метод `nextStep` проходит через полный жизненный цикл валидации: `canContinue()` -> `onBeforeNext()` -> навигация (или `onComplete()` на последнем шаге) -> `onAfterNext()`.

Вы также можете использовать `buildJourneyContent` для построения структурированного макета с опциональными кнопками навигации:

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

Вот свойства, которые вы можете использовать в методе `buildJourneyContent`.

| Свойство | Тип | Описание |
| --- | --- | --- |
| `content` | `Widget` | Основное содержимое страницы. |
| `nextButton` | `Widget?` | Виджет кнопки «Далее». |
| `backButton` | `Widget?` | Виджет кнопки «Назад». |
| `contentPadding` | `EdgeInsetsGeometry` | Отступы для содержимого. |
| `header` | `Widget?` | Виджет заголовка. |
| `footer` | `Widget?` | Виджет нижнего колонтитула. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Выравнивание по поперечной оси содержимого. |

<div id="journey-state-helper-methods"></div>

### Вспомогательные методы JourneyState

Класс `JourneyState` содержит вспомогательные методы и свойства, которые вы можете использовать для настройки поведения вашего journey.

| Метод / Свойство | Описание |
| --- | --- |
| [`nextStep()`](#next-step) | Переход к следующему шагу с валидацией. Возвращает `Future<bool>`. |
| [`previousStep()`](#previous-step) | Переход к предыдущему шагу. Возвращает `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Простой хелпер для перехода к предыдущему шагу. |
| [`onComplete()`](#on-complete) | Вызывается при завершении journey (на последнем шаге). |
| [`onBeforeNext()`](#on-before-next) | Вызывается перед переходом к следующему шагу. |
| [`onAfterNext()`](#on-after-next) | Вызывается после перехода к следующему шагу. |
| [`canContinue()`](#can-continue) | Проверка валидации перед переходом к следующему шагу. |
| [`isFirstStep`](#is-first-step) | Возвращает true, если это первый шаг в journey. |
| [`isLastStep`](#is-last-step) | Возвращает true, если это последний шаг в journey. |
| [`currentStep`](#current-step) | Возвращает индекс текущего шага (начиная с 0). |
| [`totalSteps`](#total-steps) | Возвращает общее количество шагов. |
| [`completionPercentage`](#completion-percentage) | Возвращает процент выполнения (от 0.0 до 1.0). |
| [`goToStep(int index)`](#go-to-step) | Переход к определённому шагу по индексу. |
| [`goToNextStep()`](#go-to-next-step) | Переход к следующему шагу (без валидации). |
| [`goToPreviousStep()`](#go-to-previous-step) | Переход к предыдущему шагу (без валидации). |
| [`goToFirstStep()`](#go-to-first-step) | Переход к первому шагу. |
| [`goToLastStep()`](#go-to-last-step) | Переход к последнему шагу. |
| [`exitJourney()`](#exit-journey) | Выход из journey через pop корневого навигатора. |
| [`resetCurrentStep()`](#reset-current-step) | Сброс состояния текущего шага. |
| [`onJourneyComplete`](#on-journey-complete) | Обратный вызов при завершении journey (переопределяется на последнем шаге). |
| [`buildJourneyPage()`](#build-journey-page) | Построение полноэкранной страницы journey с Scaffold. |


<div id="next-step"></div>

#### nextStep

Метод `nextStep` выполняет переход к следующему шагу с полной валидацией. Он проходит через жизненный цикл: `canContinue()` -> `onBeforeNext()` -> навигация или `onComplete()` -> `onAfterNext()`.

Вы можете передать `force: true`, чтобы обойти валидацию и перейти напрямую.

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
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

Чтобы пропустить валидацию:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Метод `previousStep` выполняет переход к предыдущему шагу. Возвращает `true` в случае успеха, `false`, если уже на первом шаге.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

Метод `onBackPressed` -- это простой хелпер, который внутренне вызывает `previousStep()`.

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

Метод `onComplete` вызывается, когда `nextStep()` срабатывает на последнем шаге (после прохождения валидации).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Метод `onBeforeNext` вызывается перед переходом к следующему шагу.

Например, если вы хотите сохранить данные перед переходом к следующему шагу, вы можете сделать это здесь.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

Метод `onAfterNext` вызывается после перехода к следующему шагу.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Метод `canContinue` вызывается при срабатывании `nextStep()`. Верните `false`, чтобы предотвратить переход.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

Свойство `isFirstStep` возвращает true, если это первый шаг в journey.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Свойство `isLastStep` возвращает true, если это последний шаг в journey.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Свойство `currentStep` возвращает индекс текущего шага (начиная с 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Свойство `totalSteps` возвращает общее количество шагов в journey.

<div id="completion-percentage"></div>

#### completionPercentage

Свойство `completionPercentage` возвращает процент выполнения как значение от 0.0 до 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Метод `goToStep` выполняет прямой переход к определённому шагу по индексу. Этот метод **не** запускает валидацию.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

Метод `goToNextStep` переходит к следующему шагу без валидации. Если уже на последнем шаге, ничего не происходит.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Метод `goToPreviousStep` переходит к предыдущему шагу без валидации. Если уже на первом шаге, ничего не происходит.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Метод `goToFirstStep` переходит к первому шагу.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Метод `goToLastStep` переходит к последнему шагу.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Метод `exitJourney` выходит из journey через pop корневого навигатора.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Метод `resetCurrentStep` сбрасывает состояние текущего шага.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Геттер `onJourneyComplete` может быть переопределён на **последнем шаге** вашего journey для определения действий при завершении пользователем потока.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
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
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

Метод `buildJourneyPage` создаёт полноэкранную страницу journey, обёрнутую в `Scaffold` с `SafeArea`.

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

| Свойство | Тип | Описание |
| --- | --- | --- |
| `content` | `Widget` | Основное содержимое страницы. |
| `nextButton` | `Widget?` | Виджет кнопки «Далее». |
| `backButton` | `Widget?` | Виджет кнопки «Назад». |
| `contentPadding` | `EdgeInsetsGeometry` | Отступы для содержимого. |
| `header` | `Widget?` | Виджет заголовка. |
| `footer` | `Widget?` | Виджет нижнего колонтитула. |
| `backgroundColor` | `Color?` | Цвет фона Scaffold. |
| `appBar` | `Widget?` | Опциональный виджет AppBar. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Выравнивание по поперечной оси содержимого. |

<div id="navigating-within-a-tab"></div>

## Навигация к виджетам внутри вкладки

Вы можете переходить к виджетам внутри вкладки, используя хелпер `pushTo`.

Внутри вашей вкладки вы можете использовать хелпер `pushTo` для перехода к другому виджету.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Вы также можете передавать данные в виджет, к которому переходите.

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

## Вкладки

Вкладки -- это основные строительные блоки Navigation Hub.

Вы можете добавлять вкладки в Navigation Hub с помощью класса `NavigationTab` и его именованных конструкторов.

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

В приведённом примере мы добавили две вкладки в Navigation Hub: Home и Settings.

Вы можете использовать различные типы вкладок:

- `NavigationTab.tab()` -- стандартная вкладка навигации.
- `NavigationTab.badge()` -- вкладка со счётчиком значка.
- `NavigationTab.alert()` -- вкладка с индикатором оповещения.
- `NavigationTab.journey()` -- вкладка для макетов навигации journey.

<div id="adding-badges-to-tabs"></div>

## Добавление значков к вкладкам

Мы упростили добавление значков к вашим вкладкам.

Значки -- это отличный способ показать пользователям, что во вкладке появилось что-то новое.

Например, если у вас приложение-чат, вы можете отображать количество непрочитанных сообщений на вкладке чата.

Чтобы добавить значок к вкладке, используйте конструктор `NavigationTab.badge`.

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

В приведённом примере мы добавили значок к вкладке Chat с начальным счётчиком 10.

Вы также можете обновлять счётчик значка программно.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

По умолчанию счётчик значка запоминается. Если вы хотите **сбрасывать** счётчик при каждой сессии, установите `rememberCount` в `false`.

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

## Добавление оповещений к вкладкам

Вы можете добавлять оповещения к вашим вкладкам.

Иногда вы можете не хотеть показывать счётчик значка, но хотите отобразить индикатор оповещения для пользователя.

Чтобы добавить оповещение к вкладке, используйте конструктор `NavigationTab.alert`.

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

Это добавит оповещение к вкладке Chat красного цвета.

Вы также можете обновлять оповещение программно.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Начальный индекс

По умолчанию Navigation Hub начинается с первой вкладки (индекс 0). Вы можете изменить это, переопределив геттер `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Сохранение состояния

По умолчанию состояние Navigation Hub сохраняется.

Это означает, что при переходе на вкладку её состояние сохраняется.

Если вы хотите сбрасывать состояние вкладки при каждом переходе на неё, установите `maintainState` в `false`.

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

Вы можете переопределить метод `onTap`, чтобы добавить пользовательскую логику при нажатии на вкладку.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## Действия состояния

Действия состояния -- это способ взаимодействия с Navigation Hub из любого места вашего приложения.

Вот доступные действия состояния:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Для использования действия состояния выполните следующее:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Стиль загрузки

Из коробки Navigation Hub будет отображать ваш виджет загрузки **по умолчанию** (resources/widgets/loader_widget.dart) во время загрузки вкладки.

Вы можете настроить `loadingStyle` для изменения стиля загрузки.

| Стиль | Описание |
| --- | --- |
| normal | Стиль загрузки по умолчанию |
| skeletonizer | Скелетонный стиль загрузки |
| none | Без стиля загрузки |

Вы можете изменить стиль загрузки следующим образом:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Если вы хотите обновить виджет загрузки в одном из стилей, вы можете передать `child` в `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Теперь при загрузке вкладки будет отображаться текст "Loading...".

Пример ниже:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

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

## Создание Navigation Hub

Чтобы создать Navigation Hub, вы можете использовать [Metro](/docs/{{$version}}/metro), выполните следующую команду.

``` bash
metro make:navigation_hub base
```

Команда проведёт вас через интерактивную настройку, где вы сможете выбрать тип макета и определить ваши вкладки или состояния journey.

Это создаст файл `base_navigation_hub.dart` в директории `resources/pages/navigation_hubs/base/` с дочерними виджетами, организованными в подпапках `tabs/` или `states/`.
