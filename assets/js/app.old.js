
var firebaseConfig = {
  apiKey: "AIzaSyDEEoG2PcHCMOptr4ZjZPojaJvAKlaTZL0",
  authDomain: "valorizasoporte.firebaseapp.com",
  projectId: "valorizasoporte",
  storageBucket: "valorizasoporte.appspot.com",
  messagingSenderId: "837991320731",
  appId: "1:837991320731:web:44d8b38180fe86f58a0a3e",
  measurementId: "G-4J5PVPG963"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
messaging.requestPermission()
.then(() => {
  console.log('Tiene permisos');
  return messaging.getToken();
})
.then(token => {
  console.log(token);
})
.catch(err => {
  console.log('Error: ', err);
});

messaging.onMessage(payload => {
  console.log(payload);
  const notificationOption = {
    body: payload.notification.body,
    icon : 'assets/images/iconos/48x48.png',
    vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600]
  };

  if (Notification.permission==='granted') {
    
    var notification = new Notification(payload.notification.title, notificationOption);
    
    notification.onclick = function (e) {
      e.preventDefault();
      window.open(payload.notification.click_action);
      notification.close();
    }
  }
});
const applicationServerKey = 
    'BDat1UJTEmymeWZamO4MQ9iYpyfuKgPbEFJ8ZE4UfcMiqgNJ0jpLfqAhCf-0oKp8oFyBCk2H5LE1lb0P4tIPZxU';

if (navigator.serviceWorker) {
    // if (url.includes('localhost')) {
        
    //     swLocation  = '/valoriza_soporte/sw.js';
    // }

    window.addEventListener('load', function() {
        
        navigator.serviceWorker.register('/sw.js').then(
            () => {
                console.log('[SW] Service Worker registrado con éxito');

                push_subscribe()
                //.then(enviarNotificacion())
                .then(checkNotificationPermission)
            },
            e => {
                console.log('[SW Service Worker registro fallido]');
            }
        );

    })
}

function push_subscribe() {
    
    return checkNotificationPermission()
      .then(() => navigator.serviceWorker.ready)
      .then(serviceWorkerRegistration =>
        serviceWorkerRegistration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: urlBase64ToUint8Array(applicationServerKey),
        })
      )
      .then(subscription => {
        // Subscription was successful
        // create subscription on your server
        console.log('Enviada suscripción notificaciones al servidor', subscription);
        return push_sendSubscriptionToServer(subscription, 'POST');
      })
      .then(subscription => subscription) // update your UI
      .catch(e => {
        if (Notification.permission === 'denied') {
          // The user denied the notification permission which
          // means we failed to subscribe and the user will need
          // to manually change the notification permission to
          // subscribe to push messages
          console.warn('Notifications are denied by the user.');
          
        } else {
          // A problem occurred with the subscription; common reasons
          // include network errors or the user skipped the permission
          console.error('Impossible to subscribe to push notifications', e);
        }
      });
  }

  function checkNotificationPermission() {
    return new Promise((resolve, reject) => {
      if (Notification.permission === 'denied') {
        return reject(new Error('Notificaciones push bloqueadas.'));
      }

      if (Notification.permission === 'granted') {
        return resolve();
      }

      if (Notification.permission === 'default') {
        return Notification.requestPermission().then(result => {
          if (result !== 'granted') {
            reject(new Error('Error en permisos de notificación push'));
          } else {
            resolve();
          }
        });
      }

      return reject(new Error('Permisos desconocidos'));
    });
  }

  checkNotificationPermission();

  function enviarNotificacion() {
    navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {
        if (!subscription) {
          //alert('Por favor active las notificaciones');
          /*$.smallBox({
            title : "Ding Dong!",
            content : "Someone's at the door...shall one get it sir? <p class='text-align-right'><a href='javascript:void(0);' class='btn btn-primary btn-sm'>Yes</a> <a href='javascript:void(0);'  onclick='noAnswer();' class='btn btn-danger btn-sm'>No</a></p>",
            color : "#296191",
            //timeout: 8000,
            icon : "fa fa-bell swing animated"
          });*/
          return;
        }
        
        const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];
        const jsonSubscription = subscription.toJSON();
        fetch('/push_send_notification.php', {
          method: 'POST',
          body: JSON.stringify(Object.assign(jsonSubscription, { contentEncoding })),
        });
      })
  }
  
  function push_updateSubscription() {
    navigator.serviceWorker.ready
      .then(serviceWorkerRegistration => serviceWorkerRegistration.pushManager.getSubscription())
      .then(subscription => {

        if (!subscription) {
          console.log('No hay suscripción')
          // We aren't subscribed to push, so set UI to allow the user to enable push
          return;
        }
        
        // Keep your server in sync with the latest endpoint
        return push_sendSubscriptionToServer(subscription, 'PUT');
      })
      .then(subscription => subscription && changePushButtonState('enabled')) // Set your UI to show they have subscribed for push messages
      .catch(e => {
        console.error('Error when updating the subscription', e);
      });
  }

  function push_sendSubscriptionToServer(subscription, method) {
    const key = subscription.getKey('p256dh');
    const token = subscription.getKey('auth');
    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0];

    return fetch('/push_subscription.php', {
      method,
      body: JSON.stringify({
        endpoint: subscription.endpoint,
        publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
        authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
        contentEncoding,
      }),
    }).then(() => subscription)
    .catch(console.log);
  }


  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
  }