<template>
    <el-drawer
        v-model="is.show"
        title="分类详情"
        size="560"
    >
        <el-skeleton :rows="8" :loading="is.loading" animated/>
        <el-descriptions :column="1" label-align="right" label-size="small" border v-if="!is.loading">
            <el-descriptions-item label="ID"> {{ item?.id }}</el-descriptions-item>
            <el-descriptions-item label="上级分类">
                {{ item.parent ? item.parent.title : '-' }}
            </el-descriptions-item>
            <el-descriptions-item label="分类标题"> {{ item.title }}</el-descriptions-item>
            <el-descriptions-item label="分类简介"> {{ item.brief ?? '-' }}</el-descriptions-item>
            <el-descriptions-item label="显示排序"> {{ item.asc_num }}</el-descriptions-item>
            <el-descriptions-item label="商品数量"> {{ item.goods_count }}</el-descriptions-item>
            <!--
                        <el-descriptions-item label="显示状态">
                            <el-tag size="small" effect="plain" :type="item.is_show ? 'success' : 'danger'" round>
                                {{ item.is_show ? '显示' : '隐藏' }}
                            </el-tag>
                        </el-descriptions-item>
            -->
            <el-descriptions-item label="创建时间"> {{ utcDateTimeToLocal(item.created_at) }}</el-descriptions-item>
            <el-descriptions-item label="更新时间"> {{ utcDateTimeToLocal(item.updated_at) }}</el-descriptions-item>
        </el-descriptions>
        <template #footer>
            <!--            <el-button @click="is.show=false">返回</el-button>-->
            <el-button type="primary" @click="() => actions.toEdit($parent.$refs.formRef, item.id)">编辑</el-button>
        </template>
    </el-drawer>
</template>

<script setup>
import { api } from '../../../stores/goodsCategory';
import { ShowActions } from '../../../lib';
import { utcDateTimeToLocal } from '../../../lib/util';

const actions = new ShowActions(api);
const {
    is,
    item,
    show,
} = actions;

defineExpose({
    show: id => show(id, { with_parent: 1 })
});
</script>
