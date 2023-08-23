<template>
    <div class="auth-wrapper">
        <div class="top">
            <a href="https://sigua.tech/"><img height="34" src="../../assets/login-logo.png"/></a>
            <h1>丝瓜管理登录</h1>
        </div>
        <el-form
            size="large"
            label-position="top"
            class="auth-form"
            :rules="formRules"
            ref="formRef"
            :model="inputs"
            @submit.prevent="submitForm($refs.formRef)"
            v-cloak
        >
            <el-form-item prop="account">
                <el-input ref="accountRef" v-model="inputs.account" placeholder="请输入用户名/手机号/邮箱">
                    <template #prepend>
                        <i class="iconfont icon-user-fill"/>
                    </template>
                </el-input>
            </el-form-item>

            <el-form-item prop="password">
                <el-input v-model="inputs.password" type="password" autocomplete="off" placeholder="请输入密码">
                    <template #prepend>
                        <i class="iconfont icon-password"/>
                    </template>
                </el-input>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" class="submit" native-type="submit" v-loading="isSubmitting">
                    登录
                </el-button>
            </el-form-item>
        </el-form>

        <Copyright/>
    </div>
</template>

<script setup>
import { onActivated, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { http } from '../../lib';
import { responseMessage, success } from '../../lib/message';
import { setUserInfo, userInfo } from '../../lib/auth';
import Copyright from '../../components/Copyright.vue';

const inputs = reactive({
    account: '',
    password: ''
});
const formRules = reactive({
    account: [
        {
            required: true,
            message: '请输入账号',
            trigger: ['blur', 'change']
        }
    ],
    password: [
        {
            required: true,
            message: '请输入密码',
            trigger: ['blur', 'change']
        },
        {
            min: 6,
            max: 20,
            message: '密码长度为 6~20 位',
            trigger: ['blur', 'change']
        }
    ]
});

const router = useRouter();
const forward = useRoute().query.forward || '/';
const accountRef = ref();

onActivated(() => {
    accountRef.value.focus();
    if (userInfo.id) {
        // 已登录则跳转
        router.replace(forward);
    }
});

const isSubmitting = ref(false);
const submitForm = formEl => {
    formEl.validate().then(() => {
        isSubmitting.value = true;
        http.post('/admin/auth/login', inputs).then((resp) => {
            if (!resp.success) {
                responseMessage(resp);
                return;
            }
            setUserInfo(resp.userInfo); // 同步人员信息
            success('登录成功');
            router.replace(forward);
        }).finally(() => {
            isSubmitting.value = false;
        });
    });
};
</script>
