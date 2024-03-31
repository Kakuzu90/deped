require('./bootstrap');

import { createApp } from 'vue';
import VueLadda from 'vue3-ladda';

import RepairRequest from './components/RepairRequest';
import ReturnRequest from './components/ReturnRequest';
import NewRequest from './components/NewRequest';
import UpdateRequest from './components/UpdateRequest';

const app = createApp({});


app.component('vue-ladda', VueLadda);
app.component('repair-request', RepairRequest);
app.component('return-request', ReturnRequest);
app.component('new-request', NewRequest);
app.component('update-request', UpdateRequest);
app.mount("#app");