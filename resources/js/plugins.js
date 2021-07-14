const laratalk = window.Laratalk

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios')

window.axios.defaults.baseURL = `/${laratalk.path}/api`

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: laratalk.echo.driver,
    key: laratalk.echo.key,
    cluster: laratalk.echo.options.cluster,
    forceTLS: laratalk.echo.options.useTLS,
    namespace: 'Laratalk.Events'
});
