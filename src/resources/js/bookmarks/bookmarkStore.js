export default {
    id: null,
    name: null,
    link: null,
    thumbnail: null,
    thumbnailFile: null,
    thumbnail_id: null,
    context_id: null,
    tags: [],
    indexInContexts: null,
    order: null,

    setData(data) {
        this.id = data.id;
        this.name = data.name;
        this.link = data.link;
        this.thumbnail = data.thumbnail;
        this.thumbnail_id = data.thumbnail_id;
        this.context_id = data.context_id;
        this.tags = data.tags ?? [];
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
            context_id:
                this.context_id ?? Alpine.store("contexts").currentContext.id,
            tags: this.tags.map((item) => item.id),
            order: this.order,
        };
    },

    addTag(tag) {
        if (this.tags.find((item) => item.id === tag.id)) {
            return;
        }
        this.tags.push(tag);
    },

    setThumbnailId(id, thumbnail) {
        this.clearThumbnail();
        this.thumbnail_id = id;
        this.thumbnail = thumbnail;
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
        this.tags.length = 0;
        this.indexInContexts = null;
    },
};
