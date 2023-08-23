import { onActivated, onDeactivated, reactive, watch } from 'vue';
import { confirmSaveSuccess } from './message';
import { refKit, bindThisDeeper, routeTo } from './util';

/**
 * 新建/编辑表单页面通用的数据和方法。
 */
export class FormActions {
    item = reactive({}); // 不能在继承中重写 item、is 属性，否则丢失响应性

    is = reactive({
        edit: false, // 是否是编辑，否则是新建
        show: false, // 是否显示表单对话框，仅弹窗模式表单有效
        submitting: false, // 是否正在提交数据
        loading: false, // 是否正在加载数据
        showValidateError: true // 是否显示表单输入框错误提示
    });

    _stashForCreate = null;
    _stashForEdit = null;

    api = null;
    listBindings = null;
    formRef = null;

    constructor (api, listBindings, formRef, itemDefaultValue = null) {
        bindThisDeeper(this, [
            'create', 'edit', 'onHide', 'haltValidateCall', 'save', 'reset', 'initStashForCreate', 'initFormPage'
        ]);

        this.api = api;
        this.listBindings = listBindings;
        this.formRef = formRef;

        if (itemDefaultValue) {
            // 如果传入默认值，则把默认值赋给 item
            refKit.cloneDeep(this.item, itemDefaultValue);
        } else {
            // 如果不设置默认值，则使用 item 的初始值作为默认值（支持在继承类中写初始值）
            itemDefaultValue = refKit.cloneDeep({}, this.item);
        }

        this.initStashForCreate(itemDefaultValue);
    }

    // 打开新建表单
    create () {
        this.is.edit = false;
        this.is.show = true;
    }

    // 打开编辑表单
    // 此时先展示骨架，item 初始值不用管。
    edit (id, params) {
        this.is.edit = true;
        this.is.show = true;
        this.is.loading = true;
        return this.api.fetchItem(id, params).then(resp => {
            Object.assign(this.item, resp.item);
            this._stashForEdit = resp.item;
            return resp;
        }).finally(() => {
            this.is.loading = false;
        });
    }

    // 返回列表处理（后退或关闭对话框式页面）
    // 模态窗表单：此方法应绑定于 el-dialog 的 closed 事件，关闭按钮事件处理应为 is.show = false
    onHide () {
        this.haltValidateCall(() => {
            if (this.is.edit) {
                this._stashForEdit = null; // 清空编辑原始数据
                this._stashForCreate.restore(); // 退出编辑，从暂存恢复 item
                return;
            }
            this._stashForCreate.store(); // 退出新建，暂存 item
        });
    }

    // 表单数据变化触发表单验证，先关闭错误显示，
    // 恢复数据完成后，清空提示再打开
    haltValidateCall (callback) {
        // 停止显示验证错误信息
        this.is.showValidateError = false;
        // 执行回调
        callback.call();
        setTimeout(() => {
            // 等待回调完成后清除错误信息
            this.formRef?.value?.clearValidate();
            this.is.showValidateError = true;
        }, 200);
    }

    // 表单验证通过后提交保存
    save () {
        return this.formRef.value.validate().then(async () => {
            this.is.submitting = true;
            const method = this.is.edit ? 'put' : 'post';
            return await this.api[method](this.item).then(resp => {
                const message = `${this.is.edit ? '编辑' : '新建'}成功`;
                const cancelBtnText = this.is.edit ? '继续编辑' : '继续新建';
                confirmSaveSuccess(message, cancelBtnText).then(() => {
                    // 返回列表页
                    // 模态窗 closed 事件绑定到 onHide 方法，对话框关闭完成时将触发该事件
                    // 路由页面返回时，手动触发 onHide 方法
                    this.is.show = false;
                }).catch(() => {
                    console.log('catch...');
                });

                if (this.is.edit) {
                    // 编辑成功，记录编辑 item
                    this._stashForEdit = { ...this.item };
                } else {
                    // 创建成功，恢复 item 默认值
                    this.haltValidateCall(() => this._stashForCreate.reset());
                }

                if (!this.listBindings) {
                    return resp;
                }

                if (resp.items) {
                    this.listBindings.listData.items = resp.items;
                    return resp;
                }

                this.listBindings.reload();

                return resp;
            }).finally(() => {
                this.is.submitting = false;
            });
        });
    }

    // 重置表单
    reset () {
        this.haltValidateCall(() => {
            if (this.is.edit) {
                refKit.cloneDeep(this.item, this._stashForEdit); // 编辑时，重置为服务器端返回的数据
            } else {
                this._stashForCreate.reset(); // 新建时，重置为 默认数据
            }
        });
    }

    initStashForCreate (itemDefaultValue) {
        let data = null;
        this._stashForCreate = {
            // 新建成功后/点击重置按钮重置 item
            reset: () => {
                refKit.cloneDeep(this.item, itemDefaultValue);
                data = null;
            },
            // 编辑退出时，存回 item
            restore: () => {
                refKit.cloneDeep(this.item, data || itemDefaultValue);
            },
            // 新建退出时保存 item
            store: () => {
                if (Object.keys(this.item).length === 0) {
                    // target 已被清空，再暂存就把暂存数据弄没了
                    // 事件被重复触发引起
                    return;
                }
                data = { ...this.item };
            }
        };
    }

    initFormPage (props, listRoutePath) {
        // 监听 is.show，点击提交成功提示框的"返回列表"按钮时，跳转到列表页
        watch(() => this.is.show, (isShow) => {
            isShow || routeTo(listRoutePath);
        });

        // 显示页面时，选择编辑或新建
        onActivated(() => {
            props.id > 0 ? this.edit(props.id) : this.create();
        });

        // 退出页面时，执行 onHide()
        onDeactivated(() => this.onHide());
    }
}
