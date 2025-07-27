import "./bootstrap";
// import "../css/app.css";
import Alpine from "alpinejs";

import alertsStore from "./alerts/alertsStore";
import contextsStore from "./contexts/contextsStore";
import contextStore from "./contexts/contextStore";
import bookmarkStore from "./bookmarks/bookmarkStore";
import searchStore from "./search/searchStore";
import tagsStore from "./tags/tagsStore";
import filterStore from "./filter/filterStore";
import tagStore from "./tags/tagStore";
import additionalDataStore from "./additionalData/additionalDataStore";
import globalValuesStore from "./globalValues/globalValuesStore";
// import "./contexts/contextsStore.js";

Alpine.store("alerts", alertsStore);
Alpine.store("contexts", contextsStore);
Alpine.store("context", contextStore);
Alpine.store("bookmark", bookmarkStore);
Alpine.store("search", searchStore);
Alpine.store("tags", tagsStore);
Alpine.store("tag", tagStore);
Alpine.store("filter", filterStore);
Alpine.store("additionalData", additionalDataStore);
Alpine.store("globalValuesStore", globalValuesStore);

window.Alpine = Alpine;

Alpine.start();
