<template>

    <el-dialog v-model="is.show" :title="is.edit ? '编辑角色' : '新建角色'" @closed="onHide" width="560" draggable align-center>
        <el-skeleton :rows="10" :loading="is.loading" animated/>
        <el-form
            ref="formRef"
            :rules="formRules"
            :model="item"
            label-width="100px"
            @submit.prevent="save"
            v-if="!is.loading"
        >
            <input type="submit" v-show="false">

            <el-form-item label="角色名称" prop="title">
                <el-input v-model="item.title" autocomplete="off"/>
            </el-form-item>

            <el-form-item label="角色简介">
                <el-input v-model="item.brief" type="textarea" autocomplete="off"/>
            </el-form-item>

            <el-form-item label="显示排序" prop="asc_num">
                <el-slider v-model="item.asc_num" show-input/>
                <div class="tips">从小到大显示</div>
            </el-form-item>

            <el-form-item label="状态" prop="enabled">
                <el-switch
                    width="60"
                    v-model="item.enabled"
                    active-text="启用"
                    inactive-text="停用"
                    inline-prompt
                />
            </el-form-item>

            <el-form-item label="访问授权">
                <div class="permissions-box w-full">
                    <div class="">
                        <el-checkbox v-model="item.isAllPermission">全部权限</el-checkbox>
                    </div>
                    <div class="permission-tree w-full" v-show="! item.isAllPermission">
                        <el-tree
                            ref="permissionsTreeRef"
                            :data="permissions"
                            @check-change="onPermissionChange()"
                            show-checkbox
                            default-expand-all
                            :default-checked-keys="item.permissions"
                            node-key="name"
                            highlight-current
                            :props="{label: 'title'}"
                        ></el-tree>
                    </div>
                </div>
            </el-form-item>
        </el-form>

        <template #footer>
            <el-button @click="reset">重置</el-button>
            <el-button type="primary" @click="save" v-loading="is.submitting">确定</el-button>
        </template>
    </el-dialog>
</template>

<script setup>
import { ref } from 'vue';
import { roles } from '../../../stores/staff';
import { api, listBindings, permissions } from '../../../stores/staffRole';
import { FormActions } from '../../../lib';

const permissionsTreeRef = ref();
const formRef = ref();
const formRules = {
    title: {
        required: true,
        message: '请输入角色名称',
        trigger: ['blur', 'change']
    }
};

class Actions extends FormActions {
    // 此方法在 FormActions 中有调用到，需要继承重写该方法
    onHide () {
        super.onHide();
        permissionsTreeRef.value.setCheckedKeys([], false);
    }

    save () {
        if (this.item.isAllPermission) {
            this.item.permissions = ['*'];
        }
        super.save().then(resp => {
            setTimeout(() => {
                roles.value = Object.values(listBindings.listData.items || []);
            }, 200);
            if (!this.is.edit) {
                permissionsTreeRef.value.setCheckedKeys([], false);
            }
        });
    }

    reset () {
        super.reset();
        this.item.isAllPermission = this.item.permissions?.includes('*');
        permissionsTreeRef.value.setCheckedKeys(this.item.permissions || [], false);
    }

    edit (id) {
        super.edit(id).then(resp => {
            this.item.isAllPermission = this.item.permissions?.includes('*');
            permissionsTreeRef.value.setCheckedKeys(this.item.permissions || [], false);
        });
    };
}

const onPermissionChange = () => {
    item.permissions = permissionsTreeRef.value.getCheckedKeys();
};

const actions = new Actions(api, listBindings, formRef, {
    title: '',
    brief: '',
    asc_num: 50,
    enabled: true,
    permissions: [],
    isAllPermission: false
});
const {
    is,
    item,
    create,
    edit,
    reset,
    save,
    onHide
} = actions;

defineExpose({
    create,
    edit
});
</script>
