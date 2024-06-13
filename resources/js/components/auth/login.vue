<template class="w-full">
    <div class="flex items-center justify-center min-h-screen">

        <a-form :model="formLogin" name="normal_login" class="login-form border p-6 shadow-lg rounded-lg"
            @finish="login()" :validate-messages="validateMessages">
            <h2 class="text-center text-2xl font-bold mb-6">PORTABLE KIOSK</h2>
            <a-form-item label="Email" name="email" :rules="[{ type: 'email' }]">
                <a-input v-model:value="formLogin.email">
                    <template #prefix>
                        <UserOutlined class="site-form-item-icon" />
                    </template>
                </a-input>
            </a-form-item>

            <a-form-item label="Password" name="password" :rules="[{ type: 'string', min: 8 }]">
                <a-input-password v-model:value="formLogin.password">
                    <template #prefix>
                        <LockOutlined class="site-form-item-icon" />
                    </template>
                </a-input-password>
            </a-form-item>

            <a-form-item>
                <a-form-item name="remember" no-style>
                    <a-checkbox v-model:checked="formLogin.remember">Remember me</a-checkbox>
                </a-form-item>
                <a class="login-form-forgot" href="">Forgot password</a>
            </a-form-item>

            <a-form-item>
                <a-button :disabled="disabled" html-type="submit" class="w-full primary login-form-button">
                    Log in
                </a-button>
            </a-form-item>
        </a-form>
    </div>
</template>
<script>
import { defineComponent, reactive, computed } from 'vue';
import { UserOutlined, LockOutlined } from '@ant-design/icons-vue';
// import axios from 'axios';
export default defineComponent({
    components: {
        UserOutlined,
        LockOutlined,
    },
    data() {
        const disabled = computed(() => {
            return !(this.formLogin.email && this.formLogin.password);
        });

        const validateMessages = {
            required: '${label} is required!',
            types: {
                email: '${label} is not a valid email!',
            },
            string: {
                range: '${label} must be between ${min} characters',
            },
        };

        return {
            formLogin: {
                email: '',
                password: '',
            },
            disabled,
            validateMessages
        };
    },
    methods: {
        login() {
            let thiss = this;
            axios.post('api/login', thiss.formLogin)
                .then(response => {
                    localStorage.setItem('authToken', response.data.token);
                    localStorage.setItem('userRole', response.data.user.role_id);
                    if (localStorage.getItem('product_id') == null) {
                        this.$router.push({ path: '/user/dashboard' })
                    } else {
                        let id = localStorage.getItem('product_id')
                        this.$router.push({ name: 'productInfo', params: { id } })
                    } 
                })
                .catch(function (error) {
                    console.error(error);
                });

        }
    }
});
</script>

<style>
#components-form-demo-normal-login .login-form {
    max-width: 300px;
}

#components-form-demo-normal-login .login-form-forgot {
    float: right;
}

#components-form-demo-normal-login .login-form-button {
    width: 100%;
}
</style>