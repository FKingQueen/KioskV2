<template>
    <div>
        <div class="w-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem>History</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div>
                <p class="text-center">Expenses Chart</p>
                <div class="flex justify-center">
                    <highcharts :constructor-type="'stockChart'" class="w-full" :options="chartOptions"></highcharts>
                </div>
            </div>
            <div>
                <p class="text-center p-2">Purchase History</p>
                <div>
                    <div v-for="(historyOrder, index) in historyOrders" :key="index" class="p-2 border-b overflow-auto">
                        <div class="flex justify-start space-x-3 py-3 items-center mr-5">
                            <div class="w-1/6">
                                <img class="cursor-pointer w-1/6 object-contain w-10 h-10" :src="`/img/${historyOrder.product.image}.jpg`">
                            </div>
                            <div class="w-5/6">
                                <div>
                                    <p class="truncate font-semibold text-xs">{{ historyOrder.name }}</p>
                                </div>
                                <div class="flex justify-between">
                                    <p class="text-xs">Qty: {{ historyOrder.quantity }}</p>
                                    <p class="text-xs">Price: ₱{{ historyOrder.price }}</p>
                                    <p class="text-xs">Total Price: ₱{{ historyOrder.price * historyOrder.quantity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
    data() {
        return {
            chartOptions: {
                chart: {
                    zoomType: 'x',
                    type: "spline",
                    scrollablePlotArea: {
                        minWidth: 200,
                        scrollPositionX: 1,
                    },

                },
                rangeSelector: {
                    buttons: [{
                        type: 'week',
                        count: 1,
                        text: '1w'
                    }, {
                        type: 'month',
                        count: 1,
                        text: '1m'
                    }, {
                        type: 'year',
                        count: 1,
                        text: '1y'
                    }, {
                        type: 'all',
                        text: 'All'
                    }],
                    inputEnabled: true,
                    selected: 5
                },
                series: [{
                    data: []
                }],
                plotOptions: {
                    series: {
                        pointInterval: 86400000,
                        pointStart: Date.UTC(2010, 0, 1),
                        marker: {
                            enabled: false,
                            states: {
                                hover: {
                                    enabled: false
                                }
                            }
                        }
                    },
                },

            },
            historyOrders: []
        };
    },
    async created() {
        const thiss = this
        try {
            const response = await axios.get('/api/getHistoryData');
            console.log(response.data);

            thiss.historyOrders = response.data.historyOrder

            for (let i = 0; i < response.data.data.length; i++) {
                thiss.chartOptions.series[0].data[i] = [];
                thiss.chartOptions.series[0].data[i][0] = Date.UTC(
                    response.data.data[i].date[0],
                    response.data.data[i].date[1] - 1,
                    response.data.data[i].date[2],
                );
                thiss.chartOptions.series[0].data[i][1] =  Math.round(response.data.data[i].average_price);
            }
        } catch (error) {
            console.log(error);
        }
    }
});
</script>

<style scoped></style>