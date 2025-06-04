export default {
    isApplied: false,
    applyToContexts: true,
    applyToBookmarks: true,
    contextualFiltration: false,
    currentContextId: null,
    groupDeepFiltration: false,

    togglecontextualFiltration(currentContextId) {
        this.contextualFiltration = !this.contextualFiltration;
        this.currentContextId = this.contextualFiltration ? currentContextId : null;
    },

    getFilterParams() {
        let params = [];

        if (this.applyToContexts == false) {
            params.push("discardToContexts=1");
        }

        if (this.applyToBookmarks == false) {
            params.push("discardToBookmarks=1");
        }

        if (this.contextualFiltration === true) {
            params.push("contextualFiltration=1");
        }

        if (this.groupDeepFiltration === true) {
            params.push("groupDeepFiltration=1");
        }

        console.warn(params);
        return params.join("&");
    },

    setDefault() {
        this.isApplied = false;
        this.applyToContexts = true;
        this.applyToBookmarks = true;
        this.contextualFiltration = false;
        this.groupDeepFiltration = false;
        this.currentContextId = null;
    },
};
