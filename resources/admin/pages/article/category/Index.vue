<template>
    <div class="op-bar flex mb-4">
        <div class="ops flex-auto">
            <el-button type="primary" @click="$refs.formRef.create()" :disabled="!cans.create">+ 新建</el-button>
        </div>
    </div>

    <div class="box">
        <el-table :data="listData.items" row-key="id" default-expand-all v-loading="listData.isLoading">
            <el-table-column prop="title" label="标题" class-name="title"/>
            <el-table-column prop="brief" label="简介"/>
            <el-table-column prop="article_count" label="文章数" width="70" align="center" class-name="cell-px-0" sortable/>
            <el-table-column prop="asc_num" label="显示排序" width="88" align="center" class-name="cell-px-0" sortable/>
            <el-table-column label="显示状态" prop="is_show" width="88" align="center" class-name="cell-px-0" sortable>
                <template #default="{ row }">
                    <el-tag size="small" effect="plain" :type="row.is_show ? 'success' : 'danger'" round>
                        {{ row.is_show ? '显示' : '隐藏' }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column prop="id" label="ID" width="70" align="center" sortable/>
            <el-table-column prop="created_at" label="创建时间" width="150" align="center" class-name="cell-px-0">
                <template #default="{ row }">
                    {{ utcDateTimeToLocal(row.created_at) }}
                </template>
            </el-table-column>
            <el-table-column label="操作" width="136" class-name="list-item-ops" align="center" fixed="right">
                <template #default="{ row }">
                    <el-link type="primary" @click="$refs.showRef.show(row.id)" :disabled="!cans.view">查看</el-link>
                    <el-link type="primary" @click="$refs.formRef.edit(row.id)" :disabled="!cans.update">编辑</el-link>
                    <PopconfirmDelete title="您确定要删除该分类吗？" @confirm="destroy(row.id)" :disabled="!cans.delete"/>
                </template>
            </el-table-column>
        </el-table>
    </div>

    <FormDialog ref="formRef"/>
    <ShowDrawer ref="showRef"/>
</template>

<script setup>
import { useCans } from '../../../lib/auth';
import { listBindings } from '../../../stores/articleCategory';
import FormDialog from './FormDialog.vue';
import ShowDrawer from './ShowDrawer.vue';
import { utcDateTimeToLocal } from '../../../lib/util';

const cans = useCans('article.category', ['view', 'create', 'update', 'delete']);

const {
    listData,
    load,
    destroy
} = listBindings;

load();
</script>
