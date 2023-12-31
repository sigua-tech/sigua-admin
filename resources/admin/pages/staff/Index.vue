<template>
    <el-container>
        <el-aside class="w-60">
            <el-collapse class="side-filter w-60" v-model="filterActiveNames">
                <el-collapse-item title="部门" name="1">
                    <el-link @click="listData.query.department_id=''" :class="!listData.query.department_id ? 'active' : ''">
                        <span class="pl-3">全部部门</span>
                    </el-link>
                    <el-tree
                        :data="departments"
                        node-key="id"
                        default-expand-all
                        :expand-on-click-node="false"
                        :props="{label: 'title', class: data => data.id === listData.query.department_id ? 'active' : ''}"
                        :indent="14"
                        @node-click="node => { listData.query.department_id=node.id }"
                    >
                    </el-tree>
                </el-collapse-item>

                <el-collapse-item title="角色" name="2">
                    <el-link @click="listData.query.role_id=''" :class="!listData.query.role_id ? 'active' : ''">
                        <span class="pl-3">全部角色</span>
                    </el-link>
                    <template v-for="role in roles" :key="role.id">
                        <el-link
                            :class="listData.query.role_id === role.id ? 'active' : ''"
                            @click="listData.query.role_id=role.id"
                        >
                            <span class="pl-3">{{ role.title }}</span>
                        </el-link>
                    </template>
                </el-collapse-item>
            </el-collapse>
        </el-aside>

        <el-container class="is-vertical">
            <div class="op-bar flex mb-4">
                <div class="ops flex-auto">
                    <PopconfirmDelete type="button" label="批量删除" @confirm="batOperate()" :disabled="!cans.batDelete || listData.batOperateIds.length === 0"/>
                    <el-button type="primary" @click="$refs.formRef.create()" :disabled="!cans.create">+ 新建</el-button>
                </div>
                <div class="filters flex-none">
                    <el-input clearable @clear="search()" v-model="listData.query.keyword" @keyup.enter="search()"
                              class="keyword mx-1.5" placeholder="账号/姓名/手机号/邮箱">
                        <template #prefix>
                            <span class="iconfont icon-search"></span>
                        </template>
                    </el-input>

                    <el-button type="primary" @click="search()">搜索</el-button>
                </div>
            </div>

            <div class="box staff-list">
                <el-table style="width: 100%;" :data="listData.items" @selection-change="items => selectChange(items)" v-loading="listData.isLoading" stripe>
                    <el-table-column type="selection" width="45" align="center" fixed></el-table-column>
                    <el-table-column prop="id" label="ID" width="45" class-name="cell-px-0"></el-table-column>
                    <el-table-column prop="name" label="账号" width="100" class-name="item-name cell-px-0"></el-table-column>
                    <el-table-column prop="realname" label="姓名" width="120"></el-table-column>

                    <!--
                    <el-table-column prop="mobile" label="手机号" width=""></el-table-column>
                    <el-table-column prop="email" label="邮箱" width=""></el-table-column>
-->
                    <el-table-column prop="department" label="部门">
                        <template #default="{row}">
                            <el-tag v-if="row.department">{{ row.department.title }}</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="角色" width="">
                        <template #default="{row}">
                            <el-tag class="mr-3" size="small" type="danger" round v-if="row.is_super">超级管理员</el-tag>
                            <template v-if="row.roles && row.roles.length > 0">
                                <el-tag class="mr-1.5" size="small" round v-for="role in row.roles" :key="role.id">
                                    {{ role.title }}
                                </el-tag>
                            </template>
                            <span v-else-if="!row.is_super">-</span>
                        </template>
                    </el-table-column>

                    <el-table-column label="状态" prop="enabled" width="70" align="center" class-name="cell-px-0" sortable>
                        <template #default="{ row }">
                            <el-tag size="small" :type="row.enabled ? 'success' : 'danger'" round>
                                {{ row.enabled ? '启用' : '停用' }}
                            </el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column prop="created_at" label="创建时间" width="150" align="center" class-name="cell-px-0">
                        <template #default="{ row }">
                            {{ utcDateTimeToLocal(row.created_at) }}
                        </template>
                    </el-table-column>

                    <el-table-column label="操作" width="136" class-name="list-item-ops" align="center" fixed="right">
                        <template #default="{row}">
                            <el-link type="primary" @click="$refs.showRef.show(row.id)" :disabled="!cans.view">查看</el-link>
                            <el-link type="primary" @click="$refs.formRef.edit(row.id)" :disabled="!cans.update || (row.is_super && !userInfo.is_super)">编辑</el-link>
                            <PopconfirmDelete title="您确定要删除该人员吗？" @confirm="destroy(row.id)" :disabled="!cans.delete || row.is_super"/>
                        </template>
                    </el-table-column>
                </el-table>
            </div>

            <div class="btm-ops mt-3">
                <PopconfirmDelete
                    type="button"
                    label="批量删除"
                    @confirm="batOperate()"
                    :disabled="!cans.batDelete || listData.batOperateIds.length === 0"
                />
            </div>

            <Pagination :bindings="listBindings"/>
        </el-container>
    </el-container>

    <ShowDrawer ref="showRef"/>
    <FormDialog ref="formRef"/>
</template>

<script setup>
import { watch, ref } from 'vue';
import { utcDateTimeToLocal } from '../../lib/util';
import { departments, listBindings, roles } from '../../stores/staff';
import { userInfo, useCans } from '../../lib/auth';
import ShowDrawer from './ShowDrawer.vue';
import FormDialog from './FormDialog.vue';

// 权限
const cans = useCans('staff', ['view', 'create', 'update', 'delete', 'batDelete']);

const {
    listData,
    destroy,
    selectChange,
    load,
    search,
    batOperate
} = listBindings;

const filterActiveNames = ref(['1', '2']);

watch([
    () => listData.query.role_id,
    () => listData.query.department_id
], () => search());

load({
    get_roles: 1,
    get_departments: 1
}, {
    roles,
    departments
});

</script>

<style lang="scss">
.staff-list .item-name .cell {
    overflow: visible !important;
}
.side-filter {
    position: absolute;
    left: 0;
    bottom: 0;
    top: -1px;
    overflow-y: auto;
    background: #fff;
    border-right: 1px solid #e8e8e8;
    .el-collapse-item__header {
        padding-left: 1rem;
        --el-collapse-header-height: 40px;
    }
    .el-collapse-item__content {
        padding: 0 0 .5rem;
        a {
            padding-left: 1rem;
            display: block;
            line-height: 2.5rem;
            &:hover:after {
                border-bottom: none;
            }
        }
        a.active, .active > .el-tree-node__content {
            background: #f2f3f5;
            color: var(--el-color-primary);
        }
        .el-tree-node__content {
            height: 32px;
        }
    }
}
</style>
