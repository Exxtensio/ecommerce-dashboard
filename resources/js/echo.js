import Echo from 'laravel-echo'

import Pusher from 'pusher-js'
window.Pusher = Pusher

const host = document.querySelector('meta[name="sellexx-host"]')?.getAttribute('content')
const isSecure = window.location.protocol === "https:"

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'sellexx',
    wsHost: host,
    namespace: 'Exxtensio.EcommerceDashboard.Events',
    wsPort: isSecure ? null : 6001,
    wssPort: isSecure ? 6001 : null,
    forceTLS: isSecure,
    enabledTransports: ['ws', 'wss'],
});
