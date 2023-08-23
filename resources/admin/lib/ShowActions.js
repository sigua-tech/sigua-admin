import { reactive } from 'vue';

/**
 * 预览页通用的数据和方法。
 */
export class ShowActions {
    item = reactive({});

    is = reactive({
        show: false,
        loading: true
    });

    constructor (api) {
        this.api = api;
        this.show = this.show.bind(this);
    }

    show (id, params = {}) {
        this.is.show = true;
        this.is.loading = true;
        return this.api.fetchItem(id, params).then(resp => {
            Object.assign(this.item, resp.item);
            return resp;
        }).finally(() => {
            this.is.loading = false;
        });
    }

    toEdit(formRef, id) {
        this.is.show = false;
        formRef.edit(id);
    }
}
