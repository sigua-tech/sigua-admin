import { reactive } from 'vue';

export const userInfo = reactive({
    id: 0,
    name: '',
    realname: '',
    showName: '',
    avatar: '',
    email: '',
    mobile: '',
    enabled: true,
    permissions: []
});

// 在 VueRouter 初始化之前初始化用户信息
export const initUserInfo = (userInfo) => {
    setUserInfo(userInfo ?? {}); // 在 blade 模板中动态设置变量 userInfo
    if (!isLogined()) {
        showLogin(); // 如果未登录，在 VueRouter 初始化之前修改 hash
    }
};

export const setUserInfo = info => {
    Object.assign(userInfo, info);
    userInfo.showName = (info.realname ? info.realname : info.name) || '';
};

export const useCan = name => {
    if (!userInfo.enabled) {
        return false;
    }
    const permissions = userInfo.permissions || []; // 用户权限列表
    if (permissions[0] === '*') {
        return true;
    }
    return permissions.includes(name);
};

/**
 * @param {string} prefix
 * @param {array} actions
 * @return {object}
 */
export const useCans = (prefix, actions) => {
    const cans = {};
    actions.forEach(action => {
        cans[action] = useCan(prefix + '.' + action);
    });
    return cans;
};

export const showLogin = () => {
    const path = window.location.hash.substring(1) || '/';
    if (path.startsWith('/login')) {
        // 已经是登录页面不跳转
        return;
    }
    window.location.hash = '#/login?forward=' + encodeURIComponent(path);
};

export const isLogined = () => {
    return userInfo.id > 0;
};
