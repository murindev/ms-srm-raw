<template>
<div>

    <Button class="settings" color="yellow" icon="h-icon-setting" @click="settingsModal=true"/>
    <div class="cntItems"> <span class="h-tag ">{{cnt}}</span>  из &nbsp;  <span class="h-tag h-tag-bg-green">{{lidsList.length}}</span></div>
    <Button class="clearFilter" color="red"  @click="clearFilter()">Сброс фильтра</Button>
    <a class="createLid" href="/vue/lid/create">СОЗДАТЬ НОВЫЙ ЛИД</a>


<!--    <Button color="primary" icon="h-icon-left" @click="testt()">eeeee</Button>-->

    <Modal class="actModal" v-model="settingsModal"  :hasCloseIcon="true">
        <div>

            <Transfer v-model="userSettings" :datas="settings" keyName="code" @change="updateSettings">
                <template slot="sourceHeader">
                    <div class="h-transfer-header">Поля не отображаемые</div>
                </template>
                <template slot="targetHeader">
                    <div class="h-transfer-header">Поля для отображения</div>
                </template>
                <template slot-scope="{option}" slot="item">
                    {{option.name}}
                </template>
            </Transfer>
        </div>
    </Modal>

    <table class="table tableSection" v-if="customersSwitcher">

        <thead>
        <tr>
            <th v-for="(cf,cfKey) in customersFilter">{{cf.n}}</th>
        </tr>
        <tr>
            <th v-for="(cf,cfKey) in customersFilter">
                <template v-if="cfKey === 'status'">
                    <Select v-if="h.customer" style="width: 80%; margin: 0 auto;" :datas="h.customer"  v-model="customersFilter[cfKey].v"  keyName="value" titleName="name"
                            @change="filterCustomers()"
                    ></Select>
                </template>
                <template v-else-if="cfKey === 'name'">
                    <input style="width: 80%" type="text" v-model="customersFilter[cfKey].v"
                          @keyup="filterCustomers()">
                </template>
                <template v-else-if="cfKey === 'organization'">
                    <input style="width: 80%" type="text" v-model="customersFilter[cfKey].v"
                           @keyup="filterCustomers()">
                </template>
                <template v-else-if="cfKey === 'phone'">
                    <input style="width: 80%" type="text" v-model="customersFilter[cfKey].v"
                           @keyup="filterCustomers()">
                </template>
                <template v-else-if="cfKey === 'email'">
                    <input style="width: 80%" type="text" v-model="customersFilter[cfKey].v"
                           @keyup="filterCustomers()">
                </template>
            </th>
        </tr>
        </thead>



        <tbody>
        <tr v-for="customer in customersFiltered">
            <td v-for="(cf,cfKey) in customersFilter">
                <span class="h-tag pointer" v-if="cfKey === 'status'"
                @click="searchCustomer(customer.id)">
                    <template v-for="c in h.customer" v-if="c.value === customer[cfKey]"> {{c.name}}</template>
                </span>
                <span class="h-tag pointer" v-else-if="cfKey === 'organization'"
                      @click="searchCustomer(customer.id)">
                    <template v-if="customer[cfKey]">{{customer[cfKey]}}</template>
                    <template v-else-if="customer.organization_long">{{customer.organization_long}}</template>
                    <template v-else></template>
                </span>
                <span class="h-tag pointer" v-else
                      @click="searchCustomer(customer.id)">
                    {{customer[cfKey]}}</span>
            </td>
        </tr>
        </tbody>

<!--        <tfoot><tr><th></th></tr></tfoot>-->

    </table>


    <table class="h-table w100">

        <thead>
        <tr>
            <th v-for="col in cols">{{col.name}}</th>
        </tr>
        <tr>
            <th v-for="col in cols">

<!--code: 'id', name: 'ID' -->
                <template v-if="col.code === 'id'"><input class="w100" type="number" v-model="arFilter.id.v"   @input="filterData()"> </template>
<!--code: 'contract', name: 'Номер договора' -->
                <template v-if="col.code === 'contract'"><input class="w100" type="text" v-model="arFilter.contract.v"   @input="filterData()"> </template>
<!--code: 'date_start', name: 'Дата обращения' -->
                <template v-if="col.code === 'date_start'"><input class="w100" type="text" v-model="arFilter.date_start.v"   @input="filterData()"> </template>
<!--code: 'manager_starter', name: 'Начавший лид' -->
                <template v-else-if="col.code === 'manager_starter'">
                    <Select v-if="unique('manager_starter').map(i => h.users[i])" v-model="arFilter.manager_starter.v" :datas="unique('manager_starter').map(i => h.users[i])" keyName="id" titleName="name" @change="filterData()"/>
                </template>
