export default {
    rootContext: null,
    previousContext: null,
    currentContext: null,
    orderNumber: null,
    data: [],
    breadcrumbs: [],

    initial(rootContext, currentContext, data, breadcrumbs) {
        this.rootContext = rootContext;
        this.previousContext = null;
        this.currentContext = currentContext;
        this.orderNumber = null;
        this.data = data;
        this.breadcrumbs = [breadcrumbs];
    },

    getLastOrder() {
        return this.data.length > 0 ? this.data[this.data.length - 1].order : 0;
    },

    pushToBreadcrumbs(data) {
        this.breadcrumbs.push(data);
    },

    spliceBreadcrumbs(index, count = this.breadcrumbs.length) {
        this.breadcrumbs.splice(Number(index) + 1, count);
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
        this.data.length = 0;
        this.breadcrumbs = [];
    },
};
