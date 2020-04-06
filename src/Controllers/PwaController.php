<?php
namespace EvolutionCMS\Dmi3yy\Pwa\Controllers;

class PwaController
{
    public $evo;

    public function __construct()
    {
        $this->evo = evolutionCMS();
        $this->evo->getSettings();
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
                
                <link href="/assets/images/evo-logo.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                <link href="/assets/images/evo-logo.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
                
                
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
                  "start_url": "/",
                  "icons": [
                    {
                      "src": "/assets/images/evo-logo.png",
                      "sizes": "192x192"
                    },
                    {
                      "src": "/assets/images/evo-logo.png",
                      "sizes": "512x512"
                    }
                  ]
                }');
    }

    public function serviceworkerlaravel()
    {
        $this->ResponseJS("var staticCacheName = \"evo-pwa-v\" + new Date().getTime();
                                var filesToCache = [
                                   '/assets/images/evo-logo.png',
                                   '/blog.html',
                                   '/theme/css/bulma.css',
                                   '/theme/css/jquery-ui.css',
                                   'https://use.fontawesome.com/releases/v5.6.3/css/all.css',
                                   '/theme/css/style.css',
                                   '/theme/images/logo.png',
                                   '/theme/js/jquery-3.3.1.min.js',
                                   '/theme/js/jquery-ui.min.js',
                                   '/theme/js/scripts.js',
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


    public function serviceworker()
    {
        $this->ResponseJS("'use strict';

            /**
             * Service Worker of Evolution CMS 2 PWA
             */
             
            const cacheName = 'evopwa-003';//+ new Date().getTime();
            const startPage = '/';
            const offlinePage = '/offline.html';
            const filesToCache = [
                startPage, 
                offlinePage, 
                '/assets/images/evo-logo.png',
                '/blog.html',
                '/theme/css/bulma.css',
                '/theme/css/jquery-ui.css',
                'https://use.fontawesome.com/releases/v5.6.3/css/all.css',
                '/theme/css/style.css',
                '/theme/images/logo.png',
                '/theme/js/jquery-3.3.1.min.js',
                '/theme/js/jquery-ui.min.js',
                '/theme/js/scripts.js',
            ];
            const neverCacheUrls = [/\/manager/];
            
            // Install
            self.addEventListener('install', function(e) {
                console.log('EvoPWA service worker installation');
                e.waitUntil(
                    caches.open(cacheName).then(function(cache) {
                        console.log('EvoPWA service worker caching dependencies');
                        filesToCache.map(function(url) {
                            return cache.add(url).catch(function (reason) {
                                return console.log('EvoPWA: ' + String(reason) + ' ' + url);
                            });
                        });
                    })
                );
            });
            
            // Activate
            self.addEventListener('activate', function(e) {
                console.log('EvoPWA service worker activation');
                e.waitUntil(
                    caches.keys().then(function(keyList) {
                        return Promise.all(keyList.map(function(key) {
                            if ( key !== cacheName ) {
                                console.log('EvoPWA old cache removed', key);
                                return caches.delete(key);
                            }
                        }));
                    })
                );
                return self.clients.claim();
            });
            
            // Fetch
            self.addEventListener('fetch', function(e) {
                
                // Return if the current request url is in the never cache list
                if ( ! neverCacheUrls.every(checkNeverCacheList, e.request.url) ) {
                  console.log( 'EvoPWA: Current request is excluded from cache.' );
                  return;
                }
                
                // Return if request url protocal isn't http or https
                if ( ! e.request.url.match(/^(http|https):\/\//i) )
                    return;
                
                // Return if request url is from an external domain.
                if ( new URL(e.request.url).origin !== location.origin )
                    return;
                
                // For POST requests, do not use the cache. Serve offline page if offline.
                if ( e.request.method !== 'GET' ) {
                    e.respondWith(
                        fetch(e.request).catch( function() {
                            return caches.match(offlinePage);
                        })
                    );
                    return;
                }
                
                // Revving strategy
                if ( e.request.mode === 'navigate' && navigator.onLine ) {
                    e.respondWith(
                        fetch(e.request).then(function(response) {
                            return caches.open(cacheName).then(function(cache) {
                                cache.put(e.request, response.clone());
                                return response;
                            });  
                        })
                    );
                    return;
                }
            
                e.respondWith(
                    caches.match(e.request).then(function(response) {
                        return response || fetch(e.request).then(function(response) {
                            return caches.open(cacheName).then(function(cache) {
                                cache.put(e.request, response.clone());
                                return response;
                            });  
                        });
                    }).catch(function() {
                        return caches.match(offlinePage);
                    })
                );
            });
            
            // Check if current url is in the neverCacheUrls list
            function checkNeverCacheList(url) {
                if ( this.match(url) ) {
                    return false;
                }
                return true;
            }");
    }




}