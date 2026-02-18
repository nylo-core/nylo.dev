# Alerts

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Estilos Integrados](#built-in-styles "Estilos Integrados")
- [Exibindo Alertas a partir de Páginas](#from-pages "Exibindo Alertas a partir de Páginas")
- [Exibindo Alertas a partir de Controllers](#from-controllers "Exibindo Alertas a partir de Controllers")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Posicionamento](#positioning "Posicionamento")
- [Estilos de Toast Personalizados](#custom-styles "Estilos de Toast Personalizados")
  - [Registrando Estilos](#registering-styles "Registrando Estilos")
  - [Criando uma Style Factory](#creating-a-style-factory "Criando uma Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} fornece um sistema de notificação toast para exibir alertas aos usuários. Ele vem com quatro estilos integrados -- **success**, **warning**, **info** e **danger** -- e suporta estilos personalizados através de um padrão de registro.

Alertas podem ser acionados a partir de páginas, controllers ou de qualquer lugar onde você tenha um `BuildContext`.

<div id="basic-usage"></div>

## Uso Básico

Exiba uma notificação toast usando métodos de conveniência em qualquer página `NyState`:

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

Ou use a função global com um ID de estilo:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Estilos Integrados

{{ config('app.name') }} registra quatro estilos de toast padrão:

| ID do Estilo | Ícone | Cor | Título Padrão |
|----------|------|-------|---------------|
| `success` | Marca de verificação | Verde | Success |
| `warning` | Exclamação | Laranja | Warning |
| `info` | Ícone de info | Teal | Info |
| `danger` | Ícone de aviso | Vermelho | Error |

Estes são configurados em `lib/config/toast_notification.dart`:

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

## Exibindo Alertas a partir de Páginas

Em qualquer página que estenda `NyState` ou `NyBaseState`, use estes métodos de conveniência:

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

### Método de Toast Geral

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Exibindo Alertas a partir de Controllers

Controllers que estendem `NyController` possuem os mesmos métodos de conveniência:

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

Métodos disponíveis: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

A função global `showToastNotification()` exibe um toast de qualquer lugar onde você tenha um `BuildContext`:

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

### Parâmetros

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | obrigatório | Contexto de build |
| `id` | `String` | `'success'` | ID do estilo do toast |
| `title` | `String?` | null | Sobrescreve o título padrão |
| `description` | `String?` | null | Texto de descrição |
| `duration` | `Duration?` | null | Por quanto tempo o toast é exibido |
| `position` | `ToastNotificationPosition?` | null | Posição na tela |
| `action` | `VoidCallback?` | null | Callback ao tocar |
| `onDismiss` | `VoidCallback?` | null | Callback ao dispensar |
| `onShow` | `VoidCallback?` | null | Callback ao exibir |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` encapsula todos os dados para uma notificação toast:

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

### Propriedades

| Propriedade | Tipo | Padrão | Descrição |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget de ícone |
| `title` | `String` | `''` | Texto do título |
| `style` | `String` | `''` | Identificador do estilo |
| `description` | `String` | `''` | Texto de descrição |
| `color` | `Color?` | null | Cor de fundo para a seção do ícone |
| `action` | `VoidCallback?` | null | Callback ao tocar |
| `dismiss` | `VoidCallback?` | null | Callback do botão de dispensar |
| `onDismiss` | `VoidCallback?` | null | Callback ao dispensar automática/manualmente |
| `onShow` | `VoidCallback?` | null | Callback ao ficar visível |
| `duration` | `Duration` | 5 segundos | Duração da exibição |
| `position` | `ToastNotificationPosition` | `top` | Posição na tela |
| `metaData` | `Map<String, dynamic>?` | null | Metadados personalizados |

### copyWith

Crie uma cópia modificada de `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Posicionamento

Controle onde os toasts aparecem na tela:

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

## Estilos de Toast Personalizados

<div id="registering-styles"></div>

### Registrando Estilos

Registre estilos personalizados no seu `AppProvider`:

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

Ou adicione estilos a qualquer momento:

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

Depois use:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Criando uma Style Factory

`ToastNotification.style()` cria uma `ToastStyleFactory`:

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

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `icon` | `Widget` | Widget de ícone para o toast |
| `color` | `Color` | Cor de fundo para a seção do ícone |
| `defaultTitle` | `String` | Título exibido quando nenhum é fornecido |
| `position` | `ToastNotificationPosition?` | Posição padrão |
| `duration` | `Duration?` | Duração padrão |
| `builder` | `Widget Function(ToastMeta)?` | Builder de widget personalizado para controle total |

### Builder Totalmente Personalizado

Para controle completo sobre o widget do toast:

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

`AlertTab` é um widget de badge para adicionar indicadores de notificação em abas de navegação. Ele exibe um badge que pode ser alternado e opcionalmente persistido no armazenamento.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parâmetros

| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|---------|-------------|
| `state` | `String` | obrigatório | Nome do estado para rastreamento |
| `alertEnabled` | `bool?` | null | Se o badge é exibido |
| `rememberAlert` | `bool?` | `true` | Persistir estado do badge no armazenamento |
| `icon` | `Widget?` | null | Ícone da aba |
| `backgroundColor` | `Color?` | null | Fundo da aba |
| `textColor` | `Color?` | null | Cor do texto do badge |
| `alertColor` | `Color?` | null | Cor de fundo do badge |
| `smallSize` | `double?` | null | Tamanho pequeno do badge |
| `largeSize` | `double?` | null | Tamanho grande do badge |
| `textStyle` | `TextStyle?` | null | Estilo de texto do badge |
| `padding` | `EdgeInsetsGeometry?` | null | Preenchimento do badge |
| `alignment` | `Alignment?` | null | Alinhamento do badge |
| `offset` | `Offset?` | null | Deslocamento do badge |
| `isLabelVisible` | `bool?` | `true` | Exibir rótulo do badge |

### Construtor Factory

Crie a partir de um `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Exemplos

### Alerta de Sucesso Após Salvar

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

### Toast Interativo com Ação

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

### Aviso Posicionado na Parte Inferior

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
