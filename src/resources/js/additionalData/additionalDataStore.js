export default {
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
    },
};
