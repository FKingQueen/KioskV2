<template>
    <div>
        <div class="w-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem>Pre Order</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class="w-full p-2">
                <div>
                    <div class="" v-for="(group, index) in groupPreOrders" :key="index">
                        <div class="w-full bg-green-200 p-1 rounded">
                            
                            <p class="font-semibold">{{ group.date }}</p>
                        </div>
                        <div class="w-full">
                            <div v-for="preOrder in group.preOrders" :key="preOrder.id">
                                <div class="p-3 test-start border-b flex justify-between">
                                    <p class="font-sm">{{ preOrder.id }}</p>
                                    <p v-if="preOrder.status == 1">Order status: DINE IN </p>
                                    <p v-if="preOrder.status == 2">Order status: TAKE OUT </p>
                                    <a @click="approve(preOrder.id)" class="text-sm" type="link">
                                        Approve</a>
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
            groupPreOrders: [],
            preOrders: [],
        }
    },
    methods: {
        sortByDate(preOrders) {
            const thiss = this
            const groups = preOrders.reduce((acc, preOrder) => {
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
        approve(id) {
            const thiss = this
            const idForm = {
                id: id
            }
            // console.log(thiss.groupPreOrders.length);
            // console.log(thiss.groupPreOrders[0].preOrders);
            axios.post('/api/approvePreOrder', idForm)
                .then(response => {
                    // console.log(response.data);
                    thiss.$Message.success('Product successfully approved');
                    for(let i = 0; i < thiss.groupPreOrders[0].preOrders.length; i++){
                        if(thiss.groupPreOrders[0].preOrders[i].id == response.data){
                            thiss.groupPreOrders[0].preOrders.splice(i, 1);
                            console.log('success');
                            break;
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
        try {
            const response = await axios.get('/api/getPreOrders');
            thiss.sortByDate(response.data);
            // thiss.preOrders
            console.log(response.data);
        } catch (error) {
            console.log(error);
        }
    }
});
</script>

<style scoped></style>