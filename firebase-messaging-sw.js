importScripts('https://www.gstatic.com/firebasejs/8.2.10/firebase-app.js');
// importScripts('https://www.gstatic.com/firebasejs/8.2.10/firebase-analytics.js');
importScripts('https://www.gstatic.com/firebasejs/8.2.10/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyDEEoG2PcHCMOptr4ZjZPojaJvAKlaTZL0",
    authDomain: "valorizasoporte.firebaseapp.com",
    projectId: "valorizasoporte",
    storageBucket: "valorizasoporte.appspot.com",
    messagingSenderId: "837991320731",
    appId: "1:837991320731:web:44d8b38180fe86f58a0a3e",
    measurementId: "G-4J5PVPG963"
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();



messaging.setBackgroundMessageHandler(payload => {
    console.log('FB: ',payload);
    const notification = JSON.parse(payload);
    const options = {
        body: notification.body,
        icon : 'assets/images/iconos/48x48.png',
        vibrate: [125,75,125,275,200,275,125,75,125,275,200,600,200,600]
    }
    return self.registration.showNotification(payload.notification.title, options);
});
