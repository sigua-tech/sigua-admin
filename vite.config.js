import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {visualizer} from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        visualizer({ open: true }), // 分析依赖几依赖包大小
        vue(),
        laravel({
            input: [
                // 'resources/css/app.css',
                // 'resources/js/app.js'
                'resources/admin/app.js'
            ],
            refresh: [
                'resources/views/**',
                'resources/admin/views/**'
            ]
        })
    ],
    css: {
        devSourcemap: true
    },
    resolve: {
        alias: {
            // '~/': 'resources/admin/'
        }
    },
    build: {
        sourcemap: false,
        chunkSizeWarningLimit: 800, // K
        rollupOptions: {
            // input: {
            //     admin: 'admin.html'
            // },
            output: {
                manualChunks: {
                    'admin.vendor': ['axios', 'vue', 'vue-router', 'vuedraggable'],
                    'admin.vendor.ep': ['element-plus', '@element-plus/icons-vue'],
                    'admin.vendor.mce1': ['tinymce', '@tinymce/tinymce-vue', 'tinymce/icons/default', 'tinymce/models/dom'],
                    'admin.vendor.mce2': [
                        'tinymce/themes/silver',
                        'tinymce/plugins/code',
                        'tinymce/plugins/image',
                        'tinymce/plugins/media',
                        'tinymce/plugins/link',
                        'tinymce/plugins/preview',
                        'tinymce/plugins/template',
                        'tinymce/plugins/table',
                        'tinymce/plugins/pagebreak',
                        'tinymce/plugins/lists',
                        'tinymce/plugins/advlist',
                        'tinymce/plugins/quickbars',
                        'tinymce/plugins/code/plugin.js',
                        'tinymce/plugins/image/plugin.js',
                        'tinymce/plugins/media/plugin.js',
                        'tinymce/plugins/link/plugin.js',
                        'tinymce/plugins/preview/plugin.js',
                        'tinymce/plugins/template/plugin.js',
                        'tinymce/plugins/table/plugin.js',
                        'tinymce/plugins/pagebreak/plugin.js',
                        'tinymce/plugins/lists/plugin.js',
                        'tinymce/plugins/advlist/plugin.js',
                        'tinymce/plugins/quickbars/plugin.js',
                    ], // tinymce 全打成一个包文件太大，分2个包

                    'admin.app': [
                        // 入口
                        'resources/admin/app.js',
                        'resources/admin/pages/App.vue',

                        // options
                        'resources/admin/routes.js',
                        'resources/admin/menu.js',

                        // 公共库
                        'resources/admin/lib/message.js',
                        'resources/admin/lib/util.js',
                        'resources/admin/lib/http.js',
                        'resources/admin/lib/uploader.js',
                        'resources/admin/lib/index.js',
                        'resources/admin/stores/auth.js',
                        'resources/admin/stores/staff.js',

                        // 顶级绑定公共组件
                        'resources/admin/components/Pagination.vue',
                        'resources/admin/components/PopconfirmDelete.vue',

                        // 图片上传
                        'resources/admin/components/UploadAlbum.vue',
                        'resources/admin/components/UploadImage.vue',

                        // 基础页面
                        'resources/admin/pages/Dashboard.vue',
                        'resources/admin/pages/NotFound.vue',
                        'resources/admin/pages/App.vue',
                        // ],
                        // 'admin.staff': [
                        'resources/admin/pages/staff/Index.vue',
                        'resources/admin/pages/staff/role/Index.vue',
                        'resources/admin/pages/staff/department/Index.vue',
                        // ],
                        // 'admin.article': [
                        'resources/admin/pages/article/Index.vue',
                        'resources/admin/pages/article/Form.vue',
                        'resources/admin/pages/article/ShowDrawer.vue',
                        'resources/admin/pages/article/category/Index.vue',
                        'resources/admin/pages/article/category/FormDialog.vue',
                        'resources/admin/pages/article/category/ShowDrawer.vue',
                        'resources/admin/pages/article/tag/Index.vue',
                        'resources/admin/pages/article/tag/FormDialog.vue',
                        // ],
                        // 'admin.goods': [
                        'resources/admin/pages/goods/Index.vue',
                        'resources/admin/pages/goods/Form.vue',
                        'resources/admin/pages/goods/FormSku.vue',
                        'resources/admin/pages/goods/ShowDrawer.vue',
                        'resources/admin/pages/goods/category/Index.vue',
                        'resources/admin/pages/goods/category/FormDialog.vue',
                        'resources/admin/pages/goods/category/ShowDrawer.vue',
                        'resources/admin/pages/goods/tag/Index.vue',
                        'resources/admin/pages/goods/tag/FormDialog.vue',
                        // setting
                        'resources/admin/pages/setting/IndexSetting.vue',
                    ]
                }
            }
        }
    }
});
