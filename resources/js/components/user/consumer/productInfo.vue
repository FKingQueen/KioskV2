<template>
    <div>
        <div class="w-full h-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem to="/user/dashboard">Dashboard</BreadcrumbItem>
                    <BreadcrumbItem>Product</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class=" w-full p-2">
                <div class="flex justify-center py-10">
                    <img class="cursor-pointer object-contain w-60 h-60" :src="`/img/${product.image}.jpg`">
                </div>
                <div class="space-y-1 ">
                    <p class="text-sm">{{ auxName }}</p>
                    <p class="text-2xl">{{ product.name }}</p>
                    <div class="flex justify-start space-x-1">
                        <p class="text-xs">Available:</p>
                        <p class="text-xs text-green-600">{{ auxNames }}</p>
                    </div>
                    <p class="text-xl"><span>₱</span>{{ product.price }}</p>
                </div>
            </div>
            <div class="w-full flex justify-center space-x-3">
                <Button @click="buy()" icon="logo-usd" size="large"
                    style="background-color: #4CAF50; color: white;">Buy</Button>
                <Button @click="cart()" icon="md-cart" size="large"
                    style="background-color: #FFDF00; color: white;">Add to
                    Cart</Button>
            </div>
            <!-- Place Order Drawer -->
            <Drawer @on-close="exitDrawer()" placement="bottom" :closable="false" v-model="placeOrderDrawer" :styles="{ padding: '0' }"
                :height="370">
                <div class="flex justify-center space-x-2 py-3 items-center">
                    <img class="cursor-pointer object-contain w-20 h-20 " :src="`/img/${product.image}.jpg`">
                    <p class="text-2xl">{{ product.name }}</p>
                </div>
                <div class="space-y-1 px-3">
                    <p class="text-sm ">{{ auxName }}</p>
                    <div class="flex justify-between">
                        <p class="text-sm"><span>Price: ₱</span>{{ product.price }}</p>
                        <p class="text-sm"><span>Quantity: </span>
                            <InputNumber v-model="quantity" size="small" :min="1" :step="1" controls-outside />
                        </p>
                    </div>
                    <div>
                        <p class="text-sm"><span>Total Cost: ₱</span>{{ product.price * quantity }}</p>
                    </div>
                </div>
                <div class="py-3">
                    <div class="w-full border-t flex pt-2 justify-around">
                        <div>
                            <p class="text-sm">Payment Option</p>
                            <div>
                                <a-radio-group v-model:value="paymentOptionValue">
                                    <a-radio :style="{
                                        display: 'flex',
                                        height: '30px',
                                        lineHeight: '30px',
                                    }" :value="1">Pay at the counter</a-radio>
                                    <a-radio :style="{
                                        display: 'flex',
                                        height: '30px',
                                        lineHeight: '30px',
                                    }" :value="2">Card (Visa)</a-radio>
                                    <a-radio :style="{
                                        display: 'flex',
                                        height: '30px',
                                        lineHeight: '30px',
                                    }" :value="3">E-money (Gcash & Maya)</a-radio>
                                </a-radio-group>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm">Order Status</p>
                            <div>
                                <a-radio-group v-model:value="orderStatusValue">
                                    <a-radio :style="{
                                        display: 'flex',
                                        height: '30px',
                                        lineHeight: '30px',
                                    }" :value="1">Dine In</a-radio>
                                    <a-radio :style="{
                                        display: 'flex',
                                        height: '30px',
                                        lineHeight: '30px',
                                    }" :value="2">Dine Out</a-radio>
                                </a-radio-group>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center py-2">
                    <Button @click="placeOrder()" style="background-color: #4CAF50; color: white;">Place
                        Order</Button>

                    <!-- Modal For QRCODE -->
                    <Modal :width="300" @on-cancel="exitDrawer()" v-model="qrCodeModal" :footer-hide="true">
                        <div ref="qrcode" class="w-full py-5">
                            <p class="text-xl text-center py-2">SCAN ME</p>
                            <div class="flex justify-center">
                                <qrcode-vue :value="qrCode" :size="280"></qrcode-vue>
                            </div>
                            <div class=" flex justify-center py-2">
                                <a-button @click="download()" class="text-lg" type="link">Click Me!
                                    Download</a-button>
                            </div>
                            <p class="text-sm text-center">You can locate this qrcode in Pre-Order</p>
                        </div>

                    </Modal>
                    <!-- Modal For QRCODE -->
                </div>
            </Drawer>
            <!-- /Place Order Drawer -->

            <!-- Add to Cart Drawer -->
            <Drawer placement="bottom" @on-close="exitDrawer()" :closable="false" v-model="addToCartDrawer" :styles="{ padding: '0' }"
                :height="250">
                <div class="flex justify-center space-x-2 py-3 items-center">
                    <img class="cursor-pointer object-contain w-20 h-20 " :src="`/img/${product.image}.jpg`">
                    <p class="text-2xl">{{ product.name }}</p>
                </div>
                <div class="space-y-1 px-3 border-b">
                    <p class="text-sm ">{{ auxName }}</p>
                    <div class="flex justify-between">
                        <p class="text-sm"><span>Price: ₱</span>{{ product.price }}</p>
                        <p class="text-sm"><span>Quantity: </span>
                            <InputNumber v-model="quantity" size="small" :min="1" :step="1" controls-outside />
                        </p>
                    </div>
                    <div>
                        <p class="text-sm"><span>Total Cost: ₱</span>{{ product.price * quantity }}</p>
                    </div>
                </div>
                <div class="flex justify-center py-4">
                    <Button @click="addToCart()" style="background-color: #4CAF50; color: white;">Add to Cart</Button>
                </div>
            </Drawer>
            <!-- /Add to Cart Drawer -->

            <!-- Recommendation -->
            <div class="pt-5">
                <div class="w-full text-center">
                    <p class="text-lg">Recommendation</p>
                </div>
                <div class="flex justify-center w-full">
                    <div class="flex flex-wrap ">
                        <div v-for="(product, index) in this.products" :key="product.id"
                            class="w-1/2 p-2 space-y-10 border-b">
                            <div class="py-2 px-2 shadow-md" @click="goToProduct(product.id)">
                                <div>
                                    <p class="line-clamp-1 font-bold">{{ product.name }}</p>
                                </div>

                                <div class="flex justify-center my-2">
                                    <img class="cursor-pointer  object-contain w-24 h-24 "
                                        :src="`/img/${product.image}.jpg`">
                                </div>

                                <div>
                                    <p><span>₱ </span>{{ product.price }}</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /Recommendation -->

        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import QrcodeVue from 'qrcode.vue';
