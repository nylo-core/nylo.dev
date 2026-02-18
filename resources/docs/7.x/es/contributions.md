# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion a las contribuciones")
- [Primeros pasos](#getting-started "Primeros pasos con las contribuciones")
- [Entorno de desarrollo](#development-environment "Configurar el entorno de desarrollo")
- [Directrices de desarrollo](#development-guidelines "Directrices de desarrollo")
- [Enviar cambios](#submitting-changes "Como enviar cambios")
- [Reportar problemas](#reporting-issues "Como reportar problemas")


<div id="introduction"></div>

## Introduccion

Gracias por considerar contribuir a {{ config('app.name') }}!

Esta guia te ayudara a entender como contribuir al micro-framework. Ya sea que estes corrigiendo errores, agregando funcionalidades o mejorando la documentacion, tus contribuciones son valiosas para la comunidad de {{ config('app.name') }}.

{{ config('app.name') }} esta dividido en tres repositorios:

| Repositorio | Proposito |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | La aplicacion boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Clases principales del framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Biblioteca de soporte con widgets, helpers, utilidades (nylo_support) |

<div id="getting-started"></div>

## Primeros pasos

### Hacer fork de los repositorios

Haz fork de los repositorios a los que quieras contribuir:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clonar tus forks

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Entorno de desarrollo

### Requisitos

Asegurate de tener lo siguiente instalado:

| Requisito | Version minima |
|-------------|-----------------|
| Flutter | 3.24.0 o superior |
| Dart SDK | 3.10.7 o superior |

### Vincular paquetes locales

Abre el boilerplate de Nylo en tu editor y agrega sobrecargas de dependencias para usar tus repositorios locales de framework y support:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Ejecuta `flutter pub get` para instalar las dependencias.

Ahora los cambios que hagas en los repositorios de framework o support se reflejaran en el boilerplate de Nylo.

### Probar tus cambios

Ejecuta la aplicacion boilerplate para probar tus cambios:

``` bash
flutter run
```

Para cambios en widgets o helpers, considera agregar pruebas en el repositorio correspondiente.

<div id="development-guidelines"></div>

## Directrices de desarrollo

### Estilo de codigo

- Sigue la guia oficial de <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">estilo de Dart</a>
- Usa nombres significativos para variables y funciones
- Escribe comentarios claros para logica compleja
- Incluye documentacion para APIs publicas
- Mantiene el codigo modular y mantenible

### Documentacion

Al agregar nuevas funcionalidades:

- Agrega comentarios dartdoc a clases y metodos publicos
- Actualiza los archivos de documentacion relevantes si es necesario
- Incluye ejemplos de codigo en la documentacion

### Pruebas

Antes de enviar cambios:

- Prueba en dispositivos/simuladores tanto de iOS como de Android
- Verifica la compatibilidad hacia atras cuando sea posible
- Documenta claramente cualquier cambio que rompa compatibilidad
- Ejecuta las pruebas existentes para asegurar que nada se rompa

<div id="submitting-changes"></div>

## Enviar cambios

### Discutir primero

Para nuevas funcionalidades, es mejor discutir con la comunidad primero:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Crear una rama

``` bash
git checkout -b feature/your-feature-name
```

Usa nombres de rama descriptivos:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Hacer commit de tus cambios

``` bash
git add .
git commit -m "Add: Your feature description"
```

Usa mensajes de commit claros:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push y crear Pull Request

``` bash
git push origin feature/your-feature-name
```

Luego crea un pull request en GitHub.

### Directrices para Pull Request

- Proporciona una descripcion clara de tus cambios
- Referencia cualquier issue relacionado
- Incluye capturas de pantalla o ejemplos de codigo si aplica
- Asegurate de que tu PR aborde solo una preocupacion
- Manten los cambios enfocados y atomicos

<div id="reporting-issues"></div>

## Reportar problemas

### Antes de reportar

1. Verifica si el problema ya existe en GitHub
2. Asegurate de estar usando la ultima version
3. Intenta reproducir el problema en un proyecto nuevo

### Donde reportar

Reporta problemas en el repositorio correspondiente:

- **Problemas del boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Problemas del framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Problemas de la biblioteca de soporte**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Plantilla de issue

Proporciona informacion detallada:

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

### Obtener informacion de version

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
