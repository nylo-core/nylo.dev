# Installation

---

<a name="section-1"></a>
- [Install](#install "Install")
  - [Adding your .env file](#adding-your-env-file "Adding your env file")
  - [Running the project](#running-the-project "Running the project")
- Setup
  - [Metro Cli tool](#setup-metro-alias "Setup Metro Cli tool alias")

<a name="install"></a>
<br>
## Install

> {info} You should have Flutter installed from the previous step

``` dart
git clone https://github.com/nylo-core/nylo.git nylo_app
```

You will need to run `flutter pub get` on the project to fetch all the packages.

<a name="adding-your-env-file"></a>
<br>

## Adding your .env file

You will also need to add a `.env` file to your project at the root level. If you've downloaded Nylo through our site or on GitHub then this might already be there.

> {danger} You must add this because the project won't build without it.

---

<a name="running-the-project"></a>
<br>

## Running the project

Once you have done the above steps, try to run the project. 

You should be greeted with Nylo's default landing screen for new projects.

---

<a name="setup-metro-alias"></a>
<br>

## Metro CLI tool

Nylo provides a useful CLI tool called Metro. It's been built to make creating things in your project easier, the below list shows what you can create:
- Models
- Controllers
- Pages
- Stateful widgets and stateless widgets
- Building your app icons

<br>

You can use Metro by running the following command:

  - `flutter pub run nylo_framework:main`

This command is long to type and hard to remember so you can also add a **bash alias** into your `bash_profile`.

<br>

#### Installation for Mac guide

---

1. **Open your bash\_profile**
``` dart
sudo open ~/.bash_profile
```

1. **Add this alias to your bash\_profile**
``` bash
...
alias metro='flutter pub run nylo_framework:main'
```

1. **Then run the following**
``` bash
source ~/.bash_profile
```

### Can't find your bash\_profile?

If you are unsure about where to add the above, check out some guides online for where to find your bash\_profile. 
The above example assumes it's in your `~/` location but it could be in a different location or missing from your setup.


