import "./bootstrap";
import Alpine from "alpinejs";

import alertsStore from "./alerts/alertsStore";
import contextsStore from "./contexts/contextsStore";
import contextStore from "./contexts/contextStore";
import bookmarkStore from "./bookmarks/bookmarkStore";
// import "./contexts/contextsStore.js";

Alpine.store("alerts", alertsStore);
Alpine.store("contexts", contextsStore);
Alpine.store("context", contextStore);
Alpine.store("bookmark", bookmarkStore);

window.Alpine = Alpine;

Alpine.start();
