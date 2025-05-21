export default {
    id: null,
    name: null,
    thumbnail: null,
    thumbnail_id: null,
    parent_context_id: null,
    indexInContexts: null,
    order: null,

    setData(data) {
        this.id = data.id;
        this.name = data.name;
        this.thumbnail = data.thumbnail;
        this.thumbnail_id = data.thumbnail_id;
        this.parent_context_id = data.parent_context_id;
        this.order = data.order;
    },

    getData() {
        return {
            id: this.id,
            name: this.name,
            link: this.link,
            thumbnail: this.thumbnail,
            thumbnail_id: this.thumbnail_id,
            parent_context_id: Alpine.store("contexts").currentContext.id,
            order: this.order,
        };
    },

    clearThumbnail() {
        this.thumbnail = null;
        this.thumbnail_id = null;
    },

    clear() {
        this.id = null;
        this.name = null;
        this.thumbnail = null;
        this.thumbnail_id = null;
        this.parent_context_id = null;
        this.order = null;
        this.indexInContexts = null;
    },
};
