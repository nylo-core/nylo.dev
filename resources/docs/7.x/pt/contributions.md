# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Primeiros Passos](#getting-started "Primeiros Passos")
- [Ambiente de Desenvolvimento](#development-environment "Ambiente de Desenvolvimento")
- [Diretrizes de Desenvolvimento](#development-guidelines "Diretrizes de Desenvolvimento")
- [Enviando Alterações](#submitting-changes "Enviando Alterações")
- [Reportando Problemas](#reporting-issues "Reportando Problemas")


<div id="introduction"></div>

## Introdução

Obrigado por considerar contribuir com o {{ config('app.name') }}!

Este guia ajudará você a entender como contribuir com o micro-framework. Seja corrigindo bugs, adicionando funcionalidades ou melhorando a documentação, suas contribuições são valiosas para a comunidade {{ config('app.name') }}.

{{ config('app.name') }} é dividido em três repositórios:

| Repositório | Finalidade |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | A aplicação boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Classes principais do framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Biblioteca de suporte com widgets, helpers e utilitários (nylo_support) |

<div id="getting-started"></div>

## Primeiros Passos

### Faça Fork dos Repositórios

Faça fork dos repositórios para os quais deseja contribuir:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork do Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork do Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork do Support</a>

### Clone seus Forks

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Ambiente de Desenvolvimento

### Requisitos

Certifique-se de ter os seguintes itens instalados:

| Requisito | Versão Mínima |
|-------------|-----------------|
| Flutter | 3.24.0 ou superior |
| Dart SDK | 3.10.7 ou superior |

### Vincular Pacotes Locais

Abra o boilerplate do Nylo no seu editor e adicione sobrescritas de dependência para usar seus repositórios locais do framework e support:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Caminho para seu repositório local do framework
  nylo_support:
    path: ../support    # Caminho para seu repositório local do support
```

Execute `flutter pub get` para instalar as dependências.

Agora, as alterações que você fizer nos repositórios do framework ou support serão refletidas no boilerplate do Nylo.

### Testando suas Alterações

Execute o aplicativo boilerplate para testar suas alterações:

``` bash
flutter run
```

Para alterações em widgets ou helpers, considere adicionar testes no repositório apropriado.

<div id="development-guidelines"></div>

## Diretrizes de Desenvolvimento

### Estilo de Código

- Siga o <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">guia de estilo oficial do Dart</a>
- Use nomes significativos para variáveis e funções
- Escreva comentários claros para lógicas complexas
- Inclua documentação para APIs públicas
- Mantenha o código modular e de fácil manutenção

### Documentação

Ao adicionar novas funcionalidades:

- Adicione comentários dartdoc a classes e métodos públicos
- Atualize os arquivos de documentação relevantes, se necessário
- Inclua exemplos de código na documentação

### Testes

Antes de enviar alterações:

- Teste em dispositivos/simuladores iOS e Android
- Verifique a compatibilidade com versões anteriores quando possível
- Documente claramente quaisquer alterações que quebrem compatibilidade
- Execute os testes existentes para garantir que nada esteja quebrado

<div id="submitting-changes"></div>

## Enviando Alterações

### Discuta Primeiro

Para novas funcionalidades, é melhor discutir com a comunidade primeiro:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">Discussões no GitHub</a>

### Crie uma Branch

``` bash
git checkout -b feature/your-feature-name
```

Use nomes descritivos para branches:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Faça Commit das suas Alterações

``` bash
git add .
git commit -m "Add: Your feature description"
```

Use mensagens de commit claras:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Envie e Crie um Pull Request

``` bash
git push origin feature/your-feature-name
```

Em seguida, crie um pull request no GitHub.

### Diretrizes para Pull Request

- Forneça uma descrição clara das suas alterações
- Referencie quaisquer issues relacionadas
- Inclua capturas de tela ou exemplos de código, se aplicável
- Certifique-se de que seu PR aborda apenas uma questão
- Mantenha as alterações focadas e atômicas

<div id="reporting-issues"></div>

## Reportando Problemas

### Antes de Reportar

1. Verifique se o problema já existe no GitHub
2. Certifique-se de que está usando a versão mais recente
3. Tente reproduzir o problema em um projeto novo

### Onde Reportar

Reporte problemas no repositório apropriado:

- **Problemas do Boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Problemas do Framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Problemas da biblioteca Support**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Modelo de Issue

Forneça informações detalhadas:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Obtendo Informações de Versão

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
