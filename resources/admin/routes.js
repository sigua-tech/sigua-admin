import Dashboard from './pages/Dashboard.vue';
import Forbidden from './pages/Forbidden.vue';
import NotFound from './pages/NotFound.vue';
import Todo from './pages/Todo.vue';
import Login from './pages/account/Login.vue';

// 非懒加载 vue 文件组件需先 import 出组件变量，再设置到路由表，否则 build 后出现不显示的问题。（vue-router bug？）
// auth: authorization，授权 id
const routes = [
    {
        path: '/',
        component: Dashboard,
        meta: {
            title: '管理概况'
        }
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/todo',
        component: Todo,
        meta: {
            title: 'TODO'
        }
    },
    {
        path: '/notfound',
        component: NotFound,
        meta: {
            title: '404 | 页面未找到'
        }
    },
    {
        path: '/forbidden',
        component: Forbidden,
        meta: {
            title: '403 | 无权限'
        }
    },

    // staff
    {
        path: '/staffs',
        component: () => import('./pages/staff/Index.vue'),
        meta: {
            title: '人员管理',
            auth: 'staff.viewAll'
        }
    },
    {
        path: '/staffs/roles',
        component: () => import('./pages/staff/role/Index.vue'),
        meta: {
            parent: '/staffs',
            title: '角色管理',
            auth: 'staff.role.viewAll'
        }
    },
    {
        path: '/staffs/departments',
        component: () => import('./pages/staff/department/Index.vue'),
        meta: {
            parent: '/staffs',
            title: '部门管理',
            auth: 'staff.department.viewAll'
        }
    },

    // article
    {
        path: '/articles',
        component: () => import('./pages/article/Index.vue'),
        meta: {
            parent: '',
            title: '文章管理',
            auth: 'article.viewAll'
        }
    },
    {
        path: '/articles/create',
        component: () => import('./pages/article/Form.vue'),
        meta: {
            parent: '/articles',
            title: '新建文章',
            auth: 'article.create'
        }
    },
    {
        path: '/articles/:id/edit',
        props: true,
        component: () => import('./pages/article/Form.vue'),
        meta: {
            parent: '/articles',
            title: '编辑文章',
            auth: 'article.category.update'
        }
    },
    {
        path: '/articles/categories',
        component: () => import('./pages/article/category/Index.vue'),
        meta: {
            parent: '/articles',
            title: '分类管理',
            auth: 'article.category.viewAll'
        }
    },
    {
        path: '/articles/tags',
        component: () => import('./pages/article/tag/Index.vue'),
        meta: {
            parent: '/articles',
            title: '分类管理',
            auth: 'article.tag.viewAll'
        }
    },

    // goods
    {
        path: '/goods',
        component: () => import('./pages/goods/Index.vue'),
        meta: {
            title: '商品管理',
            auth: 'goods.viewAll'
        }
    },
    {
        path: '/goods/categories',
        component: () => import('./pages/goods/category/Index.vue'),
        meta: {
            parent: '/goods',
            title: '分类管理',
            auth: 'goods.category.viewAll'
        }
    },
    {
        path: '/goods/tags',
        component: () => import('./pages/goods/tag/Index.vue'),
        meta: {
            parent: '/goods',
            title: '标签管理',
            auth: 'goods.tag.viewAll'
        }
    },
    {
        path: '/goods/create',
        component: () => import('./pages/goods/Form.vue'),
        meta: {
            parent: '/goods',
            title: '新建商品',
            auth: 'goods.category.create'
        }
    },
    {
        path: '/goods/:id/edit',
        props: true,
        component: () => import('./pages/goods/Form.vue'),
        meta: {
            parent: '/goods',
            title: '编辑商品',
            auth: 'goods.category.update'
        }
    },
    {
        path: '/settings/:group',
        props: true,
        component: () => import('./pages/setting/IndexSetting.vue'),
        meta: {
            title: '设置',
            auth: 'system.setting.update'
        }
    }
];

export default routes;
