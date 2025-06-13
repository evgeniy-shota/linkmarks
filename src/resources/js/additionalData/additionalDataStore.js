export default {
    isLoading: false,
    contexts: [],
    thumbnails: [],

    initial(contexts = [], thumbnails = []) {
        this.contexts = contexts;
        this.thumbnails = thumbnails;
    },
    getContext(id) {
        return this.contexts.find((item) => item.id === id) ?? {};
    },
    clear() {
        this.contexts.length = 0;
        this.thumbnails.length = 0;
        this.isLoading = false;
    },
    clearThumbnails() {
        this.thumbnails.length = 0;
    },
};
