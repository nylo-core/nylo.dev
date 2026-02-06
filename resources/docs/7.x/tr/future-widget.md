# FutureWidget

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Yükleme Durumunu Özelleştirme](#customizing-loading "Yükleme Durumunu Özelleştirme")
    - [Normal Yükleme Stili](#normal-loading "Normal Yükleme Stili")
    - [Skeletonizer Yükleme Stili](#skeletonizer-loading "Skeletonizer Yükleme Stili")
    - [Yüklemesiz Stil](#no-loading "Yüklemesiz Stil")
- [Hata Yönetimi](#error-handling "Hata Yönetimi")


<div id="introduction"></div>

## Giriş

**FutureWidget**, {{ config('app.name') }} projelerinizde `Future`'ları render etmenin basit bir yoludur. Flutter'ın `FutureBuilder`'ını sarar ve yerleşik yükleme durumlarıyla daha temiz bir API sunar.

Future'ınız devam ederken bir yükleyici görüntüler. Future tamamlandığında, veri `child` callback'i aracılığıyla döndürülür.

<div id="basic-usage"></div>

## Temel Kullanım

`FutureWidget` kullanımına basit bir örnek:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

Widget, Future tamamlanana kadar kullanıcılarınız için yükleme durumunu otomatik olarak yönetecektir.

<div id="customizing-loading"></div>

## Yükleme Durumunu Özelleştirme

`loadingStyle` parametresini kullanarak yükleme durumunun nasıl görüneceğini özelleştirebilirsiniz.

<div id="normal-loading"></div>

### Normal Yükleme Stili

Standart bir yükleme widget'ı görüntülemek için `LoadingStyle.normal()` kullanın. İsteğe bağlı olarak özel bir alt widget sağlayabilirsiniz:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

Alt widget sağlanmazsa, varsayılan {{ config('app.name') }} uygulama yükleyicisi görüntülenecektir.

<div id="skeletonizer-loading"></div>

### Skeletonizer Yükleme Stili

İskelet yükleme efekti görüntülemek için `LoadingStyle.skeletonizer()` kullanın. Bu, içerik düzeninize uygun yer tutucu arayüz göstermek için idealdir:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Mevcut iskelet efektleri:
- `SkeletonizerEffect.shimmer` - Animasyonlu parıltı efekti (varsayılan)
- `SkeletonizerEffect.pulse` - Nabız animasyonu efekti
- `SkeletonizerEffect.solid` - Düz renk efekti

<div id="no-loading"></div>

### Yüklemesiz Stil

Yükleme göstergesini tamamen gizlemek için `LoadingStyle.none()` kullanın:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Hata Yönetimi

Future'ınızdan gelen hataları `onError` callback'i kullanarak yönetebilirsiniz:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

`onError` callback'i sağlanmazsa ve bir hata oluşursa, boş bir `SizedBox.shrink()` görüntülenecektir.

### Parametreler

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `future` | `Future<T>?` | Beklenecek Future |
| `child` | `Widget Function(BuildContext, T?)` | Future tamamlandığında çağrılan oluşturucu fonksiyon |
| `loadingStyle` | `LoadingStyle?` | Yükleme göstergesini özelleştirme |
| `onError` | `Widget Function(AsyncSnapshot)?` | Future hata verdiğinde çağrılan oluşturucu fonksiyon |
