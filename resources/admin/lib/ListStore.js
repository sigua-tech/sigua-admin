import { isReactive, isRef, reactive, watch } from 'vue';
import { deleteMessage, responseMessage } from './message';
import { bindThisDeeper, loading } from './util';

/**
 * 列表页通用的数据和方法。
 */
export class ListStore {
    listData = reactive({
        items: [],
        path: '',
        total: 0,
        per_page: 15,
        current_page: 0,
        query: {},
        batOperateIds: [],
        isLoading: false,
        isLoaded: false
    });

    constructor (api) {
        this.api = api;
        // 给方法绑定 this，实例解构赋值获得函数名也能正常执行
        bindThisDeeper(this, [
            'load', 'reload', 'search', 'selectChange', 'batOperate', 'destroy'
        ]);
        watch(() => this.listData.items, () => {
            this.listData.isLoaded = true;
        });
    }

    /**
     * 加载列表页数据。
     * @param { object } appendQuery 额外增加的查询参数（不会同步到 listData.query 中）
     * @param { object } dataMap 数据映射表 { 返回变量名: 变量名数据要赋值的变量} 默认已绑定返回的 resp.items 和 resp.listData，建议不用重复绑定这两个。
     * @returns {*} Promise
     */
    load (appendQuery = {}, dataMap = {}) {
        const query = Object.assign({}, this.listData.query, appendQuery);

        this.listData.isLoading = true;
        return this.api.fetchItems(query).then((resp) => {
            // 默认返回绑定
            // 1、如果返回 resp.listData，则绑定到 listData
            if (resp.listData?.items) {
                if (resp.listData.items.length === 0 && this.listData.query?.page && parseInt(this.listData.query.page) > 1) {
                    // 返回数据为空，如果不是第页则显示前一页
                    --this.listData.query.page;
                    return this.load(dataMap, appendQuery);
                }
                Object.assign(this.listData, resp.listData);
            }

            // 2、如果返回 resp.items，则绑定到 listData.items
            if (resp.items) {
                this.listData.items = resp.items;
            }

            // 绑定返回数据，赋值给待接收的变量
            for (const key in dataMap) {
                if (typeof resp[key] === 'undefined') {
                    continue;
                }
                const valObj = dataMap[key];
                if (isRef(valObj)) {
                    valObj.value = resp[key];
                    continue;
                }
                if (isReactive(valObj)) {
                    Object.assign(valObj, resp[key]);
                    continue;
                }
                dataMap[key] = resp[key];
            }

            return resp;
        }).finally(() => {
            this.listData.isLoading = false;
            this.listData.isLoaded = true;
        });
    }

    /**
     * 重新加载列表页数据。
     * @param { int } page
     */
    reload (page = 0) {
        // if (!this.listData.isLoaded) {
        //     return;
        // }
        page ||= this.listData.current_page; // 默认使用当前页
        if (page > 1) {
            this.listData.query.page = page; // 页码同步到请求参数
        } else {
            delete this.listData.query.page; // 默认请求第一页
        }

        return this.load();
    }

    checkLoaded () {
        if (this.listData.isLoaded) {
            return;
        }
        this.load();
    }

    search () {
        this.reload(1);
    }

    selectChange (items) {
        const ids = [];
        for (const item of items) {
            ids.push(item.id);
        }
        this.listData.batOperateIds = ids;
    }

    // 批量操作
    batOperate (operate = 'delete') {
        loading.show();
        return this.api.batOperate(operate, {
            ids: this.listData.batOperateIds
        }).then((resp) => {
            responseMessage(resp);
            this.listData.batOperateIds = [];
            this.reload();
        });
    }

    // 列表页删除事件回调
    destroy (id) {
        loading.show({ title: '正在删除...' });
        return this.api.delete(id).then(resp => {
            deleteMessage(resp);

            if (!resp.success) {
                return resp;
            }

            // 列表页显示全部记录时，返回列表刷新记录
            if (resp.items) {
                this.listData.items = resp.items;
                return resp;
            }

            // 分页列表页数据刷新
            this.reload();

            return resp;
        });
    }
}
