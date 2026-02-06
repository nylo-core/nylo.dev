# FutureWidget

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Menyesuaikan Status Loading](#customizing-loading "Menyesuaikan Status Loading")
    - [Gaya Loading Normal](#normal-loading "Gaya Loading Normal")
    - [Gaya Loading Skeletonizer](#skeletonizer-loading "Gaya Loading Skeletonizer")
    - [Tanpa Gaya Loading](#no-loading "Tanpa Gaya Loading")
- [Penanganan Error](#error-handling "Penanganan Error")


<div id="introduction"></div>

## Pengantar

**FutureWidget** adalah cara sederhana untuk merender `Future` di proyek {{ config('app.name') }} Anda. Widget ini membungkus `FutureBuilder` Flutter dan menyediakan API yang lebih bersih dengan status loading bawaan.

Saat Future Anda sedang berlangsung, widget akan menampilkan loader. Setelah Future selesai, data dikembalikan melalui callback `child`.

<div id="basic-usage"></div>

## Penggunaan Dasar

Berikut contoh sederhana penggunaan `FutureWidget`:

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

Widget akan secara otomatis menangani status loading untuk pengguna Anda hingga Future selesai.

<div id="customizing-loading"></div>

## Menyesuaikan Status Loading

Anda dapat menyesuaikan tampilan status loading menggunakan parameter `loadingStyle`.

<div id="normal-loading"></div>

### Gaya Loading Normal

Gunakan `LoadingStyle.normal()` untuk menampilkan widget loading standar. Anda dapat secara opsional menyediakan widget child kustom:

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

Jika tidak ada child yang disediakan, loader default aplikasi {{ config('app.name') }} akan ditampilkan.

<div id="skeletonizer-loading"></div>

### Gaya Loading Skeletonizer

Gunakan `LoadingStyle.skeletonizer()` untuk menampilkan efek loading skeleton. Ini sangat cocok untuk menampilkan placeholder UI yang sesuai dengan tata letak konten Anda:

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

Efek skeleton yang tersedia:
- `SkeletonizerEffect.shimmer` - Efek shimmer animasi (default)
- `SkeletonizerEffect.pulse` - Efek animasi berkedip
- `SkeletonizerEffect.solid` - Efek warna solid

<div id="no-loading"></div>

### Tanpa Gaya Loading

Gunakan `LoadingStyle.none()` untuk menyembunyikan indikator loading sepenuhnya:

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

## Penanganan Error

Anda dapat menangani error dari Future Anda menggunakan callback `onError`:

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

Jika tidak ada callback `onError` yang disediakan dan terjadi error, `SizedBox.shrink()` kosong akan ditampilkan.

### Parameter

| Parameter | Tipe | Deskripsi |
|-----------|------|-----------|
| `future` | `Future<T>?` | Future yang akan ditunggu |
| `child` | `Widget Function(BuildContext, T?)` | Fungsi builder yang dipanggil saat Future selesai |
| `loadingStyle` | `LoadingStyle?` | Menyesuaikan indikator loading |
| `onError` | `Widget Function(AsyncSnapshot)?` | Fungsi builder yang dipanggil saat Future mengalami error |
