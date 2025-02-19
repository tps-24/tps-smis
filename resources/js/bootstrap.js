import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Enable Echo with Pusher
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '3a9b85e8ad0fb87a0a56', // Ensure this matches the value in your .env file
    cluster: 'mt1',
    encrypted: true,
});
