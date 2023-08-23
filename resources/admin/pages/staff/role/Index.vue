<template>
    <div class="op-bar flex mb-4" v-if="cans.create">
        <div class="ops" v-cloak>
            <el-button type="primary" @click="$refs.formRef.create()" :disabled="!cans.create">+ 新建</el-button>
        </div>
    </div>
    <div class="box" v-cloak>
        <el-table :data="listData.items" v-loading="listData.isLoading" stripe>
            <el-table-column prop="id" label="ID" width="60" align="center"></el-table-column>
            <el-table-column prop="title" label="标题" width="150"></el-table-column>
            <el-table-column prop="brief" label="简介" width=""></el-table-column>
            <el-table-column prop="staff_count" label="人员数" class-name="cell-px-0" width="70" align="center" sortable/>
            <el-table-column prop="asc_num" label="显示排序" width="80" align="center"></el-table-column>
            <el-table-column label="状态" prop="enabled" width="70" align="center" class-name="cell-px-0" sortable>
                <template #default="{ row }">
                    <el-tag size="small" effect="plain" :type="row.enabled ? 'success' : 'danger'" round>
                        {{ row.enabled ? '启用' : '停用' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column label="操作" width="138" class-name="list-item-ops" align="center">
                <template #default="scope">
                    <el-link type="primary" @click="$refs.showRef.show(scope.row.id)" :disabled="!cans.view">查看</el-link>
                    <el-link type="primary" @click="$refs.formRef.edit(scope.row.id)" :disabled="!cans.update">编辑</el-link>
                    <PopconfirmDelete title="您确定要删除该角色吗？" @confirm="destroy(scope.row.id)" :disabled="!cans.delete"/>
                </template>
            </el-table-column>
        </el-table>
    </div>
    <FormDialog ref="formRef"/>
    <ShowDrawer ref="showRef"/>
</template>

<script setup>
import { useCans } from '../../../lib/auth';
import { listBindings, permissions } from '../../../stores/staffRole';
import FormDialog from './FormDialog.vue';
import ShowDrawer from './ShowDrawer.vue';

// 权限
const cans = useCans('staff.role', ['view', 'create', 'update', 'delete']);
const {
    listData,
    load,
    destroy
} = listBindings;

load({ get_permissions: 1 }, { permissions });
</script>