import html2canvas from 'html2canvas';


export default defineComponent({
    components: {
        QrcodeVue
    },
    data() {
        return {
            product: '',
            auxName: '',
            auxNames: '',
            products: '',
            placeOrderDrawer: false,
            addToCartDrawer: false,
            quantity: 1,
            paymentOptionValue: null,
            orderStatusValue: null,
            preOrder: '',
            qrCode: '',
            qrCodeModal: false,
            totalCost: '',
            addToCartData: ''
        }
    },
    methods: {
        goToProduct(id) {
            const thiss = this
            axios.get(`/api/getProduct/${id}`)
                .then(function (response) {
                    thiss.product = response.data.product[0]
                    thiss.auxiliarys = response.data.available
                    thiss.products = response.data.products
                    thiss.auxNames = response.data.available.map(aux => aux.auxiliary.name).join(', ')
                    thiss.auxName = thiss.product.auxiliary.name
                    thiss.quantity = 1;
                    thiss.paymentOptionValue = null;
                    thiss.orderStatusValue = null;

                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                })
                .catch(function (error) {
                    console.log(error)
                });
            // console.log(thiss.product.auxiliary.name)
            
        },
        buy() {
            // console.log(localStorage.getItem('authToken') == null);
            const thiss = this
            if (localStorage.getItem('authToken') == null) {
                this.$Message.warning('Login First');
                this.$router.push({ path: '/login' });
                localStorage.setItem('product_id', thiss.product.id);
            } else {
                thiss.placeOrderDrawer = true;
            }
        },
        placeOrder() {
            const thiss = this
            if (thiss.paymentOptionValue === null) {
                thiss.$Message.warning('Select Payment Option');
            } else if (thiss.orderStatusValue === null) {
                thiss.$Message.warning('Select Order Status');
            } else {
                thiss.preOrder = {
                    product_id: thiss.product.id,
                    quantity: thiss.quantity,
                    status: thiss.orderStatusValue,
                    payment: thiss.paymentOptionValue
                };
                axios.post('/api/placeOrder', thiss.preOrder)
                    .then(response => {
                        thiss.qrCodeModal = true;
                        thiss.qrCode = response.data.toString();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        },
        async download() {
            const qrElement = this.$refs.qrcode;
            try {
                const canvas = await html2canvas(qrElement);
                const dataUrl = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = 'qrcode.png';
                link.click();
            } catch (error) {
                console.error('Failed to capture and download QR code:', error);
            }
        },
        exitDrawer() {
            const thiss = this
            thiss.placeOrderDrawer = false;
            thiss.addToCartDrawer = false;
            thiss.quantity = 1;
            thiss.paymentOptionValue = null;
            thiss.orderStatusValue = null;
        },
        cart() {
            const thiss = this
            if (localStorage.getItem('authToken') == null) {
                this.$Message.warning('Login First');
                this.$router.push({ path: '/login' });
                localStorage.setItem('product_id', thiss.product.id);
            } else {
                thiss.addToCartDrawer = true;
            }
        },
        addToCart() {
            const thiss = this

            thiss.addToCartData = {
                product_id: thiss.product.id,
                auxiliary_id: thiss.product.auxiliary_id,
                quantity: thiss.quantity,
            };
            axios.post('/api/addToCart', thiss.addToCartData)
                .then(response => {
                    console.log(response.data);
                    thiss.addToCartDrawer = false;
                    thiss.$Message.success('Success!. Check your cart');
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
    async created() {
        const thiss = this
        let id = this.$route.params.id

        await axios.get(`/api/getProduct/${id}`)
            .then(function (response) {
                thiss.product = response.data.product[0]
                thiss.auxiliarys = response.data.available
                thiss.products = response.data.products
                thiss.auxNames = response.data.available.map(aux => aux.auxiliary.name).join(', ')
                thiss.auxName = thiss.product.auxiliary.name
            })
            .catch(function (error) {
                console.log(error)
            });
        // console.log(thiss.product.auxiliary.name)
        
    }
});
</script>

<style scoped></style>