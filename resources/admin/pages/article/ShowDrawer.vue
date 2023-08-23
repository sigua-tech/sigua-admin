<template>
    <el-form ref="formRef" :model="item" label-width="100px" class="show-wp">
        <el-drawer v-model="is.show" title="商品详情" size="720">
            <el-skeleton :rows="10" :loading="is.loading"/>
            <div v-if="!is.loading">
                <div>
<!--                    <h2 class="form-title">基本信息</h2>-->
                    <el-descriptions title="基本信息" :column="1" label-align="right" label-size="small" border>
                        <el-descriptions-item label="商品标题">{{ item.title }}</el-descriptions-item>
                        <el-descriptions-item label="创建者">{{ item.creator?.show_name }}</el-descriptions-item>
                        <el-descriptions-item label="作者">{{ item.author }}</el-descriptions-item>
                        <el-descriptions-item label="编辑">{{ item.editor }}</el-descriptions-item>
                        <el-descriptions-item label="来源">{{ item.source }}</el-descriptions-item>
                        <el-descriptions-item label="显示排序">{{ item.asc_num }} <span class="tips">从小到大显示</span></el-descriptions-item>

                        <el-descriptions-item label="状态">
                            <el-tag size="small" effect="plain" :type="item.status === 'published' ? 'success' : 'warning'" round>
                                {{ item.status === 'published' ? '发布' : '草稿' }}
                            </el-tag>
                        </el-descriptions-item>

                        <el-descriptions-item label="分类">
                            <el-tag class="mr-2" v-for="cat in item.categories" :key="cat.id">{{ cat.title }}</el-tag>
                        </el-descriptions-item>

                        <el-descriptions-item label="标签">
                            <el-tag class="mr-2" v-for="tag in item.tags" :key="tag.id">{{ tag.title }}</el-tag>
                        </el-descriptions-item>

                        <el-descriptions-item label="相册">
                            <el-image
                                class="w-24 h-24 mr-2"
                                fit="cover"
                                :preview-src-list="item.album"
                                :src="img"
                                :key="img"
                                v-for="(img, index) in item.album"
                                :initial-index="index"
                                :hide-on-click-modal="true"
                            />
                        </el-descriptions-item>
                        <el-descriptions-item label="创建时间">{{ utcDateTimeToLocal(item.created_at) }}</el-descriptions-item>
                        <el-descriptions-item label="更新时间">{{ utcDateTimeToLocal(item.updated_at) }}</el-descriptions-item>
                        <el-descriptions-item label="删除时间">{{ item.deleted_at ? utcDateTimeToLocal(item.deleted_at) : '-' }}</el-descriptions-item>
                    </el-descriptions>
                </div>

                <el-descriptions class="mt-6" :column="1" title="文章内容" label-align="right" label-size="small" border>
                    <el-descriptions-item label="简介">{{ item.brief ?? '-' }}</el-descriptions-item>
                    <el-descriptions-item label="详情">
                        <div class="detail-preview" v-html="item.detail"></div>
                    </el-descriptions-item>
                </el-descriptions>

            </div>
            <template #footer>
                <el-button type="primary" @click="() => toEdit($parent.$refs.formRef, item.id)">编辑</el-button>
            </template>
        </el-drawer>
    </el-form>
</template>

<script setup>
import { api } from '../../stores/article';
import { ShowActions } from '../../lib';
import { utcDateTimeToLocal } from '../../lib/util';

const actions = new ShowActions(api);
const {
    item,
    is,
    show,
    toEdit
} = actions;

defineExpose({
    show
});
</script>

<style lang="scss">
.show-permissions .el-tree-node__content {
    display: none;
}

.show-permissions .is-indeterminate > .el-tree-node__content,
.show-permissions .is-checked .el-tree-node__content {
    display: flex;
}
</style>
