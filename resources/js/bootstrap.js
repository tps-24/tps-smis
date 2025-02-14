import Echo from 'laravel-echo';
import Pusher from 'pusher-js'; // if using Pusher
// or if using Socket.IO
import io from 'socket.io-client';

window.Pusher = Pusher; // for Pusher
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key',
//     cluster: 'your-cluster',
//     encrypted: true
// });

// For Socket.IO:
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001', // if using Echo server
    transports: ['websocket']
});
