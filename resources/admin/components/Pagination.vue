<template>
    <el-pagination
        background
        class="mt-4"
        layout="prev, pager, next, ->, total"
        @current-change="(page) => bindings.reload(page)"
        :current-page="bindings.listData.current_page"
        :total="bindings.listData.total"
        :pager-count="9"
        :page-size="bindings.listData.per_page"
        v-if="bindings.listData.total > 0 && bindings.listData.per_page < 200"
    ></el-pagination>
</template>

<script setup>
defineProps({
    bindings: Object
});
</script>
