const STATIC_CACHE_NAME     = 'static-v2.4';
const INMUTABLE_CACHE_NAME  = 'inmutable-v2.3';
const DYNAMIC_CACHE_NAME    = 'dynamic-v2.4';

const APP_SHELL = [
    'index.php',
    'assets/fonts/flaticon/Flaticon.woff2',
    'assets/js/vendor_bundle.min.js',
    'assets/css/core.min.css',
    'assets/css/vendor_bundle.min.css',
    'favicon.png',
    'assets/images/logo/logo_dark.png',
    'assets/images/logo/logo_light.png',
    'assets/js/app.js',
    'assets/js/core.min.js',
    'demo.files/svg/icons/contractapprove.svg',
    'demo.files/svg/icons/files/pdf.svg',
    'assets/images/iconos/144x144.png'
];

const APP_SHELL_INMUTABLE = [
    'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap'
];

// instalamos el SW
self.addEventListener('install', event => {
    
    const cacheStatic = caches.open(STATIC_CACHE_NAME)
        .then(cache => cache.addAll(APP_SHELL));

    const cacheInmutable = caches.open(INMUTABLE_CACHE_NAME)
        .then(cache => cache.addAll(APP_SHELL_INMUTABLE));

    event.waitUntil(Promise.all([cacheStatic, cacheInmutable]));
    self.skipWaiting();

});

// activamos el SW
self.addEventListener('activate', e => {

    const respuesta = caches.keys().then(keys => {

        keys.forEach(key => {

            if (key !== STATIC_CACHE_NAME && key.includes('static')) {

                return caches.delete(key);

            }
            if (key !== DYNAMIC_CACHE_NAME && key.includes('dynamic')) {

                return caches.delete(key);

            }
        });
    });

    e.waitUntil(respuesta);
});


self.addEventListener('fetch', e => {
    
    if (e.request.url.includes('chrome-extension') || 

        e.request.url.includes('push_') || 
        e.request.url.includes('/firebase') || 
        e.request.url.includes('/administracion') ||
        e.request.url.includes('g-callbak.php') ||
        e.request.url.includes('g-config.php') ||
        e.request.url.includes('googleapis') ||
        e.request.url.includes('hybridauth.php')) {
        return e;

    }

    const respuesta = caches.match(e.request)
        
        .then(resp => {
            
            if (resp) {
                // console.log(APP_SHELL[1]);
                // console.log(e.request.url);
                // if (APP_SHELL.includes(e.request.url)) {

                //     return actualizaCache(STATIC_CACHE_NAME, e.request, resp);
                
                // }
                return caches.open(STATIC_CACHE_NAME).then( cache => {
                    return cache.match(e.request).then(cacheResponse => {
                        
                        if (cacheResponse) {
                            return actualizaCache(STATIC_CACHE_NAME, e.request, resp);
                        } else {
                            return resp;
                        }

                    })
                    
                });

            } else {
                
                return fetch(e.request.url).then(newResp => {

                    console.log('[D]', e.request.url);
                    return actualizaCache(DYNAMIC_CACHE_NAME, e.request, newResp);

                });
            }

        }).catch(err => {
            console.log('Sin conexión: ',err);
        });

    e.respondWith(respuesta);

});

// guarda en el cache 
function actualizaCache(cacheName, req, res) {
    
    if (res.ok) {
        
        return caches.open(cacheName).then( cache => {

            cache.put( req, res.clone() );
            return res.clone();

        });
        
    } else {
        return res;
    }
}

self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const sendNotification = body => {
        // you could refresh a notification badge here with postMessage API
        console.log('SW: ', body);
        const title = body.notification.title;
        const options = {
            body : body.notification.body,
            icon : 'assets/images/iconos/48x48.png',
            vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600],
            data: {url : body.notification.click_action}
        }
        return self.registration.showNotification(title, options);
    };

    if (event.data) {
        const message = JSON.parse(event.data.text());
        event.waitUntil(sendNotification(message));
    }
    
});

// Cierra la notificacion
self.addEventListener('notificationclose', e => {
    console.log('Notificación cerrada', e);
});


self.addEventListener('notificationclick', e => {


    const notificacion = e.notification;
    const accion = e.action;


    console.log({ notificacion, accion });
    // console.log(notificacion);
    // console.log(accion);
    

    const respuesta = clients.matchAll()
    .then( clientes => {

        let cliente = clientes.find( c => {
            return c.visibilityState === 'visible';
        });

        if ( cliente !== undefined ) {
            cliente.navigate( notificacion.data.url );
            cliente.focus();
        } else {
            clients.openWindow( notificacion.data.url );
        }

        return notificacion.close();

    });

    e.waitUntil( respuesta );


});