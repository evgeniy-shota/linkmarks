import "./bootstrap";
import Alpine from "alpinejs";

import alertsStore from "./alerts/alertsStore";
// import "./contexts/contextsStore.js";

Alpine.store("alerts", alertsStore);

window.Alpine = Alpine;

Alpine.start();
