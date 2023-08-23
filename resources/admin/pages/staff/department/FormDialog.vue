<template>
    <el-dialog
        v-model="is.show"
        :title="`${is.edit ? '编辑' : '新建'}商品部门`"
        @close="onHide()"
        width="560"
        draggable
        align-center
    >
        <el-skeleton :rows="5" :loading="is.loading" animated/>
        <el-form v-if="!is.loading"
                 ref="formRef"
                 :model="item"
                 :rules="formRules"
                 label-width="100px"
                 @submit.prevent="save()"
                 :show-message="is.showValidateError"
        >
            <input type="submit" v-show="false"/>

            <el-form-item label="上级部门" prop="parent_id">
                <el-tree-select
                    v-model="item.parent_id"
                    class="w-full"
                    placeholder="请选择"
                    :data="parentOptions"
                    :props="{label: 'title', value: 'id'}"
                    :render-after-expand="true"
                    check-strictly
                    default-expand-all
                    clearable
                />
            </el-form-item>

            <el-form-item label="部门名称" prop="title">
                <el-input v-model="item.title" placeholder=""/>
            </el-form-item>

            <el-form-item label="部门简介" prop="brief">
                <el-input v-model="item.brief" :row="2" type="textarea" placeholder="" class=""/>
            </el-form-item>

            <el-form-item label="显示排序" prop="asc_num">
                <el-slider v-model="item.asc_num" show-input/>
                <div class="tips">从小到大显示</div>
            </el-form-item>

        </el-form>
        <template #footer>
            <el-button @click="actions.reset()">重置</el-button>
            <el-button type="primary" @click="save()" v-loading="is.submitting">确定</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import { ref } from 'vue';
import { listBindings, api } from '../../../stores/staffDepartment';
import { departments } from '../../../stores/staff';
import { FormActions } from '../../../lib';

const parentOptions = ref([]);
const formRef = ref();
const formRules = {
    title: {
        required: true,
        message: '请输入部门名称',
        trigger: ['blur', 'change']
    },
    asc_num: {
        required: true,
        type: 'number',
        min: 0,
        max: 100,
        message: '序号不能大于100',
        trigger: ['blur', 'change']
        // transform: value => parseInt(value)
    }
};

const actions = new FormActions(api, listBindings, formRef, {
    id: 0,
    title: '',
    asc_num: 50,
    brief: ''
});
const {
    is,
    item,
    onHide
} = actions;

const save = () => {
    actions.save().then(resp => {
        parentOptions.value = resp.options;
        setTimeout(() => {
            departments.value = Object.values(listBindings.listData.items || []);
        }, 200);
    });
};

defineExpose({
    create () {
        actions.create();
        parentOptions.value = Object.values(listBindings.listData.items); // 拷贝数组
    },
    edit (id) {
        actions.edit(id, { get_parent_options: 1 }).then(resp => {
            parentOptions.value = resp.options;
        });
    }
});
</script>
