# Authentication

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to authentication in {{ config('app.name') }}")
- Authentication
  - [Adding an auth user](#adding-an-auth-user "Adding an auth user")
  - [Retrieve an auth user](#retrieve-an-auth-user "Retrieve an auth user")
  - [Removing an auth user](#removing-an-auth-user "Removing an auth user")
- [Authentication page](#authentication-page "Authentication page")

<a name="introduction"></a>
<br>

## Introduction

In {{ config('app.name') }}, you can use the built-in helpers to make Authentication a breeze.

To authenticate a user, run the below command.

```dart
User user = User();

await Auth.set(user);
```

To retrieve a user, run the below command.

```dart
User? user = await Auth.user<User>();

print(user); // User
```

Let's imagine the below scenario.

1. A user registers using an email and password.
2. After registering, you create the user a session token.
3. We now want to store the session token on the user's device for future use.

``` dart
TextEditingController _tfEmail = TextEditingController();
TextEditingController _tfPassword = TextEditingController();

_login() async {
  // 1 - Example register via an API Service
  User? user = await api<AuthApiService>((request) => request.register(email: _tfEmail.text, password: _tfPassword.text));

  // 2 - Returns the users session token
  print(user?.token);

  // 3 - Save the user to Nylo
  await Auth.set(user);
}
```

Now the User model will be saved on the device.

To retrieve the authenticated user back, use `Backpack.instance.auth()`. This will return the model that was saved previously.

<a name="adding-an-auth-user"></a>
<br>

## Adding an auth user

When a user logs in to your application, you can add them using the `Auth.set(user)` helper.

``` dart
_login() async {
  User user = User();

  await Auth.set(user);
} 
```

<a name="retrieve-an-auth-user"></a>
<br>

## Retrieve an auth user

If a user is logged into your app, you can retrieve the user by calling `getAuthUser()`. This helper method will return the model stored.

``` dart
_getUser() async {
  User? user = await Auth.user<User>();
  // or
  User? user = Backpack.instance.auth();
}
```

<a name="removing-an-auth-user"></a>
<br>

## Removing an auth user

When a user logs out of your application, you can remove them using the `Auth.remove()` helper.

``` dart
_logout() async {

  await Auth.remove();
}
```

Now, the user is logged out of the app and the [authentication page](#authentication-page) won't show when they next visit the app.

<a name="authentication-page"></a>
<br>

## Authentication Page

Once your user is stored using the `Auth.set(user)` helper. You'll be able to set an 'authentication page', this will be used as the initial page the user sees when they open the app.

Go to your **routes/router.dart** file and set parameter `authPage`.

``` dart
appRouter() => nyRoutes((router) {

  router.route(HomePage.path, (context) => HomePage(title: "Hello World"));

  router.route(ProfilePage.path, (context) => ProfilePage(), authPage: true); // auth page
});
```

Now, when the app boots, it will use the auth page instead of the default route link.
