require('./bootstrap');

import { createApp } from 'vue';
import VueLadda from 'vue3-ladda';
import RequestItem from './components/RequestItem';

const app = createApp({});

app.component('request-item', RequestItem);
app.component('vue-ladda', VueLadda);
app.mount("#app");