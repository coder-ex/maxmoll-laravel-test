<template>
    <div class="container">
        <table class="table table-sm table-bordered">
            <thead class="thead-blue">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">CUSTOMER</th>
                    <th scope="col">PHONE</th>
                    <th scope="col">MANAGER</th>
                    <th scope="col">TYPE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col" style="background: #ffffff;"></th>
                </tr>
            </thead>
            <tbody class="tbody-lite">
                <tr v-for="item in data" v-bind:item="item" v-bind:key="item.id">
                    <th scope="row">{{ item.id }}</th>
                    <td>{{ item.customer }}</td>
                    <td>{{ item.phone }}</td>
                    <td>{{ item.user.name }}</td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.status }}</td>
                    <td style="background: #ffffff;">
                        <button type="button" class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <spin v-if="loading"></spin>
    </div>
</template>

<script>
import Spin from "../Spiner";

export default {
    name: 'Orders',
    components: {
        Spin,
    },
    data() {
        return {
            data: [],
            isError: false,
            loading: true,
        }
    },
    async mounted() {
        try {
            let res = await axios.get('http://school.loc/api/orders');
            this.data = await res.data.products;

            if (this.data.length > 0) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
            this.loading = false;
        }
    },
    methods: {
        isShow() {
            this.isError = !this.isError;
        }
    }
}
</script>

<style>
.thead-blue {
    background: #639ffa;
}
.tbody-lite {
    background: #c1ebff;
}
</style>
