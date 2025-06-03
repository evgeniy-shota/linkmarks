export default {
    isApplied: false,
    applyToContexts: true,
    applyToBookmarks: true,
    deepFiltration: false,
    currentContextId: null,
    groupDeepFiltration:false,

    toggleDeepFilration(currentContextId) {
        this.deepFiltration = !this.deepFiltration;
        this.currentContextId = this.deepFiltration ? currentContextId : null;
    },
};
