import "./bootstrap";

import { createApp } from "vue";
import App from "./App.vue";
import router from "./router"; // Router dosyasını import et

/* import Font Awesome core */
import { library } from "@fortawesome/fontawesome-svg-core";

/* import font awesome icon component */
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

/* import specific icons */
import {
    faFutbol,
    faList,
    faChartLine,
    faPlay,
    faForwardStep,
    faRedo,
    faSpinner,
    faExclamationTriangle,
    faChevronRight,
    faRotateLeft,
    faBolt,
    faBoltLightning,
    faCheckCircle,
    faInfoCircle,
    faTimes,
} from "@fortawesome/free-solid-svg-icons";

/* add icons to the library */
library.add(
    faFutbol,
    faList,
    faChartLine,
    faPlay,
    faForwardStep,
    faRedo,
    faSpinner,
    faExclamationTriangle,
    faChevronRight,
    faRotateLeft,
    faBolt,
    faBoltLightning,
    faCheckCircle,
    faInfoCircle,
    faTimes
);

const app = createApp(App);
app.use(router); // Router'ı kullan
app.component("font-awesome-icon", FontAwesomeIcon); // Register component globally
app.mount("#app");
