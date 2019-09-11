<template>
    <div>
        <div class="box box-primary flat">
            <div class="box-header with-border">
                <h4 class="box-title">Add Order</h4>
            </div>
            <div class="box-body">
                <form action="">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4"></div>
                            <div class="col-md-8">
                                <form>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select required v-model="menuId" class="form-control" id="">
                                                <option value=""></option>
                                                <option v-for="menu in menus" :value="menu.id">
                                                    {{ menu.name }}- {{ menu.formattedPrice }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input required type="number" placeholder="Quantity" v-model="qty"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-default" type="submit" @click.prevent="addOrder">
                                            <i class="fa fa-plus"></i>
                                            Add Order
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row" v-show="totalOrders > 0">
                            <div class="col-md-8">
                                <table class="table table-hover table-condensed table-striped">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(order,index) in orders" :key="index">
                                        <td>{{ order.menu.name}}</td>
                                        <td>{{ order.qty}}</td>
                                        <td>{{ order.menu.formattedPrice}}</td>
                                        <td>{{ formatMoney( order.qty * order.menu.price) }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm" @click="removeOrder(index)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>{{ formatMoney(totalPrice) }} RF</th>
                                        <th>{{ formatMoney(totalMenuPrice) }} RF</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="control-label">Order Date</label>
                                    <input required type="date" v-model="orderDate" id="date" placeholder="Order date"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="customer_name" class="control-label">Customer Name</label>
                                    <input type="text" v-model="customerName" id="customer_name" placeholder="Full name"
                                           class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="waiter" class="control-label">Waiter</label>
                                    <input type="text" v-model="waiter" id="waiter" placeholder="Waiter name"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <button type="submit"
                                            :disabled="saving"
                                            @click.prevent="saveOrder"
                                            class="btn btn-primary btn-block">
                                        <i class="fa fa-check-circle"></i>
                                        Save Order
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                orders: [],
                menus: [],
                menuId: "",
                qty: "",
                orderItems: {},
                customerName: "",
                orderDate: "",
                waiter: "",
                saving: false,
            };
        },
        created() {
            this.getMenus();
        }, methods: {
            getMenus() {
                axios.get('/api/menus')
                    .then(list => {
                        this.menus = list.data;
                    })
            },
            addOrder() {
                if (this.menuId === "" || this.qty === "") return;

                let menuId = this.menuId;
                let exist = this.orders.find(item => item.menu.id === menuId);
                if (exist) return;
                let menu = this.menus.find(item => item.id === menuId);
                let order = {
                    menu: menu,
                    qty: this.qty
                };

                this.orders.push(order);
                this.orderItems.orders = this.orders;
                this.menuId = "";
                this.qty = "";
            },
            removeOrder(index) {
                this.orders.splice(index, 1);
            },
            saveOrder() {
                this.saving = true;
                this.orderItems.customerName = this.customerName;
                this.orderItems.orderDate = this.orderDate;
                this.orderItems.waiter = this.waiter;
                axios.post('/api/orders', this.orderItems)
                    .then(data => {
                        location.reload();
                    }).catch(error => {
                });
            },
            formatMoney(number) {
                const DecimalSeparator = Number("1.2").toLocaleString().substr(1, 1);

                const AmountWithCommas = number.toLocaleString();
                const arParts = String(AmountWithCommas).split(DecimalSeparator);
                const intPart = arParts[0];
                let decPart = (arParts.length > 1 ? arParts[1] : '');
                decPart = (decPart + '0').substr(0, 2);

                return  intPart + DecimalSeparator + decPart;
            }
        },
        computed: {
            totalOrders() {
                return this.orders.length;
            },
            totalPrice() {
                let sum = 0;
                this.orders.forEach(item => {
                    sum += Number(item.menu.price);
                });
                return sum;
            },
            totalMenuPrice() {
                let sum = 0;
                this.orders.forEach(item => {
                    sum += (Number(item.menu.price) * Number(item.qty));
                });
                return sum;
            }
        }, filters: {}
    }
</script>