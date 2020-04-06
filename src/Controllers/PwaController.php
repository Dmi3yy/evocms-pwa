<?php
namespace EvolutionCMS\Dmi3yy\Pwa\Controllers;

class PwaController
{
    public $evo;

    public function __construct()
    {
        $this->evo = evolutionCMS();
    }

    /**
     * @param int $data
     * @param array $data_status
     * @param mixed $response_status
     */
    public function ResponseJSON($data='', $data_status='success', $response_status=200) {
        header('HTTP/1.1 '.$response_status );
        echo json_encode($data);
        exit();
    }
    public function ResponseJS($data='', $data_status='success', $response_status=200) {
        header('HTTP/1.1 '.$response_status );
        header('Content-Type: application/javascript');
        echo $data;
        exit();
    }

    public static function evopwa()
    {
        $evo = evolutionCMS();
        $settings = $evo->getConfig('pwa');

        return '<link rel="manifest" href="/evo-manifest.json">
                <meta name="theme-color" content="'.$settings['theme_color'].'"> 
                <link rel="apple-touch-icon" href="'.$settings['apple-touch-icon'].'"> 
                <script type="text/javascript">
                    if (\'serviceWorker\' in navigator) {
                        navigator.serviceWorker.register(\'/evo-serviceworker.js\', {
                            scope: \'.\' 
                        }).then(function (registration) {
                            console.log(\'ServiceWorker registration successful with scope: \', registration.scope);
                        }, function (err) {
                            console.log(\'ServiceWorker registration failed: \', err);
                        });
                    }
                </script>';
    }


    public function manifest()
    {
        $this->ResponseJSON($this->evo->getConfig('pwa'));
    }


    public function serviceworker()
    {
        $this->ResponseJS("'use strict';

            /**
             * Service Worker of Evolution CMS 2 PWA
             */
             
            const cacheName = 'evopwa';//+ new Date().getTime();
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