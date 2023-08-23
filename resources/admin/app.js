import { createApp } from 'vue';
import { createRouter, createWebHashHistory } from 'vue-router';
import { initUserInfo } from './lib/auth';
import routes from './routes.js';
import App from './pages/App.vue';
import Pagination from './components/Pagination.vue';
import PopconfirmDelete from './components/PopconfirmDelete.vue';
import ElementPlus from 'element-plus';
import zhCn from 'element-plus/dist/locale/zh-cn.mjs';
import './scss/app.scss';
// import { createPinia } from 'pinia';

// VueRouter 初始化前初始化用户信息
// 在 blade 模板中动态设置变量 window.userInfo
initUserInfo(window.userInfo);

const router = createRouter({
    history: createWebHashHistory(),
    routes
});
const app = createApp(App);
app.use(router);
// app.use(createPinia());

app.use(ElementPlus, {
    locale: zhCn
});

// 公用组件
app.component('Pagination', Pagination); // 分页组件
app.component('PopconfirmDelete', PopconfirmDelete); // 弹出提示确认删除

app.mount('#app');

window.app = app;
