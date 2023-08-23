import { ElMessage, ElMessageBox } from 'element-plus';

/**
 * @param {string} message
 * @param {string} type success/warning/info/error
 */
export const show = (message, type = 'info') => {
    ElMessage({
        type,
        message,
        customClass: 'app-message',
        dangerouslyUseHTMLString: true,
        offset: 100,
        duration: 5000
    });
};

export const success = message => {
    show(message, 'success');
};

export const alert = (message, title = null, options = {}) => {
    ElMessageBox.alert(message, title, options);
};

export const error = (message, title) => {
    alert(message, title, { type: 'error' });
};

export const toast = message => {
    ElMessage({
        message,
        customClass: 'app-toast',
        center: true,
        offset: 100,
        duration: 3000
    });
};

export const responseMessage = resp => {
    const errors = resp.errors;
    let message = resp.message;

    if (errors && errors.constructor.name === 'Object') {
        message = '<ul>';
        for (const errKey in errors) {
            for (const childKey in errors[errKey]) {
                message += '<li>' + errors[errKey][childKey] + '</li>';
            }
        }
        message += '</ul>';
    }

    if (!message) {
        if (resp.success) {
            message = '操作成功';
        } else {
            message = '开小差了';
        }
    }

    show(message, resp.success ? 'success' : 'error');
};

export const deleteMessage = resp => {
    if (!resp.success) {
        return responseMessage(resp);
    }
    success('删除成功');
};

export const confirmSaveSuccess = (message, cancelButtonText) => {
    return ElMessageBox.confirm(message, {
        cancelButtonText,
        confirmButtonText: '返回列表',
        type: 'success'
    });
};

export const prompt = ({
    title,
    tip,
    inputPattern,
    inputErrorMessage
}) => {
    return ElMessageBox.prompt(tip, title, {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        inputPattern,
        inputErrorMessage
    });
};

export default {
    show,
    toast,
    success,
    error,
    alert,
    responseMessage,
    deleteMessage,
    confirmSaveSuccess,
    prompt
};
