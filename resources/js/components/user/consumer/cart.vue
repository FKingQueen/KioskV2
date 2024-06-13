<template>
    <div>
        <div class="w-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem>My Cart</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class="flex w-full justify-between px-2">
                <!-- Checkout Button, disabled if isCheckOutDisabled is true -->
                <Button @click="checkOutF()" icon="md-basket" type="info" :disabled="isCheckOutDisabled">CheckOut</Button>
                <a-space>
                    <a-select placeholder="All Auxiliary" v-model:value="sort" style="width: 200px" :options="sortList" @change="sortBy()"></a-select>
                </a-space>
            </div>
            
            <!-- Modal for QR Code -->
            <Modal :width="300" v-model="qrCodeModal" @on-cancel="exitDrawer()" :footer-hide="true">
                <div ref="qrcode" class="w-full py-5">
                    <p class="text-xl text-center py-2">SCAN ME</p>
                    <div class="flex justify-center">
                        <qrcode-vue :value="qrCode" :size="280"></qrcode-vue>
                    </div>
                    <div class="flex justify-center py-2">
                        <a-button @click="download()" class="text-lg" type="link">Click Me! Download</a-button>
                    </div>
                    <p class="text-sm text-center">You can locate this qrcode in Pre-Order</p>
                </div>
            </Modal>

            <!-- Drawer for Checkout Items -->
            <Drawer placement="bottom" :closable="false" v-model="checkOutDrawer" :styles="{ padding: '0' }" :height="450">
                <div class="flex justify-between text-black p-2">
                    <p class="font-semibold">Check Out Products</p>
                    <p class="font-semibold">Total: {{ checkOutProductsLength }}</p>
                </div>

                <div v-for="(checkOutItem, index) in checkOutItems" :key="index" class="p-2 border-b overflow-auto">
                    <div class="flex justify-start space-x-3 py-3 items-center mr-5">
                        <div class="w-1/6">
                            <img class="cursor-pointer w-1/6 object-contain w-10 h-10" :src="`/img/${checkOutItem.product.image}.jpg`">
                        </div>
                        <div class="w-5/6">
                            <div>
                                <p class="truncate font-semibold text-xs">{{ checkOutItem.product.name }}</p>
                            </div>
                            <div class="flex justify-between">
                                <p class="text-xs">Qty: {{ checkOutItem.quantity }}</p>
                                <p class="text-xs">Price: ₱{{ checkOutItem.product.price }}</p>
                                <p class="text-xs">Total Price: ₱{{ checkOutItem.product.price * checkOutItem.quantity }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3">
                    <div class="w-full flex pt-2 justify-around">
                        <div>
                            <p class="text-sm">Payment Option</p>
                            <div>
                                <a-radio-group v-model:value="paymentOptionValue">
                                    <a-radio :style="{ display: 'flex', height: '30px', lineHeight: '30px' }" :value="1">Pay at the counter</a-radio>
                                    <a-radio :style="{ display: 'flex', height: '30px', lineHeight: '30px' }" :value="2">Card (Visa)</a-radio>
                                    <a-radio :style="{ display: 'flex', height: '30px', lineHeight: '30px' }" :value="3">E-money (Gcash & Maya)</a-radio>
                                </a-radio-group>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm">Order Status</p>
                            <div>
                                <a-radio-group v-model:value="orderStatusValue">
                                    <a-radio :style="{ display: 'flex', height: '30px', lineHeight: '30px' }" :value="1">Dine In</a-radio>
                                    <a-radio :style="{ display: 'flex', height: '30px', lineHeight: '30px' }" :value="2">Dine Out</a-radio>
                                </a-radio-group>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="demo-drawer-footer">
                    <Button @click="placeOrder()" size="large" style="background-color: #4CAF50; color: white;">Place Order</Button>
                </div>
            </Drawer>

            <div class="w-full p-2">
                <div>
                    <div v-for="(group, groupIndex) in groupCartItems" :key="groupIndex">
                        <div class="w-full bg-green-200 p-1 rounded">
                            <div class="flex justify-between px-2">
                                <div>
                                    <p v-if="group.auxiliary_id == '1'" class="font-semibold">Auxiliary (COE)</p>
                                    <p v-if="group.auxiliary_id == '2'" class="font-semibold">Auxiliary (CAS)</p>
                                    <p v-if="group.auxiliary_id == '3'" class="font-semibold">Auxiliary (CAFSD)</p>
                                    <p v-if="group.auxiliary_id == '4'" class="font-semibold">Auxiliary (CHS)</p>
                                </div>
                                <div>
                                    <p>Check Out</p>
                                </div>
                            </div>
                        </div>
                        <!-- Checkbox Group for Cart Items -->
                        <CheckboxGroup v-model="checkOutCheckBox" @on-change="CheckboxGroupF(group.auxiliary_id)">
                            <div v-for="item in group.items" :key="item.id">
                                <div class="p-3 test-start border-b flex justify-between items-center space-x-2 border">
                                    <div class=" ">
                                        <img class="cursor-pointer w-1/6 object-contain w-10 h-10" :src="`/img/${item.product.image}.jpg`">
                                    </div>
                                    <div class="w-4/6 ">
                                        <div>
                                            <p class="truncate font-semibold text-xs">{{ item.product.name }}</p>
                                        </div>
                                        <div class="flex justify-between">
                                            <p class="text-xs">Qty: {{ item.quantity }}</p>
                                            <p class="text-xs">Price: ₱{{ item.product.price }}</p>
                                            <a @click="change(item.id)" class="text-xs" type="link">Change</a>
                                        </div>
                                    </div>
                                    <div class="w-1/6 text-center ">
                                        <Checkbox :key="item.id" :disabled="isDisabled(group.auxiliary_id)" :label="item.id">
                                            <span> </span>
                                        </Checkbox>
                                    </div>
                                </div>
                            </div>
                        </CheckboxGroup>
                    </div>
                </div>
            </div>

            <!-- Modal for Change -->
            <Modal :width="300" v-model="changeModal" @on-cancel="exitChange()" :footer-hide="true">
                <div class="w-full ">
                    <div class="flex justify-center space-x-2 py-3 items-center">
                        <img class="cursor-pointer object-contain w-20 h-20 " :src="`/img/${this.changeProduct.product.image}.jpg`">
                        <p class="text-2xl">{{ this.changeProduct.product.name }}</p>
                    </div>
                    <div class="space-y-1 px-3">
                        <p class="text-sm ">{{ this.changeProduct.auxiliary.name }}</p>
                        <div class="flex justify-between">
                            <p class="text-sm"><span>Price: ₱</span>{{ this.changeProduct.product.price }}</p>
                            <p class="text-sm"><span>Quantity: </span>
                                <InputNumber v-model="this.changeProduct.quantity" size="small" :min="1" :step="1" controls-outside />
                            </p>
                        </div>
                        <div>
                            <p class="text-sm"><span>Total Cost: ₱</span>{{ this.changeProduct.product.price * this.changeProduct.quantity }}</p>
                        </div>
                    </div>
                    <div class="flex justify-center pt-3">
                        <Button @click="changeUpdate()" type="primary">Update Order</Button>
                    </div>
                </div>
            </Modal>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';
import QrcodeVue from 'qrcode.vue';
import html2canvas from 'html2canvas';
import axios from 'axios';

export default defineComponent({
    components: {
        QrcodeVue
    },
    data() {
        return {
            sortList: [
                { label: 'Auxiliary 1 (COE)', value: '1' },
                { label: 'Auxiliary 2 (CAS)', value: '2' },
                { label: 'Auxiliary 3 (CAFSD)', value: '3' },
                { label: 'Auxiliary 4 (CHS)', value: '4' }
            ],
            sort: null,
            cartItems: [],
            groupCartItems: [],
            checkOutCheckBox: [],
            checkDisable: {
                Aux1: false,
                Aux2: false,
                Aux3: false,
                Aux4: false
            },
            checkOutDrawer: false,
            checkOutProductsLength: 0,
            qrCodeModal: false,
            qrCode: '',
            checkOutItems: [],
            paymentOptionValue: null,
            orderStatusValue: null,
            ids: [],
            // Change
            changeModal: false,
            changeProduct: {
                id: '',
                quantity: null,
                user_id: '',
                product: '',
                auxiliary: ''
            }
        }
    },
    computed: {
        isCheckOutDisabled() {
            return this.checkOutCheckBox.length === 0;
        }
    },
    methods: {
        sortBy() {
            // Sorting logic
        },
        groupByAuxiliary() {
            const groupedItems = this.cartItems.reduce((acc, item) => {
                const auxId = item.auxiliary_id;
                if (!acc[auxId]) {
                    acc[auxId] = [];
                }
                acc[auxId].push(item);
                return acc;
            }, {});

            this.groupCartItems = Object.keys(groupedItems).map(auxiliaryId => ({
                auxiliary_id: parseInt(auxiliaryId),
                items: groupedItems[auxiliaryId]
            }));

            this.groupCartItems.sort((a, b) => a.auxiliary_id - b.auxiliary_id);
        },
        CheckboxGroupF(auxiliary_id) {
            const disableMap = {
                1: ['Aux2', 'Aux3', 'Aux4'],
                2: ['Aux1', 'Aux3', 'Aux4'],
                3: ['Aux1', 'Aux2', 'Aux4'],
                4: ['Aux1', 'Aux2', 'Aux3']
            };

            this.resetCheckDisable();

            if (this.checkOutCheckBox.length === 0) {
                return;
            }

            if (disableMap[auxiliary_id]) {
                disableMap[auxiliary_id].forEach(aux => {
                    this.checkDisable[aux] = true;
                });
            }
        },
        resetCheckDisable() {
            this.checkDisable = {
                Aux1: false,
                Aux2: false,
                Aux3: false,
                Aux4: false
            };
        },
        isDisabled(auxiliary_id) {
            return this.checkDisable[`Aux${auxiliary_id}`];
        },
        checkOutF() {
            this.checkOutItems = this.cartItems.filter(item => this.checkOutCheckBox.includes(item.id));
            this.checkOutProductsLength = this.checkOutItems.length;
            this.checkOutDrawer = true;
        },
        placeOrder() {
            if (this.paymentOptionValue === null) {
                this.$Message.warning('Select Payment Option');
            } else if (this.orderStatusValue === null) {
                this.$Message.warning('Select Order Status');
            } else {
                const cartOrder = {
                    ids: this.checkOutCheckBox,
                    status: this.orderStatusValue,
                    payment: this.paymentOptionValue
                };
                axios.post('/api/cartToOrder', cartOrder)
                    .then(response => {
                        this.qrCodeModal = true;
                        this.ids = response.data.ids;
                        this.qrCode = response.data.qrid.toString();
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
            this.groupCartItems.forEach((group, groupIndex) => {
                group.items = group.items.filter(item => !this.ids.includes(item.id));
                if (group.items.length === 0) {
                    this.groupCartItems.splice(groupIndex, 1);
                }
            });

            this.cartItems = this.cartItems.filter(item => !this.ids.includes(item.id));

            this.resetCheckoutState();
        },
        resetCheckoutState() {
            this.checkOutDrawer = false;
            this.paymentOptionValue = null;
            this.orderStatusValue = null;
            this.checkOutCheckBox = [];
            this.resetCheckDisable();
        },
        change(id) {
            const thiss = this
            this.changeModal = true
            axios.get(`/api/getCartItemEdit/${id}`)
                .then(function (response) {
                    thiss.changeProduct.id = response.data[0].id
                    thiss.changeProduct.quantity = response.data[0].quantity
                    thiss.changeProduct.user_id = response.data[0].user_id
                    thiss.changeProduct.product = response.data[0].product
                    thiss.changeProduct.auxiliary = response.data[0].product.auxiliary
                    console.log(thiss.changeProduct);
                })
                .catch(function (error) {
                    console.log(error)
                });
        },
        changeUpdate() {
            const thiss = this
            axios.post('/api/changeCartItemUpdate', thiss.changeProduct)
                .then(response => {
                    thiss.groupCartItems.quantity = response.data
                    thiss.changeModal = false;
                })
                .catch(error => {
                    console.error(error);
                });
                // console.log('checkOutItems',thiss.checkOutItems);
                // console.log('groupCartItems',thiss.groupCartItems);
        },
        exitChange() {
            this.changeModal = false
        }
    },
    async created() {
        try {
            const response = await axios.get('/api/getCartItems');
            this.cartItems = response.data;
            this.groupByAuxiliary();
        } catch (error) {
            console.log(error);
        }
    }
});
</script>

<style scoped>
.demo-drawer-footer {
    width: 100%;
    top: 0;
    left: 0;
    padding: 10px 16px;
    text-align: right;
    background: #fff;
}
</style>
