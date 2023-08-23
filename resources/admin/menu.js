const menu = [
    // overview
    {
        title: '首页',
        path: '/',
        icon: 'dashboard',
        desc: '后台首页',
        children: [
            { title: '控制台', path: '/' }
        ]
    },

    // article
    {
        title: '文章',
        path: '/articles',
        icon: 'news',
        desc: '文章管理',
        module: 'article',
        children: [
            { title: '文章', path: '/articles' },
            { title: '分类', path: '/articles/categories' },
            { title: '标签', path: '/articles/tags' }
        ]
    },

    // goods
    {
        title: '商品',
        path: '/goods',
        icon: 'goods',
        desc: '商品管理',
        module: 'goods',
        children: [
            { title: '商品', path: '/goods' },
            { title: '分类', path: '/goods/categories' },
            { title: '标签', path: '/goods/tags' }
        ]
    },
    // todo
    {
        title: 'Todo',
        path: '/todo',
        icon: 'stats',
        desc: '数据管理',
        module: 'stats',
        children: [
            { title: 'Todo1', path: '/todo' },
            { title: 'Todo2', path: '/todo' }
        ]
    },

    // staffs
    {
        title: '人员',
        path: '/staffs',
        icon: 'user',
        desc: '系统设置',
        module: 'staff',
        children: [
            {
                title: '人员管理',
                path: '/staffs'
            },
            {
                title: '角色管理',
                path: '/staffs/roles'
            },
            {
                title: '部门管理',
                path: '/staffs/departments'
            }
            // { title: '授权条目', path: '/staffs/permissions' }
        ]
    },

    // setting
    {
        title: '设置',
        path: '/settings/system',
        icon: 'setting',
        desc: '系统设置',
        module: 'setting',
        children: [
            {
                title: '系统设置',
                path: '/settings/system'
            },
            {
                title: '联系信息',
                path: '/settings/contact'
            },
            {
                title: '短信设置',
                path: '/settings/sms'
            }
        ]
    }
];

let activeMap;
const getMenuActiveMap = () => {
    if (activeMap) {
        return activeMap;
    }

    const paths = {};
    const modules = {};
    for (const idx0 in menu) {
        const children = menu[idx0].children;
        if (menu[idx0].module) {
            modules[menu[idx0].module] = parseInt(idx0);
        }
        for (const idx1 in children) {
            const item = children[idx1];
            paths[item.path] = [parseInt(idx0), parseInt(idx1)];
        }
    }
    activeMap = { paths, modules };
    return activeMap;
};

const getMenuActive = path => {
    const menuActiveMap = getMenuActiveMap();
    const active = menuActiveMap.paths[path];

    if (active) {
        return active;
    }

    if (path.match(/^\/[\w-]+$/)) {
        return [-1, -1];
    }

    // 二级菜单子页面
    const pathNodes = path.replace(/^\/([\w-/]+)(\/\d+)?(\/.*)/, '$1').split('/');
    let childPageIndex = '';
    for (let i = pathNodes.length; i > 0; i--) {
        childPageIndex = menuActiveMap.paths['/' + pathNodes.join('/')];
        if (childPageIndex) {
            return childPageIndex;
        }
        pathNodes.pop();
    }

    // 模块子页面
    const module = path.replace(/^\/([\w-]+).*/, '$1');
    const moduleIndex = menuActiveMap.modules[module];
    if (moduleIndex) {
        return [moduleIndex, -1];
    }

    return [-1, -1];
};

export {
    menu,
    getMenuActiveMap,
    getMenuActive
};
