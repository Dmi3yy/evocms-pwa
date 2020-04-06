# Evolution CMS 2.0 PWA
Evolution CMS 2.0 Progressive Web App package

## Install

`php artisan package:installrequire dmi3yy/evocms-pwa '*'` in you **core/** folder

`php artisan pwa:install`

## Установка  

`php artisan package:installrequire dmi3yy/evocms-pwa '*'` в папке **core/**

`php artisan pwa:install`



## Настройка:
1. Редактируем фаил: **/core/custom/config/cms/settings/pwa.php**
```
     'name' => 'Название проекта',
     'short_name' => 'Кратное название проекта',
     'apple-touch-icon' => '/assets/images/evo-logo.png',
     'theme_color' => '#000000',
     'background_color' => '#ffffff',
     'display' => 'standalone',
     'scope' => '/',
     'start_url' => '/',
     'icons' => [
         [
             'src'=> '/assets/images/evo-logo.png',
             'sizes'=>'192x192'
         ],
         [
             'src'=> '/assets/images/evo-logo.png',
             'sizes'=>'512x512'
         ]
     ] 
```

2. В head вставляем @evopwa(), или можно вставить следующий код: 
```
<link rel="manifest" href="/evo-manifest.json">
<meta name="theme-color" content="#000000"> 
<link rel="apple-touch-icon" href="/assets/images/evo-logo.png"> 
<script type="text/javascript">
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/evo-serviceworker.js', {
            scope: '.' 
        }).then(function (registration) {
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function (err) {
            console.log('ServiceWorker registration failed: ', err);
        });
    }
</script>
```



#TODO
- генерация manifest.json 
- генерация serviceworker.js
- генерация @evopwa
- очистка кеша и перегенерация 
- настройка прекеша файлов 
- настройка прекеша страниц 
