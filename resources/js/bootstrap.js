import axios from 'axios';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';

import {Button, Card, DatePicker, FloatLabel, InputMask, ToastService} from "primevue";
import Stepper from 'primevue/stepper';
import StepList from 'primevue/steplist';
import StepPanels from 'primevue/steppanels';
import StepItem from 'primevue/stepitem';
import Step from 'primevue/step';
import StepPanel from 'primevue/steppanel';
import Dialog from 'primevue/dialog';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura
                }
            })
            .use(ToastService);

        app.component('Button', Button)
            .component('Card', Card)
            .component('Stepper', Stepper)
            .component('StepList', StepList)
            .component('StepPanels', StepPanels)
            .component('StepItem', StepItem)
            .component('Step', Step)
            .component('StepPanel', StepPanel)
            .component('Dialog', Dialog)
            .component('Select', Select)
            .component('DatePicker', DatePicker)
            .component('InputText', InputText)
            .component('FloatLabel', FloatLabel)
            .component('InputMask', InputMask)
            .component('Toast', Toast)

        app.mount(el)
    },
})
