<?php
namespace EvolutionCMS\Dmi3yy\Pwa\Controllers;

class PwaController
{
    public function __construct()
    {
        $this->evo = EvolutionCMS();
    }


    /**
     * @param int $data
     * @param array $data_status
     * @param mixed $response_status
     */
    public function ResponseJSON($data='', $data_status='success', $response_status=200) {
        header('HTTP/1.1 '.$response_status );
        echo $data;
        exit();
    }
    public function ResponseJS($data='', $data_status='success', $response_status=200) {
        header('HTTP/1.1 '.$response_status );
        header('Content-Type: application/javascript');
        echo $data;
        exit();
    }

    public function evopwa()
    {
        return '<!-- Web Application Manifest -->
                <link rel="manifest" href="/evo-manifest.json">
                <!-- Chrome for Android theme color -->
                <meta name="theme-color" content="#000000">
                
                <!-- Add to homescreen for Chrome on Android -->
                <meta name="mobile-web-app-capable" content="yes">
                <meta name="application-name" content="PWA">
                <link rel="icon" sizes="512x512" href="/assets/images/evo-logo.png">
                
                <!-- Add to homescreen for Safari on iOS -->
                <meta name="apple-mobile-web-app-capable" content="yes">
                <meta name="apple-mobile-web-app-status-bar-style" content="black">
                <meta name="apple-mobile-web-app-title" content="PWA">
                <link rel="apple-touch-icon" href="/assets/images/evo-logo.png">
                <!--
                <link href="/images/icons/splash-640x1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-750x1334.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1242x2208.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1125x2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-828x1792.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1242x2688.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1536x2048.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1668x2224.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-1668x2388.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/images/icons/splash-2048x2732.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                -->
                
                <!-- Tile for Win8 -->
                <meta name="msapplication-TileColor" content="#ffffff">
                <meta name="msapplication-TileImage" content="/assets/images/evo-logo.png">
                
                <script type="text/javascript">
                    // Initialize the service worker
                    if (\'serviceWorker\' in navigator) {
                        navigator.serviceWorker.register(\'/evo-serviceworker.js\', {
                            scope: \'.\' 
                        }).then(function (registration) {
                            // Registration was successful
                            console.log(\'Evolution PWA: ServiceWorker registration successful with scope: \', registration.scope);
                        }, function (err) {
                            // registration failed :(
                            console.log(\'Evolution PWA: ServiceWorker registration failed: \', err);
                        });
                    }
                </script>';
    }

    public function manifest()
    {
        $this->ResponseJSON('{
                  "name": "Evolution CMS 2.0 PWA",
                  "short_name": "EVOCMS",
                  "theme_color": "#2196f3",
                  "background_color": "#2196f3",
                  "display": "standalone",
                  "scope": "/",
                  "start_url": "/"
                }');
    }

    public function serviceworker()
    {
        $this->ResponseJS("var staticCacheName = \"evo-pwa-v\" + new Date().getTime();
                                var filesToCache = [
                                    '/assets/images/evo-logo.png',
                                ];
                                
                                // Cache on install
                                self.addEventListener(\"install\", event => {
                                    this.skipWaiting();
                                    event.waitUntil(
                                        caches.open(staticCacheName)
                                            .then(cache => {
                                                return cache.addAll(filesToCache);
                                            })
                                    )
                                });
                                
                                // Clear cache on activate
                                self.addEventListener('activate', event => {
                                    event.waitUntil(
                                        caches.keys().then(cacheNames => {
                                            return Promise.all(
                                                cacheNames
                                                    .filter(cacheName => (cacheName.startsWith(\"pwa-\")))
                                                    .filter(cacheName => (cacheName !== staticCacheName))
                                                    .map(cacheName => caches.delete(cacheName))
                                            );
                                        })
                                    );
                                });
                                
                                // Serve from Cache
                                self.addEventListener(\"fetch\", event => {
                                    event.respondWith(
                                        caches.match(event.request)
                                            .then(response => {
                                                return response || fetch(event.request);
                                            })
                                            .catch(() => {
                                                return caches.match('offline');
                                            })
                                    )
                                });");
    }


}