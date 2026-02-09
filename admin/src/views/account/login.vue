<template>
    <div class="login-container">
        <div class="login-background"></div>
        <div class="login-content">
            <div class="login-wrapper">
                <div class="login-left hidden md:flex">
                    <div class="left-content">
                        <div class="brand-title">
                            <h1>后台管理系统</h1>
                            <p>高效 · 安全 · 智能</p>
                        </div>
                    </div>
                </div>
                <div class="login-right">
                    <div class="login-form-container">
                        <div class="form-header">
                            <div class="logo-icon">
                                <icon name="el-icon-Setting" size="32" />
                            </div>
                            <h2>欢迎登录</h2>
                            <p>后台管理系统</p>
                        </div>
                        <el-form ref="formRef" :model="formData" size="large" :rules="rules" class="login-form">
                            <el-form-item prop="account">
                                <div class="input-wrapper">
                                    <div class="input-icon">
                                        <icon name="el-icon-User" size="18" />
                                    </div>
                                    <el-input v-model="formData.account" placeholder="请输入账号" class="custom-input"
                                        @keyup.enter="handleEnter" />
                                </div>
                            </el-form-item>
                            <el-form-item prop="password">
                                <div class="input-wrapper">
                                    <div class="input-icon">
                                        <icon name="el-icon-Lock" size="18" />
                                    </div>
                                    <el-input ref="passwordRef" v-model="formData.password" show-password
                                        placeholder="请输入密码" class="custom-input" @keyup.enter="handleLogin" />
                                </div>
                            </el-form-item>
                        </el-form>
                        <div class="form-options hidden" style="display: none;">
                            <el-checkbox v-model="remAccount" class="custom-checkbox">记住账号</el-checkbox>
                        </div>
                        <el-button type="primary" size="large" class="login-button" :loading="isLock"
                            @click="lockLogin">
                            {{ isLock ? '登录中...' : '立即登录' }}
                        </el-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-footer">
            <layout-footer />
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { FormInstance, InputInstance } from 'element-plus'
import { computed, onMounted, reactive, ref, shallowRef } from 'vue'

import { ACCOUNT_KEY } from '@/enums/cacheEnums'
import { PageEnum } from '@/enums/pageEnum'
import { useLockFn } from '@/hooks/useLockFn'
import LayoutFooter from '@/layout/components/footer.vue'
import useAppStore from '@/stores/modules/app'
import useUserStore from '@/stores/modules/user'
import cache from '@/utils/cache'

const passwordRef = shallowRef<InputInstance>()
const formRef = shallowRef<FormInstance>()
const appStore = useAppStore()
const userStore = useUserStore()
const route = useRoute()
const router = useRouter()
const remAccount = ref(true)
const config = computed(() => appStore.config)
const formData = reactive({
    account: '',
    password: ''
})
const rules = {
    account: [
        {
            required: true,
            message: '请输入账号',
            trigger: ['blur']
        }
    ],
    password: [
        {
            required: true,
            message: '请输入密码',
            trigger: ['blur']
        }
    ]
}
// 回车按键监听
const handleEnter = () => {
    if (!formData.password) {
        return passwordRef.value?.focus()
    }
    handleLogin()
}
// 登录处理
const handleLogin = async () => {
    await formRef.value?.validate()
    // 记住账号，缓存
    cache.set(ACCOUNT_KEY, {
        remember: remAccount.value,
        account: remAccount.value ? formData.account : ''
    })
    await userStore.login(formData)
    const {
        query: { redirect }
    } = route
    const path = typeof redirect === 'string' ? redirect : PageEnum.INDEX
    router.push(path)
}
const { isLock, lockFn: lockLogin } = useLockFn(handleLogin)

onMounted(() => {
    const value = cache.get(ACCOUNT_KEY)
    if (value?.remember) {
        remAccount.value = value.remember
        formData.account = value.account
    }
})
</script>

<style lang="scss" scoped>
.login-container {
    position: relative;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    overflow: hidden;
}

.login-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('@/assets/images/loginbg.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(8px);
    transform: scale(1.05);
    animation: backgroundShift 15s ease-in-out infinite alternate;
}

@keyframes backgroundShift {
    0% {
        transform: scale(1);
    }

    100% {
        transform: scale(1.1);
    }
}

.login-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

.login-wrapper {
    display: flex;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow:
        0 25px 50px -12px rgba(0, 0, 0, 0.25),
        0 0 0 1px rgba(255, 255, 255, 0.2);
    overflow: hidden;
    max-width: 1000px;
    width: 100%;
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-left {
    flex: 1;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    padding: 3rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.login-left::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }

    to {
        transform: rotate(360deg);
    }
}

.left-content {
    position: relative;
    z-index: 1;
    width: 100%;
    color: white;
    text-align: center;
}

.brand-title {
    margin-bottom: 3rem;
}

.brand-title h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.brand-title p {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 300;
    letter-spacing: 4px;
}

.login-right {
    flex: 0 0 420px;
    padding: 3rem;
    display: flex;
    align-items: center;
}

.login-form-container {
    width: 100%;
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.logo-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 50%;
    margin-bottom: 1.5rem;
    color: white;
    box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
}

.form-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.form-header p {
    color: #64748b;
    font-size: 0.95rem;
}

.login-form {
    :deep(.el-form-item) {
        margin-bottom: 1.5rem;
    }
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    transition: all 0.3s ease;
}

.input-wrapper:focus-within {
    transform: translateY(-2px);
}

.input-icon {
    position: absolute;
    left: 1rem;
    z-index: 1;
    color: #94a3b8;
    transition: color 0.3s ease;
}

.input-wrapper:focus-within .input-icon {
    color: #6366f1;
}

.custom-input {
    width: 100%;

    :deep(.el-input__wrapper) {
        padding-left: 3rem;
        padding-right: 1rem;
        border-radius: 12px;
        box-shadow: none;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }

    :deep(.el-input__wrapper:hover) {
        border-color: #cbd5e1;
    }

    :deep(.el-input__wrapper.is-focus) {
        border-color: #6366f1;
        box-shadow: none;
    }

    :deep(.el-input__inner) {
        font-size: 0.95rem;
        color: #1e293b;
    }

    :deep(.el-input__inner::placeholder) {
        color: #94a3b8;
    }
}

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.custom-checkbox {
    :deep(.el-checkbox__label) {
        color: #64748b;
        font-size: 0.9rem;
    }

    :deep(.el-checkbox__input.is-checked .el-checkbox__inner) {
        background-color: #6366f1;
        border-color: #6366f1;
    }
}

.login-button {
    width: 100%;
    height: 48px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 12px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

.login-button:active {
    transform: translateY(0);
}

.login-footer {
    position: relative;
    z-index: 1;
    padding: 1.5rem;
    text-align: center;
}

@media (max-width: 768px) {
    .login-right {
        flex: 0 0 auto;
        width: 100%;
        padding: 2rem;
    }

    .form-header h2 {
        font-size: 1.5rem;
    }

    .brand-title h1 {
        font-size: 2rem;
    }
}
</style>
