import "./bootstrap";
import Alpine from "alpinejs";

import alertsStore from "./alerts/alertsStore";
import contextsStore from "./contexts/contextsStore";
import contextStore from "./contexts/contextStore";
import bookmarkStore from "./bookmarks/bookmarkStore";
import searchStore from "./search/searchStore";
import tagsStore from "./tags/tagsStore";
import filterStore from "./filter/filterStore";
// import "./contexts/contextsStore.js";

Alpine.store("alerts", alertsStore);
Alpine.store("contexts", contextsStore);
Alpine.store("context", contextStore);
Alpine.store("bookmark", bookmarkStore);
Alpine.store("search", searchStore);
Alpine.store("tags", tagsStore);
Alpine.store("filter", filterStore);

window.Alpine = Alpine;

Alpine.start();
