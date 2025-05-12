// import Alpine from "alpinejs";

document.addEventListener("alpine:init", () => {
    Alpine.store("contexts", {
        rootContext: null,
        previousContext: null,
        currentContext: null,
        orderNumber: null,
        data: null,
        breadcrumbs: [],

        initial(rootContext, data) {
            this.rootContext = rootContext;
            this.previousContext = null;
            this.currentContext = rootContext;
            this.orderNumber = null;
            this.data = data;
            this.breadcrumbs = [rootContext];
        },

        getLastOrder() {
            return this.data.length > 0
                ? this.data[this.data.length - 1].order
                : 0;
        },

        pushToBreadcrumbs(data) {
            this.breadcrumbs.push(data);
        },

        spliceBreadcrumbs(index, count = this.breadcrumbs.length) {
            console.log(this.breadcrumbs);
            this.breadcrumbs.splice(index + 1, count);
            console.log(this.breadcrumbs);
        },

        setData(data, previousContext, currentContext, orderNumber) {
            this.previousContext = previousContext;
            this.currentContext = currentContext;
            this.orderNumber = orderNumber;
            this.data = data;
        },

        clearData() {
            this.previousContext = null;
            this.currentContext = this.rootContext;
            this.orderNumber = null;
            this.data = [];
            this.breadcrumbs = [];
        },
    });
});
