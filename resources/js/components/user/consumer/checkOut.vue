<template>
    <div>
        <div class="w-full h-full">
            <div class="p-2 w-full">
                <Breadcrumb>
                    <BreadcrumbItem to="/user/dashboard">Dashboard</BreadcrumbItem>
                    <BreadcrumbItem @click="goToProduct()">Product</BreadcrumbItem>
                    <BreadcrumbItem>Check Out</BreadcrumbItem>
                </Breadcrumb>
            </div>
            <div class=" w-full p-2">
                <div class="flex justify-center py-10">
                    <img class="cursor-pointer object-contain w-60 h-60" :src="`/img/${product.image}.jpg`">
                </div>
                <div class="space-y-1 ">
                    <p class="text-2xl">{{ product.name }}</p>
                    <div class="flex justify-around">
                        <p class="text-sm"><span>Price: ₱</span>{{ product.price }}</p>
                        <p class="text-sm"><span>Quantity: </span><InputNumber v-model="quantity" size="small" :min="1" :step="1" controls-outside/></p>
                    </div>
                </div>
                <div class="flex justify-center py-5">
                    <Button size="large" style="background-color: #4CAF50; color: white;">Place Order</Button>
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
            product: '',
            auxName: '',
            auxNames: '',
            products: '',
            quantity: 1
        }
    },
    methods: {
        goToProduct(){
            let id = this.$route.params.id
            this.$router.push({ name: 'productInfo', params: { id } })
        }
    },
    async created() {
        const thiss = this
        let id= this.$route.params.id

        await axios.get(`/api/getProduct/${id}`)
            .then(function (response) {
                thiss.product = response.data.product[0]
            })
            .catch(function (error) {
                console.log(error)
            });
        // console.log(thiss.product.auxiliary.name)
        // thiss.auxName = thiss.product.auxiliary.name
    }
});
</script>

<style scoped></style>