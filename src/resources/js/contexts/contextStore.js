export default {
    id: null,
    name: null,
    thumbnails: [],
    thumbnail_id: null,
    parentContextId: null,
    indexInContexts: null,
    tags: [],
    order: null,

    setData(data) {
        this.id = data.id;
        this.name = data.name;
        this.thumbnails = data.thumbnails;
        this.thumbnail_id = data.thumbnail_id;
        this.parentContextId = data.parentContextId;
        this.tags = data.tags;
        this.order = data.order;
    },

    getData() {
        return {
            id: this.id,
            name: this.name,
            thumbnail_id: this.thumbnail_id,
            parent_context_id:
                this.parentContextId ??
                Alpine.store("contexts").currentContext.id,
            order: this.order,
            tags: this.tags.map((item) => item.id),
        };
    },

    clearThumbnail() {
        this.thumbnails.length = 0;
        this.thumbnail_id = null;
    },

    addTag(tag) {
        if (this.tags.find((item) => item.id === tag.id)) {
            return;
        }
        this.tags.push(tag);
    },

    clear() {
        this.id = null;
        this.name = null;
        this.thumbnails.length = 0;
        this.thumbnail_id = null;
        this.parentContextId = null;
        this.order = null;
        this.indexInContexts = null;
        this.tags.length = 0;
    },
};
