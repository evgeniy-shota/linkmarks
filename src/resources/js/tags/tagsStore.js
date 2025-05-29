export default {
    tags: [],

    setTags(tagsArray) {
        if (this.tags.length == 0) {
            this.tags = tagsArray;
            this.tags.map((item) => (item.state = null));
        } else {
            while (tagsArray.length > 0) {
                let tag = tagsArray.pop();
                let indexInArray = this.tags.findIndex(
                    (item) => item.id === tag.id
                );

                if (indexInArray != -1) {
                    this.tags[indexInArray] = {
                        ...tag,
                        state: this.tags[indexInArray].state,
                    };
                } else {
                    this.tags.push({ ...tag, state: null });
                }
            }
        }
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
        this.tags.map((item) => (item.state = state));
    },

    getTags(state, idOnly = true) {
        let filtered = [];

        for (let i = 0; i < this.tags.length; i++) {
            if (this.tags[i].state == state) {
                filtered.push(idOnly ? this.tags[i].id : this.tags[i]);
            }
        }

        // let filtered = this.tags.filter((item) => item.state == state);
        return filtered;
    },

    clear() {
        this.tags.length = 0;
    },
};
