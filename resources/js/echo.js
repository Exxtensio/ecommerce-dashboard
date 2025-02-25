import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'sellexx',
    wsHost: import.meta.env.VITE_WS_HOST,
    namespace: 'Exxtensio.EcommerceDashboard.Events',
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
