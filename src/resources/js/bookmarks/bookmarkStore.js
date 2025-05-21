export default {
    id: null,
    name: null,
    link: null,
    thumbnail: null,
    thumbnailFile: null,
    thumbnail_id: null,
    context_id: null,
    indexInContexts: null,
    order: null,

    setData(data) {
        this.id = data.id;
        this.name = data.name;
        this.link = data.link;
        this.thumbnail = data.thumbnail;
        this.thumbnail_id = data.thumbnail_id;
        this.context_id = data.context_id;
        this.order = data.order;
    },

    getData() {
        return {
            id: this.id,
            name: this.name,
            link: this.link,
            // thumbnail: this.thumbnail,
            thumbnailFile: this.thumbnailFile,
            thumbnail_id: this.thumbnail_id,
            context_id: Alpine.store("contexts").currentContext.id,
            order: this.order,
        };
    },

    clearThumbnail() {
        this.thumbnail = null;
        this.thumbnailFile = null;
        this.thumbnail_id = null;
    },

    clear() {
        this.id = null;
        this.name = null;
        this.link = null;
        this.thumbnail = null;
        this.thumbnailFile = null;
        this.thumbnail_id = null;
        this.context_id = null;
        this.order = null;
        this.indexInContexts = null;
    },
};
