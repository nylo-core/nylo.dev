# Authentication

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to authentication in {{ config('app.name') }}")
- Basics
  - [Adding an auth user](#adding-an-auth-user "Adding an auth user")
  - [Retrieve an auth user](#retrieve-an-auth-user "Retrieve an auth user")
  - [Logout an auth user](#logout-an-auth-user "Logout an auth user")
- [Checking if a user is authenticated](#checking-if-a-user-is-authenticated "Checking if a user is authenticated")
- [Authentication page](#authentication-page "Authentication page")

<div id="introduction"></div>

## Introduction

In {{ config('app.name') }}, you can use the built-in helpers to make Authentication a breeze.

To authenticate a user, run the below command.

```dart
await Auth.authenticate();
```

If you'd like to add data, use the 'data' parameter.

```dart
await Auth.authenticate(data: {"token_id": "ey2sdm..."});
```

To retrieve the authenticated user's data, run the below.

```dart
Map user = await Auth.data();

print(user); // {token_id: ey2sdm...}
```

Let's imagine the below scenario.

1. A user registers using an email and password.
2. After registering, you create the user a session token.
3. We now want to store the session token on the user's device for future use.

``` dart
_login(String email, String password) async {
  // 1 - Example register via an API Service
  User? user = await api<AuthApiService>((request) => request.registerUser(
    email: email, 
    password: password
 ));

  // 2 - Returns the users session token
  print(user?.token); // ey2sdm...

  // 3 - Save the user to Nylo
  await Auth.authenticate(data: {"token_id": user?.token});
}
```

Now the User will be authenticated and the data will be stored on their device.

<div id="adding-an-auth-user"></div>

## Adding an auth user

When a user logs in to your application, you can add them using the `Auth.authenticate()` helper.

``` dart
_login() async {
  ...

  await Auth.authenticate(data: {"token_id": "ey2sdm..."});
} 
```

<div id="retrieve-an-auth-user"></div>

## Retrieve an auth user's data

If a user is logged into your app, you can retrieve the user's data by calling `Auth.data()`.

``` dart
_getUser() async {
  dynamic userData = await Auth.data();

  print(userData); // {token_id: ey2sdm...}
}
```

<div id="logout-an-auth-user"></div>

## Logout an auth user

When a user logs out of your application, you can remove them using the `Auth.logout()` helper.

``` dart
_logout() async {

  await Auth.logout();
}
```

Now, the user is logged out of the app and the [authentication page](#authentication-page) won't show when they next visit the app.

<div id="checking-if-a-user-is-authenticated"></div>

## Checking if a user is authenticated

You can check if a user is authenticated by calling the `Auth.isAuthenticated()` helper.

``` dart
_isAuthenticated() async {
  bool isAuthenticated = await Auth.isAuthenticated();

  print(isAuthenticated); // true
}
```

<div id="authentication-page"></div>

## Authentication Page

Once your user is stored using the `Auth.authenticate(user)` helper. You'll be able to set an 'authentication page', this will be used as the initial page the user sees when they open the app.

Go to your **routes/router.dart** file and use the `authenticatedRoute` function.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute(); // initial route

  router.add(ProfilePage.path).authenticatedRoute(); // authenticated route
});
```

Now, when the app boots, it will use the authenticated page instead of the default route.
