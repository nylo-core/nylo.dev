# Requirements

---

<a name="section-1"></a>
- [Requisitos do Sistema](#system-requirements "Requisitos do Sistema")
- [Instalando o Flutter](#installing-flutter "Instalando o Flutter")
- [Verificando sua Instalação](#verifying-installation "Verificando sua Instalação")
- [Configurar um Editor](#set-up-an-editor "Configurar um Editor")
  - [Plugins do Nylo para Editores](#nylo-editor-plugins "Plugins do Nylo para Editores")


<div id="system-requirements"></div>

## Requisitos do Sistema

<x-doc-strip label="Visão geral" items="Flutter 3.38.4+, Dart SDK 3.10.7+, Todas as plataformas Flutter" linkText="Instalar Flutter" linkHref="https://docs.flutter.dev/get-started/install" />

{{ config('app.name') }} v7 requer as seguintes versões mínimas:

| Requisito | Versão Mínima |
|-------------|-----------------|
| **Flutter** | 3.38.4 ou superior |
| **Dart SDK** | 3.10.7 ou superior |

### Suporte a Plataformas

{{ config('app.name') }} suporta todas as plataformas que o Flutter suporta:

| Plataforma | Suporte |
|----------|---------|
| iOS | ✓ Suporte completo |
| Android | ✓ Suporte completo |
| Web | ✓ Suporte completo |
| macOS | ✓ Suporte completo |
| Windows | ✓ Suporte completo |
| Linux | ✓ Suporte completo |

<div id="installing-flutter"></div>

## Instalando o Flutter

Se você não tem o Flutter instalado, siga o guia oficial de instalação para o seu sistema operacional:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Guia de Instalação do Flutter</a>

<div id="verifying-installation"></div>

## Verificando sua Instalação

Após instalar o Flutter, verifique sua configuração:

### Verificar a Versão do Flutter

``` bash
flutter --version
```

Você deverá ver uma saída semelhante a:

```
Flutter 3.38.4 • channel stable
Dart SDK version: 3.10.7
```

### Atualizar o Flutter (se necessário)

Se sua versão do Flutter está abaixo de 3.38.4, atualize para a versão estável mais recente:

``` bash
flutter channel stable
flutter upgrade
```

### Executar o Flutter Doctor

Verifique se seu ambiente de desenvolvimento está configurado corretamente:

``` bash
flutter doctor -v
```

Este comando verifica:
- Instalação do Flutter SDK
- Toolchain do Android (para desenvolvimento Android)
- Xcode (para desenvolvimento iOS/macOS)
- Dispositivos conectados
- Plugins do IDE

Corrija quaisquer problemas reportados antes de prosseguir com a instalação do {{ config('app.name') }}.

<div id="set-up-an-editor"></div>

## Configurar um Editor

Escolha um IDE com suporte ao Flutter:

### Visual Studio Code (Recomendado)

O <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> é leve e possui excelente suporte ao Flutter.

1. Instale o <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>
2. Instale a <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">extensão Flutter</a>
3. Instale a <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">extensão Dart</a>

Guia de configuração: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">Configuração do Flutter no VS Code</a>

### Android Studio

O <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a> fornece um IDE completo com suporte integrado a emuladores.

1. Instale o <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>
2. Instale o plugin Flutter (Preferences → Plugins → Flutter)
3. Instale o plugin Dart

Guia de configuração: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Configuração do Flutter no Android Studio</a>

### IntelliJ IDEA

O <a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community ou Ultimate) também suporta desenvolvimento Flutter.

1. Instale o IntelliJ IDEA
2. Instale o plugin Flutter (Preferences → Plugins → Flutter)
3. Instale o plugin Dart

<div id="nylo-editor-plugins"></div>

### Plugins do Nylo para Editores

{{ config('app.name') }} tem plugins próprios para o VS Code e para os IDEs da JetBrains:

| Editor | Plugin |
|--------|--------|
| VS Code | <a href="https://marketplace.visualstudio.com/items?itemName=Nylo.vscode-nylo" target="_BLANK">Nylo for VS Code</a> |
| Android Studio, IntelliJ IDEA | <a href="https://plugins.jetbrains.com/plugin/33988-nylo" target="_BLANK">Nylo</a> |

O plugin adiciona uma configuração de execução para cada arquivo `.env` do seu projeto, com o nome derivado do arquivo: `.env` se torna **Default** e `.env.staging` se torna **Staging**. Ao iniciar uma delas, `metro make:env` é executado com esse arquivo antes de o app iniciar, para que você não precise regenerar manualmente a configuração do env ao alternar entre ambientes. A lista é atualizada conforme você adiciona ou remove arquivos `.env`.

No VS Code, escolha um ambiente no dropdown **Run and Debug** ou execute **Nylo: Run Environment…** na paleta de comandos. No Android Studio e no IntelliJ IDEA, escolha-o no dropdown de configurações de execução.

Assim que seu editor estiver configurado, você estará pronto para [instalar o {{ config('app.name') }}](/docs/7.x/installation).
