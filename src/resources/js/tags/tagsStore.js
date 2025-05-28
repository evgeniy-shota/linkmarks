export default {
    tags: [],

    setTags(tagsArray) {
        // check if tags not empty - update array
        this.tags = tagsArray;
        this.tags.map((item) => (item.state = null));
    },

    toggleTag(index) {
        switch (this.tags[index].state) {
            case true:
                this.tags[index].state = false;
                break;
            case false:
                this.tags[index].state = null;
                break;
            default:
                this.tags[index].state = true;
        }
    },

    setAllTagsState(state) {
        console.log("all tags");
        this.tags.map((item) => (item.state = state));
    },

    getTags(state) {
        let filtered = this.tags.filter((item) => item.state == state);
        return filtered;
    },

    clear() {
        this.tags.length = 0;
    },
};
