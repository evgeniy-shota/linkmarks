export default {
    indexInTags: null,
    id: null,
    name: "",
    description: "",
    isLoading: false,
    callback: null,

    setData(tag, index) {
        this.id = tag.id;
        this.name = tag.name;
        this.description = tag.description;
    },

    getData() {
        return {
            id: this.id,
            name: this.name,
            description: this.description,
        };
    },

    clear() {
        this.id = null;
        this.name = "";
        this.description = "";
        this.isLoading = false;
        this.callback = null;
    },
};
