
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

messaging.onMessage(payload => {
  const notificationOption = {
    body: payload.notification.body,
    icon: 'assets/images/iconos/48x48.png',
    vibrate: [125, 75, 125, 275, 200, 275, 125, 75, 125, 275, 200, 600, 200, 600]
  };

  if (Notification.permission === 'granted') {

    var notification = new Notification(payload.notification.title, notificationOption);

    notification.onclick = function (e) {
      const respuesta = clients.matchAll()
        .then(clientes => {

          let cliente = clientes.find(c => {
            return c.visibilityState === 'visible';
          });

          if (cliente !== undefined) {
            cliente.navigate(payload.notification.click_action);
            cliente.focus();
          } else {
            clients.openWindow(payload.notification.click_action);
          }

          return notificacion.close();

        });
      e.preventDefault();
      //window.open(payload.notification.click_action);
      //notification.close();
      e.waitUntil(respuesta);
    }
  }
});

if (navigator.serviceWorker) {
  
  window.addEventListener('load', function () {

    navigator.serviceWorker.register('sw.js').then(
      registration => {
        console.log('[SW] Service Worker registrado con éxito');
        
        messaging.requestPermission()
          .then(() => {
            messaging.useServiceWorker(registration);
            return messaging.getToken();
          })
          .then(token => {
            console.log('[FCM: ]', token);
            navigator.serviceWorker.ready.then(() => {
             push_sendSubscriptionToServer(token, 'POST');
            })
          })
          .catch(err => {
            console.log('[FCM] Error: ', err);
          });
      },
      e => {
        console.log('[SW Service Worker registro fallido]');
      }
    );

  })
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

function push_sendSubscriptionToServer(token, method) {

  return fetch('/push_token_fcm.php', {
    method,
    body: JSON.stringify({ token: token })
  }).then(() => token)
    .catch(console.log);
}