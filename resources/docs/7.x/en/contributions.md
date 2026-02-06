# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to contributing")
- [Getting Started](#getting-started "Getting started with contributions")
- [Development Environment](#development-environment "Setting up development environment")
- [Development Guidelines](#development-guidelines "Development guidelines")
- [Submitting Changes](#submitting-changes "How to submit changes")
- [Reporting Issues](#reporting-issues "How to report issues")


<div id="introduction"></div>

## Introduction

Thank you for considering contributing to {{ config('app.name') }}!

This guide will help you understand how to contribute to the micro-framework. Whether you're fixing bugs, adding features, or improving documentation, your contributions are valuable to the {{ config('app.name') }} community.

{{ config('app.name') }} is split into three repositories:

| Repository | Purpose |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | The boilerplate application |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Core framework classes (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Support library with widgets, helpers, utilities (nylo_support) |

<div id="getting-started"></div>

## Getting Started

### Fork the Repositories

Fork the repositories you want to contribute to:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clone Your Forks

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Development Environment

### Requirements

Ensure you have the following installed:

| Requirement | Minimum Version |
|-------------|-----------------|
| Flutter | 3.24.0 or higher |
| Dart SDK | 3.10.7 or higher |

### Link Local Packages

Open the Nylo boilerplate in your editor and add dependency overrides to use your local framework and support repositories:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Run `flutter pub get` to install dependencies.

Now changes you make to the framework or support repositories will be reflected in the Nylo boilerplate.

### Testing Your Changes

Run the boilerplate app to test your changes:

``` bash
flutter run
```

For widget or helper changes, consider adding tests in the appropriate repository.

<div id="development-guidelines"></div>

## Development Guidelines

### Code Style

- Follow the official <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart style guide</a>
- Use meaningful variable and function names
- Write clear comments for complex logic
- Include documentation for public APIs
- Keep code modular and maintainable

### Documentation

When adding new features:

- Add dartdoc comments to public classes and methods
- Update the relevant documentation files if needed
- Include code examples in documentation

### Testing

Before submitting changes:

- Test on both iOS and Android devices/simulators
- Verify backwards compatibility where possible
- Document any breaking changes clearly
- Run existing tests to ensure nothing is broken

<div id="submitting-changes"></div>

## Submitting Changes

### Discuss First

For new features, it's best to discuss with the community first:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Create a Branch

``` bash
git checkout -b feature/your-feature-name
```

Use descriptive branch names:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Commit Your Changes

``` bash
git add .
git commit -m "Add: Your feature description"
```

Use clear commit messages:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push and Create Pull Request

``` bash
git push origin feature/your-feature-name
```

Then create a pull request on GitHub.

### Pull Request Guidelines

- Provide a clear description of your changes
- Reference any related issues
- Include screenshots or code examples if applicable
- Ensure your PR addresses only one concern
- Keep changes focused and atomic

<div id="reporting-issues"></div>

## Reporting Issues

### Before Reporting

1. Check if the issue already exists on GitHub
2. Ensure you're using the latest version
3. Try to reproduce the issue in a fresh project

### Where to Report

Report issues on the appropriate repository:

- **Boilerplate issues**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Framework issues**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Support library issues**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Issue Template

Provide detailed information:

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

### Getting Version Information

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
