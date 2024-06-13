<template>
    <div>
        <div class="w-full ">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem>Dashboard</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class="flex w-full justify-between p-2">
                <Poptip placement="bottom-start">
                    <Button icon="ios-options" type="text">Filter</Button>
                    <template #content>
                        <div class="w-40">
                            <p class="font-bold pb-2">Filter: Auxiliary</p>
                            <a-checkbox-group :change="filter()" v-model:value="state.filterData" name="checkboxgroup" :options="options" />
                        </div>
                       
                    </template>
                </Poptip>

                <a-space>
                    <a-select placeholder="Sort" v-model:value="sort" style="width: 200px" :options="sortList"
                        @change="sortBy()"></a-select>
                </a-space>
            </div>
            
            <div class="flex justify-center w-full">
                <div class="flex flex-wrap ">
                    <div v-for="(product, index) in this.filteredProducts" :key="product.id"
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
    </div>
</template>

<script>
import { defineComponent, reactive } from 'vue';
export default defineComponent({
    data() {
        const state = reactive({
            filterData: [],
        });
        const options = [
            {
                label: 'Auixiliary 1 (COE)',
                value: 1,
            },
            {
                label: 'Auixiliary 2 (CAS)',
                value: 2,
            },
            {
                label: 'Auixiliary 3 (CAFSD)',
                value: 3,
            },
            {
                label: 'Auixiliary 4 (CHS)',
                value: 4,
            },
        ];
        return {
            sortList: [
                {
                    value: '1',
                    label: 'Sort by price: Low to High'
                },
                {
                    value: '2',
                    label: 'Sort by price: High to Low'
                },
                {
                    value: '3',
                    label: 'Sort by Availability'
                },
            ],
            sort: null,
            products: null,
            filteredProducts: null,
            options,
            state
        }
    },
    methods: {
        sortBy() {
            const thiss = this
            if (thiss.sort == 1) {
                thiss.filteredProducts = thiss.filteredProducts.sort((a, b) => a.price - b.price)
            }
            if (thiss.sort == 2) {
                thiss.filteredProducts = thiss.filteredProducts.sort((a, b) => b.price - a.price)
            }
            if (thiss.sort == 3) {
                thiss.filteredProducts  = thiss.products.filter(product => product.is_available);
            }
            
        },
        filter() {
            const thiss = this
            thiss.sort = null;
            if(thiss.state.filterData.length != 0){
                thiss.filteredProducts = thiss.products.filter(product => thiss.state.filterData.includes(product.auxiliary_id));
            }
        },
        goToProduct(id){
            this.$router.push({ name: 'productInfo', params: { id } })
        }
    },
    async created() {
        const thiss = this
        await axios.get('/api/getDashboardProducts')
            .then(function (response) {
                thiss.products = response.data
                thiss.filteredProducts  = response.data.filter(product => product.is_available);
            })
            .catch(function (error) {
                console.log(error)
            });
    }
});
</script>

<style scoped></style>