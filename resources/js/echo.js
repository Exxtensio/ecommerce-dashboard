import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

const host = document.querySelector('meta[name="websocket-host"]')?.getAttribute('content')
const port = document.querySelector('meta[name="websocket-port"]')?.getAttribute('content')
const scheme = document.querySelector('meta[name="websocket-scheme"]')?.getAttribute('content')

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'sellexx',
    wsHost: host,
    namespace: 'Exxtensio.EcommerceDashboard.Events',
    wsPort: scheme === 'https' ? null : port,
    wssPort: scheme === 'http' ? port : null,
    forceTLS: scheme === 'https',
    enabledTransports: ['ws', 'wss'],
});