<!--code: 'site', name: 'Сайт' -->
                <template v-else-if="col.code === 'site'"> <Select v-model="arFilter.site.v" :datas="h.companies" keyName="id" titleName="name" @change="filterData()"/> </template>
<!--code: 'servicing', name: 'Обслуживание'-->
                <template v-else-if="col.code === 'servicing'"> <Select v-model="arFilter.servicing.v" :datas="h.servicing" keyName="id" titleName="name" @change="filterData()"/> </template>
<!--code: 'customer', name: 'Клиент' -->
                <Button v-else-if="col.code === 'customer'" class="w100"  color="primary" icon="h-icon-search" @click="customersSwitcher = !customersSwitcher">Поиск клиента</Button>
<!--code: 'comment', name: 'Комментарий'-->
                <template v-if="col.code === 'comment'"><input class="w100" type="text" v-model="arFilter.comment.v"   @input="filterData()"> </template>
            </th>
        </tr>
        </thead>

        <tbody>
        <tr v-for="lid in lidFiltered">
            <td v-for="(col,colKey) in cols">

<!--code: 'id', name: 'ID'-->
                <template v-if="col.code === 'id'"><a target="_blank" :href="`/layout/lid/${lid[col.code]}/edit`">Открыть лид ({{lid[col.code]}})</a></template>
<!--code: 'contract', name: 'Номер договора' -->
                <template v-else-if="col.code === 'contract'">{{lid[col.code] | truncate(20, '...')}}</template>
<!--code: 'date_start', name: 'Дата обращения' -->
                <template v-else-if="col.code === 'date_start'">{{lid[col.code]}}</template>
<!--code: 'manager_starter', name: 'Начавший лид' -->
                <template v-else-if="col.code === 'manager_starter'">
                    <template v-if="h.users[lid[col.code]]">{{h.users[lid[col.code]].name}}</template>
                </template>
<!--code: 'site', name: 'Сайт' -->
                <template v-else-if="col.code === 'site'">{{h.companies[lid[col.code]] ? h.companies[lid[col.code]].name : ''}}</template>
<!--code: 'servicing', name: 'Обслуживание' -->
                <template v-else-if="col.code === 'servicing' && lid[col.code]">
                    {{h.servicing[lid[col.code]].name}}
                </template>
<!--code: 'customer', name: 'Клиент' -->
                <template v-else-if="col.code === 'customer' && obCustomers[lid[col.code]]">
                    <span v-if="obCustomers[lid[col.code]].organization"
                          v-tooltip :content="obCustomers[lid[col.code]].organization">{{obCustomers[lid[col.code]].organization | truncate(40, '...') }}</span>
                    <span
                            v-tooltip :content="obCustomers[lid[col.code]].organization_long"
                            v-else-if="obCustomers[lid[col.code]].organization_long">{{obCustomers[lid[col.code]].organization_long | truncate(40, '...') }}</span>
                    <span
                            v-tooltip :content="obCustomers[lid[col.code]].name"
                            v-else-if="obCustomers[lid[col.code]].name">{{obCustomers[lid[col.code]].name | truncate(40, '...') }}</span>
                    <span v-else></span>
                </template>
<!--code: 'comment', name: 'Комментарий' -->
                <template v-else-if="col.code === 'comment' && lid[col.code]">
                    <div style="max-width: 400px">
                        <TextEllipsis :text="lid[col.code]">
                            <span slot="before" class="h-tag h-tag-red"> >> </span>
                        </TextEllipsis>
                    </div>
                </template>
<!--by default-->
                <template v-else>
                    {{lid[col.code]}}
                </template>

            </td>
        </tr>
        </tbody>

<!--        <tfoot><tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr></tfoot>-->

    </table>
</div>
</template>

