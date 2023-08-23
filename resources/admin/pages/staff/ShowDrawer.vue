<template>
    <el-drawer v-model="is.show" title="人员详情" size="560">
        <el-skeleton :rows="10" :loading="is.loading" animated/>
        <el-descriptions :column="1" label-align="right" label-size="small" border v-if="!is.loading">
            <el-descriptions-item label="ID">{{ item.id }}</el-descriptions-item>
            <el-descriptions-item label="部门">
                <el-tag class="mr-3" size="small" v-if="item.department" plain>
                    {{ item.department.title }}
                </el-tag>
                <span v-else>-</span>
            </el-descriptions-item>
            <el-descriptions-item label="角色">
                <el-tag class="mr-3" size="small" type="danger" round v-if="item.is_super">超级管理员</el-tag>
                <el-tag class="mr-3" size="small" round v-for="role in item.roles" :key="role.id">
                    {{ role.title }}
                </el-tag>
            </el-descriptions-item>
            <el-descriptions-item label="用户名">{{ item.name ?? '-' }}</el-descriptions-item>
            <el-descriptions-item label="姓名">{{ item.realname ?? '-' }}</el-descriptions-item>
            <el-descriptions-item label="手机号">{{ item.mobile ?? '-' }}</el-descriptions-item>
            <el-descriptions-item label="邮箱">{{ item.email ?? '-' }}</el-descriptions-item>
            <el-descriptions-item label="状态">
                <el-tag size="small" effect="plain" :type="item.enabled ? 'success' : 'danger'" round>
                    {{ item.enabled ? '启用' : '停用' }}
                </el-tag>
            </el-descriptions-item>
            <el-descriptions-item label="创建时间">{{ utcDateTimeToLocal(item.created_at) }}</el-descriptions-item>
            <el-descriptions-item label="更新时间">{{ utcDateTimeToLocal(item.updated_at) }}</el-descriptions-item>
            <el-descriptions-item label="删除时间" v-if="item.deleted_at">{{ utcDateTimeToLocal(item.deleted_at) }}</el-descriptions-item>
        </el-descriptions>
        <template #footer>
            <el-button type="primary" @click="() => actions.toEdit($parent.$refs.formRef, item.id)" :disabled="!canUpdate">编辑</el-button>
        </template>
    </el-drawer>
</template>

<script setup>
import { api } from '../../stores/staff';
import { ShowActions } from '../../lib';
import { utcDateTimeToLocal } from '../../lib/util';
import { useCan, userInfo } from '../../lib/auth';

const actions = new ShowActions(api);
const {
    is,
    item
} = actions;

const canUpdate = useCan('staff.update') && (!item.is_super || userInfo.is_super);

defineExpose({
    show: (id) => actions.show(id, { with_department: true })
});
</script>
