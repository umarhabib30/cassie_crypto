<!DOCTYPE html>
<html>
  <head>
     <title>{{ \App\Models\Settings::get('[OpenGraph] Title', 'CASINO.DEMOCASINO.CLICK | the best option for base of your new casino')  }}</title>

    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no, height=device-height, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="CASINO.DEMOCASINO.CLICK, base for your new casino project, battle tested source code that withstood test of time">

    <meta property="og:image" content="{{ \App\Models\Settings::get('[OpenGraph] Image URL', '/img/misc/logo.png') }}"/> 
    <meta property="og:image:secure_url" content="{{ asset(\App\Models\Settings::get('[OpenGraph] Image URL', '/img/misc/logo.png')) }}"/>
    <meta property="og:image:type" content="image/png"/>
    <meta property="og:image:width" content="295"/>
    <meta property="og:image:height" content="295"/>
    {{-- <meta property="og:site_name" content="{{ config('app.name') }}"/> --}}
    <meta property="og:title" content="CASINO.DEMOCASINO.CLICK | the best option for base of your new casino"/>
    <meta property="og:description" content="CASINO.DEMOCASINO.CLICK, base for your new casino project, battle tested source code that withstood test of time"/>
    <meta property="og:title" content="CASINO.DEMOCASINO.CLICK | the best option for base of your new casino"/>


    <meta name="keywords" content="casino source code, democasino, casino source, crypto casino source code"/>
    <meta name="author" content="CASINO.DEMOCASINO.CLICK">
    <link rel="canonical" href="https://casino.democasino.click"/>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MTEGL4LGPT"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-MTEGL4LGPT');
    </script>

    @if(env('APP_DEBUG'))
      <meta http-equiv="Expires" content="Mon, 26 Jul 1997 05:00:00 GMT">
      <meta http-equiv="Pragma" content="no-cache">
    @endif

    <link rel="icon" href="{{ asset('/favicon.png') }}">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "CASINO.DEMOCASINO.CLICK",
      "url": "https://casino.democasino.click",
      "logo": "https://casino.democasino.click/img/misc/logo.png",
      "sameAs": [
        "https://www.t.me/Athenian"
      ],
      "description": "CASINO.DEMOCASINO.CLICK, base for your new casino project, battle tested source code that withstood test of time"
    }
    </script>

    @php \App\Utils\ViteHotReloadFile::set('@web'); @endphp
    @vite('resources/js/app.js', 'build/@web')

    <script>
      (function(w,d,s,r,n){w.TrustpilotObject=n;w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)};
          a=d.createElement(s);a.async=1;a.src=r;a.type='text/java'+s;f=d.getElementsByTagName(s)[0];
          f.parentNode.insertBefore(a,f)})(window,document,'script', 'https://invitejs.trustpilot.com/tp.min.js', 'tp');
          tp('register', '3WBSIA3kln5TW0Fc');
    </script>

    <link rel="stylesheet" href="/css/sportradar-widget.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/css/swiper.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.0/js/swiper.min.js"></script>
  </head>
  <body>
    <div id="app">
      <layout></layout>
    </div>

    @if((new \App\License\License())->hasFeature('phoenixSport'))
      <script type="text/javascript">
        (function(a,b,c,d,e,f,g,h,i){a[e]||(i=a[e]=function(){(a[e].q=a[e].q||[]).push(arguments)},i.l=1*new Date,i.o=f,
            g=b.createElement(c),h=b.getElementsByTagName(c)[0],g.async=1,g.src=d,g.setAttribute("n",e),h.parentNode.insertBefore(g,h)
        )})(window,document,"script","https://phoenix-gambling.com:2053/widget/loader?url=https://widgets.sir.sportradar.com/sportradar/widgetloader","SIR", {
          language: 'nl'
        });
      </script>

      <!--suppress JSUnresolvedLibraryURL -->
      <script src="https://embed.twitch.tv/embed/v1.js"></script>
    @endif
  </body>
</html>
