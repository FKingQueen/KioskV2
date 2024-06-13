<template>
    <div class="w-full h-screen bg-white">
        <!-- Side Bar -->
        <div class="w-full min-h-11 max-h-11 bg-white">
            <div class="flex items-center min-h-11 max-h-11 h-full border">
                <Icon @click="showSideBar = true" type="md-menu" :size="30" class="pl-2" />
            </div>
        </div>
        <!-- <Drawer placement="left" :closable="false" v-model="sideBar" class="p-0">
            <div class="border w-full">
                sample
            </div>
        </Drawer> -->
        <Drawer placement="left" :closable="false" v-model="showSideBar" :styles="{ padding: '0' }">
            <!--  -->
            <div class="text-center border py-4">
                <p class="font-bold">PORTABLE KIOSK</p>
            </div>
            <div>
                <!-- Default -->
                    <div v-if="!this.isLogged_in" class="w-full min-h-9 flex items-center">
                        <router-link to="/user/dashboard" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                            <p>Dashboard</p>
                        </router-link>
                    </div>
                <!-- /Default -->

                <!-- Cashier -->
                <div v-if="isCashierVisible" class="w-full min-h-9 flex items-center">
                    <router-link to="/user/cashierPreOrder" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                        <p>Pre-Order</p>
                    </router-link>
                </div>
                <!-- Cashier -->

                <!-- Consumer -->
                <div v-if="isConsumerVisible" class="w-full min-h-9 flex items-center">
                    <router-link to="/user/preOrder" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                        <p>Pre-Order</p>
                    </router-link>
                </div>
                <div v-if="isConsumerVisible" class="w-full min-h-9 flex items-center">
                    <router-link to="/user/cart" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                        <p>My Cart</p>
                    </router-link>
                </div>
                <div v-if="isConsumerVisible" class="w-full min-h-9 flex items-center">
                    <router-link to="/user/purchaseHistory" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                        <p>History</p>
                    </router-link>
                </div>
                <!-- /Consumer -->

                <!-- Logged in -->

                <div v-if="!this.isLogged_in" class="w-full min-h-9 flex items-center">
                    <button class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start" v-if="!this.isLogged_in" @click="logout()">
                        Logout
                    </button>
                </div>
                <!-- /Logged in -->

                <!-- Logged out -->
                <div v-if="this.isLogged_in" class="w-full min-h-9 flex items-center">
                    <router-link to="/" class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start">
                      <span>Dashboard</span>
                  </router-link>
                </div>
                <div v-if="this.isLogged_in" class="w-full min-h-9 flex items-center">
                    <button class="w-full text-black min-h-9 text-start pl-3 flex items-center justify-start" @click="this.$router.push({ path: '/login' })">
                        Login
                    </button>
                </div>
                <!-- /Logged out -->

            </div>

        </Drawer>
        <!-- /Side Bar -->
        <!-- Content -->
        <div class="p-2 bg-gray-200 h-screen">
            <div>
                <router-view class="bg-white"></router-view>
            </div>
        </div>
        <!-- /Content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            isLogged_in: false,
            sideBar: false,
            showSideBar: false,
            isConsumerVisible: false,
            isCashierVisible: false,
            userRole: '',
        }
    },
    methods: {
        siderBar() {
            const thiss = this;
            thiss.sideBar = true;
        },
        logout() {
            // axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/api/logout')
                    .then(response => {
                        localStorage.removeItem('authToken');
                        localStorage.removeItem('userRole');
                        localStorage.removeItem('product_id');
                        this.$router.push({ path: '/login' })
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            // })
        }
    },
    created() {
        const thiss = this;
        
        if (localStorage.getItem('userRole') == 3) {
            thiss.isConsumerVisible = true;
        }
        if (localStorage.getItem('userRole') == 2) {
            thiss.isCashierVisible = true;
        }
        if (localStorage.getItem('authToken') == null) {
            thiss.isLogged_in = true;
        } else {
            thiss.isLogged_in = false;
        }
    }
}
</script>
<style scoped>
.router-link-exact-active {
    background-color: rgb(229 231 235);
    border-left: 2px solid rgb(32, 127, 40);
    color: rgb(41, 39, 39);
}

.router-link-active {
    background-color: rgb(229 231 235);
    border-left: 2px solid rgb(32, 127, 40);
    color: rgb(41, 39, 39);
}
</style>