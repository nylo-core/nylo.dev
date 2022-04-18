# API Networking

---

- <span class="text-grey">Sponsors</span>
- [Become a sponsor](https://nylo.dev/contributions)

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Api model](#api-model "API model")
- [Auto-generating your models](#auto-generating-your-models "Auto-generating your models")
- [Fetching data into your widgets](#fetching-data-into-your-widgets "Fetching data into your widgets")

<a name="introduction"></a>
<br>
## Introduction

API networking is used to fetch data from the internet. A common data format is `.json`, it provides a representation of an object from a backend system. You’ll find many apps rely on having a backend API to return and store data.

Let's take a look at a simple example.
If you were to head over to the below link, this will return a response for users.
[https://jsonplaceholder.typicode.com/users](https://jsonplaceholder.typicode.com/users)

If we wanted to display those users in our app, we can use some of the widgets and tools provided in Nylo that this document will explain.

It’s important you have a basic understanding of different API requests, some of the common method types are as follows:
- Get
- Post
- Put
- Delete

If you are new to networking, check out this [guide](https://restfulapi.net) by **https://restfulapi.net** to learn more.

<a name="api-models"></a>
<br>
## Api models

Models help us interact with the data from an API request in our widgets. You can think of a Model like a blueprint of how the data is structured.

If we take a look at the below example you can see on the JSON object all the properties we can set on a model e.g. name, username, phone and more

``` JSON
{
    "id": 2,
    "name": "Ervin Howell",
    "username": "Antonette",
    "email": "Shanna@melissa.tv",
    "address": {
      "street": "Victor Plains",
      "suite": "Suite 879",
      "city": "Wisokyburgh",
      "zipcode": "90566-7771",
      "geo": {
        "lat": "-43.9509",
        "lng": "-34.4618"
      }
    },
    "phone": "010-692-6593 x09125",
    "website": "anastasia.net",
    "company": {
      "name": "Deckow-Crist",
      "catchPhrase": "Proactive didactic contingency",
      "bs": "synergize scalable supply-chains"
    }
}
```

You should define your models as classes within the `app/models` directory. 

Our data representation of this in `.dart` would look something like the below.

``` dart
// * app/models/user.dart file *

class User {

int id;
String name;
String username;
String email;
Address address;
String phone;
String website;
Company company;

User({this.id, this.name, this.username, this.email, this.address, this.phone, this.website, this.company});
}
```

<a name="auto-generating-your-models"></a>
<br>

## Auto generating your models

In Nylo, you can use the Metro CLI tool to automatically create your Models for you when running the below command.

metro apispec:build

You need to have defined all your APIs from within your `apispec.json` file first, we have a detailed guide [here](/docs/1.x/metro#what-is-an-apispec).

<a name="fetching-data-into-your-widgets"></a>
<br>

## Fetching data into your widgets

Once you have defined all your API requests in your `api_service.dart` file, you can start using the service to return data.

API requests will usually return a `Future` which means it will need time to process the request before it returns a value. You can read up more on Future’s [here](https://dart.dev/codelabs/async-await).

In Nylo, we can start using our APIs with the `ApiRender` widget. This widget will look like the below.

``` dart
ApiRender<User>(
  api: widget.controller.apiService.fetchUser(),
  widget: (user) {
    return Text(user.username);
  }
);
```

That’s all that’s needed to return data into our widget. This will display a loading widget until the final data has been retrieved.

You can also view the source code for the ApiRender widget to see all the parameters you can set.

``` dart
class ApiRender<T> extends FutureBuilder {
  ApiRender(
      {Key key,
      Future api,
      Widget Function(T model) widget,
      Widget whenLoading = const CircularProgressIndicator(),
      Widget initialWidget})
      : super(
          key: key,
          future: api,
          initialData: initialWidget,
          builder: (BuildContext context, AsyncSnapshot snapshot) {
            switch (snapshot.connectionState) {
              case ConnectionState.waiting:
                if (initialWidget == null) {
                  return whenLoading;
                }
                return initialWidget;
              default:
                if (snapshot.hasError) {
                  NyLogger.debug(snapshot.error);
                  return widget(null);
                } else
                  return widget(snapshot.data);
            }
          },
        );
} 
```
