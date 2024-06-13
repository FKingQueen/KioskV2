<template>
    <div>
        <div class="w-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem>Pre Order</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class="flex w-full justify-end p-2 ">
                <a-select placeholder="Sort" v-model:value="sort" style="width: 100%" :options="sortList"
                    @change="sortBy()"></a-select>
            </div>
            <div class="w-full p-2">
                <div>
                    <div class="" v-for="(group, index) in groupPreOrders" :key="index">
                        <div class="w-full bg-green-200 p-1 rounded">
                            <p v-if="sort == '2'" class="font-semibold">{{ group.date }}</p>
                            <div v-if="sort == '1'">
                                <p v-if="group.auxiliary_id == '1'" class="font-semibold">Auxiliary (COE)</p>
                                <p v-if="group.auxiliary_id == '2'" class="font-semibold">Auxiliary (CAS)</p>
                                <p v-if="group.auxiliary_id == '3'" class="font-semibold">Auxiliary (CAFSD)</p>
                                <p v-if="group.auxiliary_id == '4'" class="font-semibold">Auxiliary (CHS)</p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div v-for="preOrder in group.preOrders" :key="preOrder.id">
                                <div class="p-3 test-start border-b flex justify-between">
                                    <p v-if="preOrder.status == 1">Order status: DINE IN </p>
                                    <p v-if="preOrder.status == 2">Order status: TAKE OUT </p>
                                    <a @click="qrCodeF(preOrder.id)" class="text-sm" type="link">
                                        QRcode</a>

                                    <a @click="preOrderDrawerF(preOrder)" class="text-sm" type="link">
                                        View</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal For QRCODE -->
                    <Modal :width="300" v-model="qrCodeModal" :footer-hide="true">
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

                    <!-- Drawer -->
                    <Drawer placement="bottom" :closable="false" v-model="preOrderDrawer" :styles="{ padding: '0' }"
                        :height="450">
                        <div class="flex justify-between text-black p-2">
                            <p class="font-semibold">Pre Order Products</p>
                            <p class="font-semibold">Total: {{ preOrderProductsLength }}</p>
                        </div>

                        <div v-for="(preOrderProduct, index) in preOrder.pre_order_products"
                            class="p-2 border-b overflow-auto">
                            <div class="flex justify-start space-x-3 py-3 items-center">
                                <img class="cursor-pointer object-contain w-20 h-20 "
                                    :src="`/img/${preOrderProduct.product.image}.jpg`">
                                <div>
                                    <p class="text-lg">{{ preOrderProduct.product.name }}</p>
                                    <p class="text-sm"><span>Price: ₱</span>{{ preOrderProduct.product.price }}</p>
                                    <p class="text-sm"><span>Total Cost: ₱</span>{{ preOrderProduct.product.price *
                                        preOrderProduct.quantity }}</p>
                                    <p class="text-sm"><span>Quantity: </span>{{ preOrderProduct.quantity }}</p>
                                </div>

                            </div>
                        </div>
                        <div class="demo-drawer-footer">
                            <a-popconfirm @confirm="deletePreOrder(preOrder.id)" placement="topRight"
                                title="Pre-order will be deleted. Continue?" ok-text="Yes" cancel-text="No">
                                <Button style="background-color: #FF0000; color: white;">Cancel
                                    Order</Button>
                            </a-popconfirm>
                        </div>
                    </Drawer>
                    <!-- /Drawer -->
                </div>
            </div>

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
            sortList: [
                {
                    value: '1',
                    label: 'Auxiliary'
                },
                {
                    value: '2',
                    label: 'Date'
                }
            ],
            sort: '2',
            preOrders: [],
            groupPreOrders: [],
            qrCodeModal: false,
            qrCode: '',
            preOrderDrawer: false,
            preOrder: '',
            preOrderProductsLength: '',
        }
    },
    methods: {
        sortBy() {
            const thiss = this
            if (thiss.sort == '1') {
                const groups = thiss.preOrders.reduce((acc, preOrder) => {
                    preOrder.pre_order_products.forEach((pop) => {
                        const auxiliaryId = pop.product.auxiliary_id;
                        if (!acc[auxiliaryId]) {
                            acc[auxiliaryId] = [];
                        }
                        acc[auxiliaryId].push(preOrder);
                    });
                    return acc;
                }, {});

                thiss.groupPreOrders = Object.keys(groups).map(auxiliaryId => {
                    return {
                        auxiliary_id: auxiliaryId,
                        preOrders: groups[auxiliaryId]
                    };
                });
                console.log(thiss.groupPreOrders[0].preOrders);
                const seen = new Set();
                
                thiss.groupPreOrders[0].preOrders = thiss.groupPreOrders[0].preOrders.filter(item => {
                    if (seen.has(item.id)) {
                        return false;
                    } else {
                        seen.add(item.id);
                        return true;
                    }
                });

                console.log(thiss.groupPreOrders);
            }

            if (thiss.sort == '2') {
                thiss.groupPreOrdersByDate();
            }
        },
        groupPreOrdersByDate() {
            const thiss = this
            const groups = thiss.preOrders.reduce((acc, preOrder) => {
                const date = new Date(preOrder.created_at).toDateString();
                if (!acc[date]) {
                    acc[date] = [];
                }
                acc[date].push(preOrder);
                return acc;
            }, {});

            // Sort the dates in descending order
            const sortedDates = Object.keys(groups).sort((a, b) => new Date(b) - new Date(a));

            // Map the sorted dates to create the groupPreOrders array
            thiss.groupPreOrders = sortedDates.map(date => {
                return {
                    date: date,
                    preOrders: groups[date]
                };
            });
        },
        qrCodeF(id) {
            const thiss = this
            thiss.qrCodeModal = true;
            thiss.qrCode = id.toString();
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
        preOrderDrawerF(preOrder) {
            const thiss = this;
            this.preOrderProductsLength = preOrder.pre_order_products.length;
            thiss.preOrderDrawer = true;
            thiss.preOrder = preOrder;
        },
        deletePreOrder(id) {
            let data = {
                id: id
            }
            const thiss = this;
            axios.post('/api/deletePreOrder', data)
                .then(response => {
                    for (let i = 0; i <= thiss.groupPreOrders.length - 1; i++) {
                        for (let k = 0; k <= thiss.groupPreOrders[i].preOrders.length - 1; k++) {
                            if (thiss.groupPreOrders[i].preOrders[k].id === response.data) {
                                thiss.groupPreOrders[i].preOrders.splice(k, 1);
                                // Check if the preOrders array is empty after deletion
                                if (thiss.groupPreOrders[i].preOrders.length === 0) {
                                    thiss.groupPreOrders.splice(i, 1);


                                }
                                // If you need to break out of the outer loop as well
                                // Drawer
                                thiss.preOrderDrawer = false;
                                return;
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
    async created() {
        const thiss = this
        await axios.get('/api/getPreOrder')
            .then(function (response) {
                thiss.preOrders = response.data;
                thiss.groupPreOrdersByDate();
            })
            .catch(function (error) {
                console.log(error)
            });
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