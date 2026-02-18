# Assets

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos assets")
- Arquivos
  - [Exibindo Imagens](#displaying-images "Exibindo imagens")
  - [Caminhos de Assets Personalizados](#custom-asset-paths "Caminhos de assets personalizados")
  - [Retornando Caminhos de Assets](#returning-asset-paths "Retornando caminhos de assets")
- Gerenciando Assets
  - [Adicionando Novos Arquivos](#adding-new-files "Adicionando novos arquivos")
  - [Configuração de Assets](#asset-configuration "Configuração de assets")

<div id="introduction"></div>

## Introdução

{{ config('app.name') }} v7 fornece métodos auxiliares para gerenciar assets no seu app Flutter. Assets são armazenados no diretório `assets/` e incluem imagens, vídeos, fontes e outros arquivos.

A estrutura padrão de assets:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Exibindo Imagens

Use o widget `LocalAsset()` para exibir imagens dos assets:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Ambos os métodos retornam o caminho completo do asset incluindo o diretório de assets configurado.

<div id="custom-asset-paths"></div>

## Caminhos de Assets Personalizados

Para suportar diferentes subdiretórios de assets, você pode adicionar construtores personalizados ao widget `LocalAsset`.

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## Retornando Caminhos de Assets

Use `getAsset()` para qualquer tipo de arquivo no diretório `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Usando com Diversos Widgets

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Adicionando Novos Arquivos

1. Coloque seus arquivos no subdiretório apropriado de `assets/`:
   - Imagens: `assets/images/`
   - Vídeos: `assets/videos/`
   - Fontes: `assets/fonts/`
   - Outros: `assets/data/` ou pastas personalizadas

2. Certifique-se de que a pasta está listada no `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Configuração de Assets

{{ config('app.name') }} v7 configura o caminho de assets através da variável de ambiente `ASSET_PATH` no seu arquivo `.env`:

``` bash
ASSET_PATH="assets"
```

As funções auxiliares adicionam automaticamente este caminho como prefixo, então você não precisa incluir `assets/` nas suas chamadas:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Alterando o Caminho Base

Se você precisar de uma estrutura de assets diferente, atualize `ASSET_PATH` no seu `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

Após a alteração, regenere a configuração do seu ambiente:

``` bash
metro make:env --force
```
