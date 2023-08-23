<template>
    <div class="op-bar flex mb-4">
        <div class="ops flex-auto">
            <PopconfirmDelete type="button" label="批量删除" @confirm="batOperate()" :disabled="!cans.batDelete || listData.batOperateIds.length === 0"/>
            <el-button type="primary" @click="$refs.formRef.create()" :disabled="!cans.create">+ 新建</el-button>
        </div>
        <div class="filters flex-none">
            <el-input clearable @clear="search()" v-model="listData.query.keyword" @keyup.enter="search()" class="keyword mx-1.5" placeholder="搜索标签名">
                <template #prefix>
                    <span class="iconfont icon-search"></span>
                </template>
            </el-input>
            <el-button type="primary" @click="search()">搜索</el-button>
        </div>
    </div>

    <div class="box">
        <el-table
            :data="listData.items"
            @selection-change="items => selectChange(items)"
            v-loading="listData.isLoading"
        >
            <el-table-column type="selection" width="45" align="center" fixed></el-table-column>
            <el-table-column prop="id" label="ID" width="60" fixed></el-table-column>
            <el-table-column prop="title" label="标签"></el-table-column>
            <el-table-column prop="article_count" label="文章数" width="72" align="center"></el-table-column>
            <el-table-column prop="created_at" label="创建时间" width="168" align="center">
                <template #default="{ row }">
                    {{ utcDateTimeToLocal(row.created_at) }}
                </template>
            </el-table-column>
            <el-table-column label="操作" width="120" class-name="list-item-ops" align="center" fixed="right">
                <template #default="scope">
                    <el-link type="primary" @click="$refs.formRef.edit(scope.row.id)" :disabled="!cans.update">编辑</el-link>
                    <PopconfirmDelete title="您确定要删除该标签吗？" @confirm="destroy(scope.row.id)" :disabled="!cans.delete"/>
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
    <FormDialog ref="formRef"/>
</template>

<script setup>
import { listBindings } from '../../../stores/articleTag';
import { useCans } from '../../../lib/auth';
import FormDialog from './FormDialog.vue';
import { utcDateTimeToLocal } from '../../../lib/util';

// 权限
const cans = useCans('article.tag', ['create', 'update', 'delete', 'batDelete']);
const {
    listData,
    search,
    load,
    destroy,
    batOperate,
    selectChange
} = listBindings;
load();
</script>
