<template>
    <div class="op-bar flex mb-4">
        <div class="ops flex-auto">
            <PopconfirmDelete type="button" label="批量删除" @confirm="batOperate()"
                              :disabled="!cans.batDelete || listData.batOperateIds.length === 0"/>
            <el-button type="primary" @click="routeTo('/goods/create')" :disabled="!cans.create">+ 新建</el-button>
        </div>
        <div class="filters flex-none">
            <el-tree-select
                class="w-50"
                placeholder="选择分类"
                v-model="listData.query.cat_id"
                :data="category.listData.items"
                :props="{label: 'title', value: 'id', multiple: true}"
                :render-after-expand="false"
                default-expand-all
                collapse-tags
                collapse-tags-tooltip
                clearable
                filterable
                checkStrictly
            />
            <el-input clearable @clear="search()" v-model="listData.query.keyword" @keyup.enter="search()"
                      class="keyword mx-1.5" placeholder="搜索标题">
                <template #prefix>
                    <span class="iconfont icon-search"></span>
                </template>
            </el-input>
            <el-button type="primary" @click="search()">搜索</el-button>
        </div>
    </div>

    <div class="box">
        <el-table :data="listData.items" @selection-change="(items) => selectChange(items)" stripe
                  v-loading="listData.isLoading">
            <el-table-column type="selection" width="45" align="center" fixed></el-table-column>
            <el-table-column prop="id" label="ID" width="50" class-name="cell-px-0" fixed></el-table-column>

            <el-table-column prop="code" label="编码" width="80" class-name="cell-px-0"></el-table-column>

            <el-table-column prop="title" label="商品">
                <template #default="{ row }">
                    <img :src="row.album[0].thumb" v-if="row.album[0]" width="42" align="left" class="mr-2">
                    {{ row.title }}
                </template>
            </el-table-column>

            <el-table-column prop="usp" label="卖点"></el-table-column>

            <el-table-column label="分类" width="">
                <template #default="{row}">
                    <template v-if="row.categories.length > 0">
                        <el-tag class="mr-1.5" size="small" v-for="cat in row.categories" :key="cat.id">
                            {{ cat.title }}
                        </el-tag>
                    </template>
                    <span v-else>-</span>
                </template>
            </el-table-column>

            <el-table-column label="标签" width="">
                <template #default="{row}">
                    <template v-if="row.tags.length > 0">
                        <el-tag class="mr-1.5" size="small" v-for="tag in row.tags" :key="tag.id">
                            {{ tag.title }}
                        </el-tag>
                    </template>
                    <span v-else>-</span>
                </template>
            </el-table-column>

            <el-table-column prop="is_on_sale" label="状态" width="45" class-name="cell-px-0" align="center">
                <template #default="{row}">
                    <span class="text-success" v-if="row.is_on_sale">在售</span>
                    <span class="text-danger" v-else>停售</span>
                </template>
            </el-table-column>

            <el-table-column prop="asc_num" label="显示排序" width="60" class-name="cell-px-0" align="center"></el-table-column>

            <el-table-column prop="created_at" label="创建时间" width="168" align="center">
                <template #default="{ row }">
                    {{ utcDateTimeToLocal(row.created_at) }}
                </template>
            </el-table-column>
            <el-table-column label="操作" width="136" class-name="list-item-ops" align="center" fixed="right">
                <template #default="{row}">
                    <el-link type="primary" @click="$refs.showRef.show(row.id)" :disabled="!cans.view">详情</el-link>
                    <el-link type="primary" :href="`#/goods/${row.id}/edit`" :disabled="!cans.update">编辑</el-link>
                    <PopconfirmDelete
                        title="您确定要删除该商品吗？"
                        @confirm="destroy(row.id)"
                        :disabled="!cans.delete"
                    />
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

    <ShowDrawer ref="showRef"/>
</template>

<script setup>
import { useCans } from '../../lib/auth';
import { routeTo, utcDateTimeToLocal } from '../../lib/util';
import { listBindings } from '../../stores/goods';
import { listBindings as category } from '../../stores/goodsCategory';
import { watch } from 'vue';
import ShowDrawer from './ShowDrawer.vue';

// 权限
const cans = useCans('goods', ['view', 'create', 'update', 'delete', 'batDelete']);

const {
    listData,
    batOperate,
    search,
    selectChange,
    destroy,
    load
} = listBindings;

load({ get_category: 1 }, { categories: category.listData.items });

watch(() => listData.query.cat_id, () => search());
</script>
