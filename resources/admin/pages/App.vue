<template>
    <el-container class="h-full" v-cloak>
        <div class="app-top-ops">
            <el-dropdown>
                <div class="staff-action">
                    <span class="avatar">
                        <img :src="userInfo.avatar" alt="" v-if="userInfo.avatar">
                        <span v-else>{{ userInfo.showName[0] || '' }}</span>
                    </span>
                    {{ userInfo.showName }}
                    <el-icon class="align-middle">
                        <ArrowDown/>
                    </el-icon>
                </div>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item @click="$refs.chProfileRef.show()">
                            <el-icon>
                                <User/>
                            </el-icon>
                            账号设置
                        </el-dropdown-item>
                        <el-dropdown-item @click="$refs.chpwRef.show()">
                            <el-icon>
                                <Unlock/>
                            </el-icon>
                            修改密码
                        </el-dropdown-item>
                        <el-dropdown-item @click="logout()">
                            <el-icon>
                                <SwitchButton/>
                            </el-icon>
                            退出登录
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </div>
        <el-aside class="app-side">
            <h1 title="丝瓜管理后台">
                <router-link to="/">
                    <img class="logo" src="../assets/logo.svg" alt="丝瓜管理后台">
                </router-link>
            </h1>

            <ul class="menu" v-cloak>
                <li :class="idx0 === navActive[0] ? 'active' : ''" v-for="(navItem, idx0) in menu"
                    :key="navItem.path">
                    <router-link :to="navItem.path">
                        <i :class="'iconfont icon-' + navItem.icon + (idx0 === navActive[0] ? '-fill' : '')"></i>
                        <span>{{ navItem.title }}</span>
                    </router-link>
                    <dl class="menu-2">
                        <dt>{{ navItem.desc ? navItem.desc : navItem.title }}</dt>
                        <dd
                            :class="(idx0 === navActive[0] && idx1 === navActive[1]) ? 'active' : ''"
                            v-for="(subNavItem, idx1) in navItem.children"
                            :key="subNavItem.path"
                        >
                            <router-link :to="subNavItem.path"><span>{{ subNavItem.title }}</span></router-link>
                        </dd>
                    </dl>
                </li>
            </ul>
            <div class="menu-2-holder"></div>
        </el-aside>
        <el-container class="main-wrapper h-full">
            <el-header class="app-header">
                <el-breadcrumb class="breadcrumb">
                    <el-breadcrumb-item v-for="breadcrumb in breadcrumbs" :key="breadcrumb.title">
                        <router-link :to="breadcrumb.path" v-if="breadcrumb.path">
                            {{ breadcrumb.title }}
                        </router-link>
                        <span v-else>{{ breadcrumb.title }}</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
            </el-header>
            <el-main class="app-main">
                <router-view v-slot="{ Component }">
                    <keep-alive>
                        <component :is="Component"/>
                    </keep-alive>
                </router-view>

                <Copyright/>
            </el-main>
        </el-container>
    </el-container>
    <ChangePasswordDialog ref="chpwRef"/>
    <ChangeProfileDialog ref="chProfileRef"/>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { getMenuActive, menu } from '../menu';
import { useCan, userInfo, isLogined, showLogin } from '../lib/auth';
import { http } from '../lib';
import { success } from '../lib/message';
import { loading } from '../lib/util';
import { ArrowDown, SwitchButton, Unlock, User } from '@element-plus/icons-vue';
import ChangePasswordDialog from './account/ChangePasswordDialog.vue';
import ChangeProfileDialog from './account/ChangeProfileDialog.vue';
import Copyright from '../components/Copyright.vue';

const chpwRef = ref(); // 修改账号对话框
const navActive = ref([0, 0]); // 菜单选中状态
const breadcrumbs = ref([]);

const router = useRouter();

// 路由执行之前
router.beforeEach((to, from, next) => {
    if (to.matched.length === 0) {
        // 页面不存在
        router.replace('/notfound');
        return;
    }
    // 前往页面是登录页
    if (to.path === '/login') {
        // 已登录，则跳转
        if (isLogined()) {
            const forward = to.query.forward || '/';
            router.replace(forward);
            return;
        }
        next();
        return;
    }
    // 登录状态检查
    if (!isLogined()) {
        showLogin();
        return;
    }
    // 检查访问权限
    if (to.meta.auth && !useCan(to.meta.auth)) {
        router.replace('/forbidden');
        return;
    }
    next();
});

// 路由执行完成后（包括首次加载、路由错误等）
router.afterEach((to, from, failure) => {
    if (to.matched.length === 0) {
        // 页面不存在
        return;
    }
    // 设置跳转后管理菜单选中状态
    navActive.value = getMenuActive(to.path);

    syncBreadcrumbs(to);
});

// 同步面包屑导航
const syncBreadcrumbs = (route) => {
    const crumbs = [];
    if (route.meta.parent) {
        crumbs.push({
            path: route.meta.parent,
            title: router.resolve(route.meta.parent).meta.title
        });
    }
    crumbs.push({
        title: route.meta.title ?? '未知页面'
    });
    breadcrumbs.value = crumbs;
};

const logout = (forward = '') => {
    loading.show({ text: '退出中...' });
    http.post('/admin/auth/logout', {}).then((resp) => {
        success('退出成功');
        window.location.reload();
    });
};
</script>
