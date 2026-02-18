# Alerts

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Yerleşik Stiller](#built-in-styles "Yerleşik Stiller")
- [Sayfalardan Uyarı Gösterme](#from-pages "Sayfalardan Uyarı Gösterme")
- [Controller'lardan Uyarı Gösterme](#from-controllers "Controller'lardan Uyarı Gösterme")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Konumlandırma](#positioning "Konumlandırma")
- [Özel Toast Stilleri](#custom-styles "Özel Toast Stilleri")
  - [Stilleri Kaydetme](#registering-styles "Stilleri Kaydetme")
  - [Stil Factory Oluşturma](#creating-a-style-factory "Stil Factory Oluşturma")
- [AlertTab](#alert-tab "AlertTab")
- [Örnekler](#examples "Örnekler")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }}, kullanıcılara uyarılar göstermek için bir toast bildirim sistemi sağlar. Dört yerleşik stil ile birlikte gelir -- **success**, **warning**, **info** ve **danger** -- ve bir kayıt deseni aracılığıyla özel stilleri destekler.

Uyarılar sayfalardan, controller'lardan veya `BuildContext`'e sahip olduğunuz herhangi bir yerden tetiklenebilir.

<div id="basic-usage"></div>

## Temel Kullanım

Herhangi bir `NyState` sayfasında kolaylık metotlarını kullanarak toast bildirimi gösterin:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

Veya bir stil ID'si ile global fonksiyonu kullanın:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Yerleşik Stiller

{{ config('app.name') }} dört varsayılan toast stili kaydeder:

| Stil ID | Simge | Renk | Varsayılan Başlık |
|----------|------|-------|---------------|
| `success` | Onay işareti | Yeşil | Success |
| `warning` | Ünlem | Turuncu | Warning |
| `info` | Bilgi simgesi | Teal | Info |
| `danger` | Uyarı simgesi | Kırmızı | Error |

Bunlar `lib/config/toast_notification.dart` dosyasında yapılandırılır:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Sayfalardan Uyarı Gösterme

`NyState` veya `NyBaseState`'i genişleten herhangi bir sayfada bu kolaylık metotlarını kullanın:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Genel Toast Metodu

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Controller'lardan Uyarı Gösterme

`NyController`'ı genişleten controller'lar aynı kolaylık metotlarına sahiptir:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Kullanılabilir metotlar: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

Global `showToastNotification()` fonksiyonu, `BuildContext`'e sahip olduğunuz herhangi bir yerden toast gösterir:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Parametreler

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | zorunlu | Build context |
| `id` | `String` | `'success'` | Toast stil ID'si |
| `title` | `String?` | null | Varsayılan başlığı geçersiz kıl |
| `description` | `String?` | null | Açıklama metni |
| `duration` | `Duration?` | null | Toast'ın görüntülenme süresi |
| `position` | `ToastNotificationPosition?` | null | Ekrandaki konum |
| `action` | `VoidCallback?` | null | Dokunma geri çağırması |
| `onDismiss` | `VoidCallback?` | null | Kapatma geri çağırması |
| `onShow` | `VoidCallback?` | null | Gösterim geri çağırması |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta`, bir toast bildirimi için tüm verileri kapsüller:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Özellikler

| Özellik | Tür | Varsayılan | Açıklama |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Simge widget'ı |
| `title` | `String` | `''` | Başlık metni |
| `style` | `String` | `''` | Stil tanımlayıcısı |
| `description` | `String` | `''` | Açıklama metni |
| `color` | `Color?` | null | Simge bölümü arka plan rengi |
| `action` | `VoidCallback?` | null | Dokunma geri çağırması |
| `dismiss` | `VoidCallback?` | null | Kapatma düğmesi geri çağırması |
| `onDismiss` | `VoidCallback?` | null | Otomatik/manuel kapatma geri çağırması |
| `onShow` | `VoidCallback?` | null | Görünürlük geri çağırması |
| `duration` | `Duration` | 5 saniye | Görüntülenme süresi |
| `position` | `ToastNotificationPosition` | `top` | Ekran konumu |
| `metaData` | `Map<String, dynamic>?` | null | Özel meta veri |

### copyWith

`ToastMeta`'nın değiştirilmiş bir kopyasını oluşturun:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Konumlandırma

Toast'ların ekranda nerede görüneceğini kontrol edin:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Özel Toast Stilleri

<div id="registering-styles"></div>

### Stilleri Kaydetme

`AppProvider`'ınızda özel stiller kaydedin:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Veya herhangi bir zamanda stil ekleyin:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Sonra kullanın:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Stil Factory Oluşturma

`ToastNotification.style()` bir `ToastStyleFactory` oluşturur:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `icon` | `Widget` | Toast için simge widget'ı |
| `color` | `Color` | Simge bölümü arka plan rengi |
| `defaultTitle` | `String` | Başlık sağlanmadığında gösterilen başlık |
| `position` | `ToastNotificationPosition?` | Varsayılan konum |
| `duration` | `Duration?` | Varsayılan süre |
| `builder` | `Widget Function(ToastMeta)?` | Tam kontrol için özel widget builder |

### Tamamen Özel Builder

Toast widget'ı üzerinde tam kontrol için:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab`, navigasyon sekmelerine bildirim göstergeleri eklemek için bir rozet widget'ıdır. Açılıp kapatılabilen ve isteğe bağlı olarak depolamaya kalıcı olarak kaydedilebilen bir rozet gösterir.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parametreler

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `state` | `String` | zorunlu | Takip için durum adı |
| `alertEnabled` | `bool?` | null | Rozetin gösterilip gösterilmeyeceği |
| `rememberAlert` | `bool?` | `true` | Rozet durumunu depoya kalıcı kaydet |
| `icon` | `Widget?` | null | Sekme simgesi |
| `backgroundColor` | `Color?` | null | Sekme arka planı |
| `textColor` | `Color?` | null | Rozet metin rengi |
| `alertColor` | `Color?` | null | Rozet arka plan rengi |
| `smallSize` | `double?` | null | Küçük rozet boyutu |
| `largeSize` | `double?` | null | Büyük rozet boyutu |
| `textStyle` | `TextStyle?` | null | Rozet metin stili |
| `padding` | `EdgeInsetsGeometry?` | null | Rozet dolgusu |
| `alignment` | `Alignment?` | null | Rozet hizalama |
| `offset` | `Offset?` | null | Rozet konumu |
| `isLabelVisible` | `bool?` | `true` | Rozet etiketini göster |

### Factory Constructor

Bir `NavigationTab`'dan oluşturun:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Örnekler

### Kaydetme Sonrası Başarı Uyarısı

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Eylemli Etkileşimli Toast

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Alt Konumlu Uyarı

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
