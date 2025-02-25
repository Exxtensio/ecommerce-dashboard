import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

const host = document.querySelector('meta[name="sellexx-host"]')?.getAttribute('content')

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'sellexx',
    wsHost: host,
    namespace: 'Exxtensio.EcommerceDashboard.Events',
    wsPort: 8080,
    wssPort: 8080,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
