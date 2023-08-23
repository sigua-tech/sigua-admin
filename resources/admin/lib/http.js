import axios from 'axios';
import { isProxy, toRaw } from 'vue';
import { error as showError, responseMessage } from './message';
import { loading } from './util';
import { showLogin } from './auth';

export const http = {
    request: function (options) {
        if (typeof options.data === 'undefined') {
            options.data = {};
        }

        // 数据为代理对象的处理
        if (isProxy(options.data)) {
            options.data = toRaw(options.data);
        }

        options.url = urlPrefix(options.url);

        return ajax(options);
    },
    get: function (url, params = {}) {
        return this.request({
            method: 'GET',
            url,
            params
        });
    },
    post: function (url, data) {
        return this.request({
            method: 'POST',
            url,
            data
        });
    },
    option: function (url, params = {}) {
        return this.request({
            method: 'OPTION',
            url,
            params
        });
    },
    put: function (url, data) {
        return this.request({
            method: 'PUT',
            url,
            data
        });
    },
    patch: function (url, data) {
        return this.request({
            method: 'PATCH',
            url,
            data
        });
    },
    delete: function (url, data = {}) {
        return this.request({
            method: 'DELETE',
            url,
            data
        });
    }
};

/**
 * @param {object} options
 * @return {Promise<unknown>}
 */
const ajax = (options = {}) => {
    return axios(options)
        .then((response) => {
            return response.data;
        })
        .catch(error => errorHandler(error, options))
        .finally(() => {
            loading.hide();
        });
};

/**
 * @param {string} path
 */
const urlPrefix = path => {
    const baseUrl = import.meta.env.VITE_ADMIN_BASE_URL || '/admin';
    if (path.startsWith(baseUrl)) {
        return path;
    }
    if (path.startsWith('/')) {
        return baseUrl + path;
    }
    return baseUrl + '/' + path;
};

// 异常处理应该以 「抛出错误」 来退出函数，而不是用用 return，否则 `catch()` 之后的 `then()` 链还会执行。
const errorHandler = (error, options) => {
    const respJar = error.response || {};
    const status = respJar.status;
    const resp = respJar.data || {};

    if (status === 401) {
        showLogin();
        throw error;
    }

    if (status === 419) {
        // CSRF token 过期，已随请求返回新 token，重新提交即可
        if (resp.token_cookie_sent) {
            return ajax(options);
        }
        showError('状态已过期，请刷新页面后再执行该操作');
        throw error;
    }

    if (!resp.message) {
        const messages = {
            403: '无权限访问该资源',
            404: '请求资源未找到',
            429: '你请求太频繁了，情稍等一会再来', // 访问限流
            500: '服务器异常',
        };
        resp.message = messages[status] || '未知错误';
    }

    resp.success = false;
    responseMessage(resp);

    throw error;
};
