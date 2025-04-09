# Contributing to Nylo

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to contributing")
- [Getting Started](#getting-started "Getting started with contributions")
- [Development Guidelines](#development-guidelines "Development guidelines")
- [Submitting Changes](#submitting-changes "How to submit changes")
- [Reporting Issues](#reporting-issues "How to report issues")

<div id="introduction"></div>
<br>

## Introduction

Thank you for considering contributing to Nylo! 

This guide will help you understand how you can contribute to the micro-framework, no matter your experience level. 

Whether you're a beginner or an experienced developer, your contributions are valuable to the Nylo community.

<div id="getting-started"></div>
<br>

## Getting Started

Before you begin contributing to Nylo, please ensure you have the following:

1. Fork the Nylo repositories
	- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo</a>
	- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework</a>
	- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support</a>
2. Clone your forks locally
	- `git clone https://github.com/nylo-core/nylo`
	- `git clone https://github.com/nylo-core/framework`
	- `git clone https://github.com/nylo-core/support`
3. Open the repositories in your preferred code editor

## Set up your development environment

Open the <a href="https://github.com/nylo-core/nylo" target="_BLANK">Nylo</a> repository in your code editor and add the following:

**pubspec.yaml**
``` yaml
...
dependency_overrides:
  nylo_framework:
    path: ../framework # Path to your local framework repository
  nylo_support:
    path: ../support # Path to your local support repository
```

Next, run `flutter pub get` to install the dependencies.

Now, you should be able to run the Nylo project with your local framework and support repositories.

Any changes you make to the framework or support repositories will be reflected in the Nylo project.

<div id="submitting-changes"></div>
<br>

### Submitting Changes 

If you have an idea, it's best to discuss it with the community first. 

You can do this by creating a new discussion on GitHub <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">here</a>.

Once you have a clear idea of the feature you want to add, you can start working on it.

``` bash
# Create a new branch
git checkout -b feature/your-feature-name
```

Once you've completed the feature, commit your changes and push them to your fork.

``` bash
# Commit your changes
git commit -m "Add: Your feature description"

# Push your changes
git push origin feature/your-feature-name
```

Finally, submit a pull request into the repository you forked from.


<div id="development-guidelines"></div>
<br>

## Development Guidelines

When contributing to Nylo, please follow these guidelines:

### Code Style

- Follow the official <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart style guide</a>
- Use meaningful variable and function names
- Write clear comments for complex logic
- Include documentation for public APIs
- Keep code modular and maintainable

### Testing

Before submitting your changes:

- Verify backwards compatibility
- Check for any breaking changes
- Test your changes on IOS and Android devices

### Pull Request Guidelines

- Provide a clear description of your changes
- Reference any related issues
- Include screenshots or code examples if applicable
- Ensure your PR addresses only one concern
- Keep changes focused and atomic

<div id="reporting-issues"></div>
<br>

## Reporting Issues

When reporting issues:

1. Use the GitHub issue tracker
2. Check if the issue already exists
3. Include detailed reproduction steps
4. Provide system information:
   - Flutter version `flutter --version`
   - Nylo framework version from pubspec.yaml, e.g. `nylo_framework: ^6.5.0`
   - Device information (if relevant)

Example of a good issue report:
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
- Flutter: 3.x.x
- Nylo: x.x.x
- OS: macOS/Windows/Linux
- Device: iPhone 13/Pixel 6 (if applicable)
```