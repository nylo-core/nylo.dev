# App Icons

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Gerando Ícones do App](#generating-app-icons "Gerando ícones do app")
- [Adicionando Seu Ícone do App](#adding-your-app-icon "Adicionando seu ícone do app")
- [Requisitos do Ícone do App](#app-icon-requirements "Requisitos do ícone do app")
- [Configuração](#configuration "Configuração")
- [Contagem de Badge](#badge-count "Contagem de badge")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 usa <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> para gerar ícones do app para iOS e Android a partir de uma única imagem de origem.

O ícone do seu app deve ser colocado no diretório `assets/app_icon/` com um tamanho de **1024x1024 pixels**.

<div id="generating-app-icons"></div>

## Gerando Ícones do App

Execute o seguinte comando para gerar ícones para todas as plataformas:

``` bash
dart run flutter_launcher_icons
```

Isso lê o ícone de origem de `assets/app_icon/` e gera:
- Ícones iOS em `ios/Runner/Assets.xcassets/AppIcon.appiconset/`
- Ícones Android em `android/app/src/main/res/mipmap-*/`

<div id="adding-your-app-icon"></div>

## Adicionando Seu Ícone do App

1. Crie seu ícone como um arquivo **PNG de 1024x1024**
2. Coloque-o em `assets/app_icon/` (ex: `assets/app_icon/icon.png`)
3. Atualize o `image_path` no seu `pubspec.yaml` se necessário:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Execute o comando de geração de ícones

<div id="app-icon-requirements"></div>

## Requisitos do Ícone do App

| Atributo | Valor |
|-----------|-------|
| Formato | PNG |
| Tamanho | 1024x1024 pixels |
| Camadas | Achatado sem transparência |

### Nomenclatura de Arquivos

Mantenha os nomes de arquivos simples sem caracteres especiais:
- `app_icon.png`
- `icon.png`

### Diretrizes da Plataforma

Para requisitos detalhados, consulte as diretrizes oficiais da plataforma:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple Human Interface Guidelines - App Icons</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Icon Design Specifications</a>

<div id="configuration"></div>

## Configuração

Personalize a geração de ícones no seu `pubspec.yaml`:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

Veja a <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">documentação do flutter_launcher_icons</a> para todas as opções disponíveis.

<div id="badge-count"></div>

## Contagem de Badge

{{ config('app.name') }} fornece funções auxiliares para gerenciar contagens de badge do app (o número exibido no ícone do app):

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Suporte por Plataforma

Contagens de badge são suportadas em:
- **iOS**: Suporte nativo
- **Android**: Requer suporte do launcher (a maioria dos launchers suporta)
- **Web**: Não suportado

### Casos de Uso

Cenários comuns para contagens de badge:
- Notificações não lidas
- Mensagens pendentes
- Itens no carrinho
- Tarefas incompletas

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```