<script>
    import Axios from 'axios';
    import {mapGetters} from 'vuex';

    export default {
        props:['manager'],
        name: "LidsList",
        data(){
            return {
                cnt:0,
                cols: [],
                lidsList:[],
                lidFiltered:[],
                settingsModal: false,
                userSettings:[],
                settings: [
                    {code: 'id', name: 'ID' },
                    {code: 'contract', name: 'Номер договора' },
                    {code: 'date_start', name: 'Дата обращения' },
                    {code: 'manager_starter', name: 'Начавший лид' },
                    {code: 'site', name: 'Сайт' },
                    {code: 'servicing', name: 'Обслуживание' },
                    {code: 'customer', name: 'Клиент' },
                    {code: 'comment', name: 'Комментарий' },
                    // {code: 'manager', name: 'Manager' },
                    // {code: 'date_from', name: 'Начало договора' },
                    // {code: 'date_to', name: 'Окончание договора' },
                    {code: 'contract_transferred', name: 'Договор передан' },
                    {code: 'contract_signed', name: 'Договор подписан' },
                    {code: 'contract_file', name: 'Файл договора' },
                    // {code: 'action', name: 'Последнее дейстаие' },
                    // {code: 'action_date', name: 'action_date' },
                    // {code: 'action_note', name: 'action_note' },
                    // {code: 'customer_status', name: 'customer_status' },
                    // {code: 'customer_payment', name: 'customer_payment' },
                    // {code: 'payment_rule', name: 'payment_rule' },
                    // {code: 'payment_condition', name: 'payment_condition' },
                    // {code: 'price', name: 'price' },
                    // {code: 'price_fact', name: 'price_fact' },
                    // {code: 'kpi', name: 'kpi' },
                ],
                arFilter: {
                    id: {
                        v: null,
                        f: function (el) {
                            return String(el['id']).indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    manager_starter: {
                        v: null,
                        f: function (el) {
                            return el['manager_starter'] === Number(this.v)
                        }
                    },
                    manager: {
                        v: null,
                        f: function (el) {
                            return el['manager'] === this.v
                        }
                    },
                    site: {
                        v: null,
                        f: function (el) {
                            return el['site'] === this.v
                        }
                    },
                    customer: {
                        v: null,
                        f: function (el) {
                            return el['customer'] === this.v
                        }
                    },
                    contract: {
                        v: null,
                        f: function (el) {
                            return el['contract'] && String(el['contract']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    }, date_from: {
                        v: null,
                        f: function (el) {
                            return el['date_from'] === this.v
                        }
                    }, date_to: {
                        v: null,
                        f: function (el) {
                            return el['date_to'] === this.v
                        }
                    },
                    contract_transferred: {
                        v: null,
                        f: function (el) {
                            return el['contract_transferred'] === this.v
                        }
                    },
                    contract_signed: {
                        v: null,
                        f: function (el) {
                            return el['contract_signed'] === this.v
                        }
                    },
                    contract_file: {
                        v: null,
                        f: function (el) {
                            return el['contract_file'] === this.v
                        }
                    },
                    servicing: {
                        v: null,
                        f: function (el) {
                            return el['servicing'] === this.v
                        }
                    },
                    action: {
                        v: null,
                        f: function (el) {
                            return el['action'] === this.v
                        }
                    },
                    date_start: {
                        v: null,
                        f: function (el) {
                            return el['date_start'] && String(el['date_start']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    action_date: {
                        v: null,
                        f: function (el) {
                            return el['action_date'] === this.v
                        }
                    },
                    action_note: {
                        v: null,
                        f: function (el) {
                            return el['action_note'] === this.v
                        }
                    },
                    customer_payment: {
                        v: null,
                        f: function (el) {
                            return el['customer_payment'] === this.v
                        }
                    },
                    payment_rule: {
                        v: null,
                        f: function (el) {
                            return el['payment_rule'] === this.v
                        }
                    },
                    payment_condition: {
                        v: null,
                        f: function (el) {
                            return el['payment_condition'] === this.v
                        }
                    },
                    comment: {
                        v: null,
                        f: function (el) {
                            return el['comment'] && String(el['comment']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    price: {
                        v: null,
                        f: function (el) {
                            return el['price'] === this.v
                        }
                    },
                    price_fact: {
                        v: null,
                        f: function (el) {
                            return el['price_fact'] === this.v
                        }
                    },
                    kpi: {
                        v: null,
                        f: function (el) {
                            return el['kpi'] === this.v
                        }
                    },
                },
                customersFilter: {
                    status: {
                        n: 'статус',
                        v: null,
                        f: function (el) {
                            return Number(el['status']) === Number(this.v)
                        }
                    },
                    name: {
                        n: 'Имя',
                        v: null,
                        f: function (el) {
                            // return el['name'].search(new RegExp(this.v, "ig")) > 0
                            return el['name'] && el['name'].length > 2 && el['name'].toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    organization: {
                        n: 'Организация',
                        v: null,
                        f: function (el) {
                            return (el['organization'] && el['organization'].length > 2
                                && el['organization'].toLowerCase().indexOf(this.v.toLowerCase()) !== -1)
                                || (el['organization_long'] && el['organization_long'].length > 2
                                && el['organization_long'].toLowerCase().indexOf(this.v.toLowerCase()) !== -1)

                        }
                    },
                    phone: {
                        n: 'Телефон',
                        v: null,
                        f: function (el) {
                            let str = el['phone'].toString.replace(/\D+/g,"");
                            return str && str.length > 2 && str.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    email: {
                        n: 'email',
                        v: null,
                        f: function (el) {
                            return el['email'] && el['email'].length > 2 && el['email'].toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    }
                },
                customers: [],
                obCustomers: {},
                customersSwitcher: false,
                customersFiltered: {},

            }
        },
        computed: {
            ...mapGetters(['h']),
        },
        methods:{

            unique(key){
                return [...new Set(this.lidsList.map(item => item[key]))];
            },

            clearCustomersFilter(){
                for (let key in this.customersFilter ){
                    this.$set(this.customersFilter[key],'v',null);
                }
                this.filterData();
            },

            clearFilter(){
                for (let key in this.arFilter ){
                    this.$set(this.arFilter[key],'v',null);
                }
                this.filterData();
                this.clearCustomersFilter();
                this.filterCustomers();
            },

            blender(arr, filters) {
                return arr.filter(function (el) {
                    return filters.every(function (filter) {
                        return filter.f(el)
                    })
                })
            },

            filterData() {
                let arF = [];
                for(let k in this.arFilter) if(this.arFilter[k].v !== null){
                    arF.push(this.arFilter[k])
                }
                console.log(arF);
                let res = this.blender(this.lidsList,arF);
                this.$set(this.$data, 'lidFiltered', res);
                this.$set(this.$data, 'cnt', res.length);
            },

            filterCustomers() {
                let arF = [];
                for(let k in this.customersFilter) if(this.customersFilter[k].v !== null){
                    arF.push(this.customersFilter[k])
                }
                let res = this.blender(this.customers,arF);
                this.$set(this.$data, 'customersFiltered', res);
            },

            searchCustomer(id){
                this.$set(this.$data.arFilter.customer,'v',id);
                this.filterData();
            },

            async getSettings(){
                try {
                    let response = await Axios.post('/lid/async',{
                        func: 'readSettings',
                        manager: this.manager
                    });
                    if(response.data) {
                        this.$set(this.$data, 'userSettings', response.data.settings);
                        this.$set(this.$data, 'cols', this.settings.filter(i => this.$data.userSettings.indexOf(i.code) !== -1))
                    }
                } catch (error) {
                    console.log(error);
                }
            },

            async updateSettings(){
                try {
                    let response = await Axios.post('/lid/async',{
                        func: 'updateSettings',
                        manager: this.manager,
                        values: arguments[0]
                    });
                    if(response.data){
                        this.$set(this.$data,'userSettings',response.data.settings)
                    }
                } catch (error) {
                    console.log(error);
                }
            },

            async getList(){
                try {
                    let response = await Axios.post('/lid/async',{
                        func: 'getList'
                    });
                    if(response.data){
                        this.$set(this.$data,'lidsList',response.data);
                        this.$set(this.$data,'lidFiltered',response.data);
                        this.$set(this.$data,'cnt',response.data.length);
                    }
                } catch (error) {
                    console.log(error);
                }
            },

            async readCustomers(){
                try {
                    let response = await Axios.post('/lid/async', {func: 'readCustomers'});
                    if(response.data){
                        this.$set(this.$data,'customers',response.data.arCustomer);
                        this.$set(this.$data,'customersFiltered',response.data.arCustomer);
                        this.$set(this.$data,'obCustomers',response.data.obCustomer);
                    }
                } catch (error) {
                    console.log(error);
                }
            },
        },
        created() {
            this.$store.dispatch('fetchHelpers');


        },
        mounted() {
            this.getSettings();
            this.getList();
            this.readCustomers();
        }
    }
</script>

<style lang="scss">

    .settings {
        position: absolute;
        top: -56px;
        left: 44px;
        z-index: 1500;
    }

    .clearFilter {
        position: absolute;
        top: -56px;
        left: 100px;
        z-index: 1500;
    }
    .createLid {
        display: block;
        position: absolute;
        top: -49px;
        right: 303px;
        z-index: 1500;
        color: #fff;

    }
    .createLid:hover {
        text-decoration: none;
        color: #fff!important;
    }
    .cntItems {
        position: absolute;
        top: -51px;
        left: 240px;
        z-index: 1500;
        display: inline-flex;
        color: #000!important;
    }
    .cntItems span {
        color: #000!important;
    }
    .customersFilter {
        position: absolute;
        top: -56px;
        left: 320px;
        z-index: 1500;
    }

    .h-transfer {
        width: 750px;
    }

    .h-transfer-list {
        min-height: 450px;
    }

    .h-modal .h-notify-content {
        margin: 20px;

    }

    table.tableSection {
        display: table;
        width: 100%;
        background: #fff;
        border-color: grey;
        margin-bottom: 40px;
        border-bottom: 20px solid white;
    }
    table.tableSection thead, table.tableSection tbody {
        float: left;
        width: 100%;
    }
    table.tableSection tbody {
        overflow: auto;
        height: 150px;
    }
    table.tableSection tr {
        width: 100%;
        display: table;
        text-align: left;
    }
    table.tableSection th, table.tableSection td {
        width: 20%;
    }
    table.tableSection th {
        text-align: center;
    }
    table.tableSection td {
        border-left: 1px solid #f4f4f4;
    }


</style>
