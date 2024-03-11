<head>
  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.5">
  <meta name="author" content="{{ config('app.name') }}" />
  {!! SEO::generate(true) !!}
  <meta name="twitter:image:src" content="{{ asset('images/nylo_logo.png') }}">
  <meta name="twitter:image" content="{{ asset('images/nylo_logo.png') }}">
  <link href="{{ asset('css/remixicon' . (config('app.env') == 'production' ? '.min' : '') . '.css') }}" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('site.webmanifest') }}">
  <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <link
      href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css"
      rel="stylesheet"
    />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            clifford: '#da373d',
          }
        }
      }
    }
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sora:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">

  @env('production')
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('project.meta.ga_id') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ config('project.meta.ga_id') }}');
  </script>
  @endenv
  <style type="text/css">
    * {
      font-family: "Work Sans";
    }
    .sora {
      font-family: "Sora";
    }
    p {
      letter-spacing: -0.02em;
      color: #6C7379;
    }
    .text-gray-prime {
      color: #6C7379;
    }
    .text-primary-blue {
      color: #328DDF;
    }
    .text-primary-blue-deep {
      color: #1A63A4;
    }
    .text-primary-gray {
      color: #6C7379;
    }
    .text-primary-gray-400 {
      color: #979DA2;
    }
    .bg-primary-blue-deep {
      background-color: #1A63A4;
    }
    .text-4xl {
      letter-spacing: -0.01em;
    }
  </style>
</head>
