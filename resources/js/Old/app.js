require('./bootstrap');


// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

import { library } from '@fortawesome/fontawesome-svg-core'
import { faBars, faChevronDown, faUserSecret } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faFacebookSquare,  faGooglePlusG, faTwitter } from '@fortawesome/free-brands-svg-icons'

library.add(faBars);
library.add(faChevronDown)
library.add(faFacebookSquare)
library.add(faGooglePlusG)
library.add(faTwitter)

const el = document.getElementById('app');

const app = createApp({
        render: () =>
            h(InertiaApp, {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            }),
    })
    .mixin({ methods: { route } })
    .use(InertiaPlugin);

app.component('v-icon', FontAwesomeIcon );
app.mount(el);

InertiaProgress.init({ color: '#4B5563' });
