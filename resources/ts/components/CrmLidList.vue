<template>
    <div>
        <DateFullRangePicker class="roof" style="left: 50px" v-model="period" :layout="tableHead[0].layout"
                             :startWeek="tableHead[0].startWeek" @input="filterData()"/>
        <Button color="red" v-if="filterBtn" class="settings roof" style="left: 303px;" @click="flushFilter()">Сбросить фильтр</Button>
        <span class="h-tag h-tag-blue roof" style="left: 480px; margin-top: 4px;">{{total}} строк {{page}} стр.</span>

        <div class="statistic-table-container" @scroll="onScroll">
            <table class="statistic-table">

                <thead>
                <tr>
                    <th v-for="i in tableHead"
                        v-if="i.show && i.colspan && i.colspan !== 0"
                        :style="theadStyle(i)"
                        :class="i.class"
                        :colspan="i.colspan"
                    >
                        <span v-if="getShowCols(i.key)" @dblclick="setShowCols(i.key, false)">{{i.name}}</span>
                        <span v-else @click="setShowCols(i.key, true)" class="statistic-table-toggle_col" v-tooltip
                              :content="i.name">{{i.short}}</span>
                    </th>
                </tr>
                <tr>
                    <th v-for="(obj,key) in tableHead"
                        v-if="obj.show &&['period','time'].indexOf(obj.key) === -1"
                        :class="obj.class"
                        :style="theadStyleNth2(obj)">


                        <input v-if="obj.type === 'text'" type="text" v-model="obj.v" @keyup="filterData()">
                        <input v-if="obj.type === 'number'" type="text" v-model.number="obj.v" @keyup="filterData()">
                        <Select v-if="obj.type === 'select'"  v-model="obj.v" :datas="obj.list" keyName="id" titleName="name" @change="filterData()"/>

                    </th>
                </tr>
                </thead>


                <tbody>
                <tr
                        v-for="(row, rowKey) in lid_table"
                        :id="'tr'+row.id && row.show"
                        :class="row.call_status ? callStatus.find(o => o.id === row.call_status).class : ''"
                        :style="row.editing ? 'background: #ff000014' : ''"

                >
                    <!-- @dblclick="editRow(row)"-->

                    <td v-for="th in tableHead" v-if="th.show" v-html="th.text(row)"></td>
                </tr>
                </tbody>

            </table>
        </div>

    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import * as Globals from "../types/Global";
    import {bus} from "../bus";
    import {ILid} from "../types/lid";
    import {IArrHelper} from "../types/Global";
    import lid from "../store/modules/lid";
    const Lid = namespace('Lid')

    @Component
    export default class CrmLidList extends Vue {
        @Prop(Number) manager!: number
        @Prop() h!: IArrHelper

        public theadSettingsLid: Globals.IKeyValue = {}

        public filterBtn: boolean = false
        public period = {
            start:false,
            end: false,
            type: 'customize'
        }

        @Lid.State('page') page!: number
        @Lid.State('total') total!: number
        @Lid.State('last_page') last_page!: number
        @Lid.State('table') lid_table!: ILid[]
        @Lid.State('paymentType') lid_paymentType!: { id: number, name: string }[]
        @Lid.Getter('getPaymentType') lid_getPaymentType!: { id: number, name: string }[]
        @Lid.State('paymentSystem') lid_paymentSystem!: { id: number, name: string }[]
        @Lid.Action('initTable') lid_initTable!: (payload: string) => void
        @Lid.Action('fetchConcatTable') lid_fetchConcatTable!: (payload: string) => void

        public tableHead = [

            {
                key: 'period', type:'', name: 'period', short: 'id', width: 70, v: '', d: null, colspan: 0, show: false, q:'&filter',
                period: {start:false, end: false, type: 'customize'}, datePicker: {startWeek: 1, layout   : ['year', 'quarter', 'month', 'week', 'customize']}
            },
            {
                key: 'id', type:'text' , name: 'ID', short: 'id', width: 70, v: null, d: null, colspan: 1, show: true, q:'&filter', alt: 'row',
                text: (obj:ILid) => obj.id
            },{
                key: 'contract', type:'text', name: 'Договор', short: 'Д', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter',
                text: (obj:ILid) => `<a target="_blank" href="/layout/lid/${obj.id}/edit">${obj.contract}</a>`
            },{
                key: 'manager', type:'select', name: 'Менеджер', short: 'М', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter',
            },{
                key: 'site', type:'select', name: 'Сайт', short: 'С', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter' ,
                list: this.h.companies,
                text: (obj:ILid) => this.h.companies.find(o => o.id === obj.site)?.name
            },{
                key: 'manager_starter', type:'select', name: 'Начавший лид', short: 'Н', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter',
                list: this.h.users.filter(o => [1,2,4].indexOf(Number(o.role_id)) !== -1),
                text: (obj:ILid) => this.h.users.find(o => o.id === obj.manager_starter)?.name
            },{
                key: 'servicing', type:'select', name: 'Обслуживание', short: 'О', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter',
                list: this.h.servicing,
                text: (obj:ILid) => this.h.servicing.find(o => o.id === obj.servicing)?.name
            },{
                key: 'client.name', type:'text', name: 'Клиент', short: 'К', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter', alt:'customer_name',class: 'customer-thead',
                text: (obj:ILid) => obj.client?.name
            },{
                key: 'client.phone', type:'text', name: 'Телефон', short: 'Т', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter', alt:'customer_phone',class: 'customer-thead',
                text: (obj:ILid) => obj.client?.phone
            },{
                key: 'client.status', type:'select', name: 'Ф/Ю', short: 'Ф', width: 50, v: null, d: null, colspan: 1, show: true, q:'&filter', alt:'customer_status',class: 'customer-thead',
                list: this.h.customer,
                text: (obj:ILid) => {
                    if(obj.client?.status === 1) {
                        return '<span class="h-tag h-tag-blue">Ф</span>'
                    } else if(obj.client?.status === 2) {
                        return '<span class="h-tag h-tag-green">Ю</span>'
                    } else {
                        return ''
                    }
                }
            },{
                key: 'client.organization', type:'text', name: 'Организация', short: 'О', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter', alt:'customer_organization',class: 'customer-thead',
                text: (obj:ILid) => obj.client?.organization || obj.client?.organization_long,

            },{
                key: 'date_from', type:'type', name: 'date_from', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'date_to', type:'type', name: 'date_to', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'contract_transferred', type:'type', name: 'Передан', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'contract_signed', type:'type', name: 'Подписан', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'contract_file', type:'type', name: 'Файл', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'action', type:'type', name: 'action', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'date_start', type:'type', name: 'Начало', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'action_date', type:'type', name: 'action_date', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'action_note', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'customer_status', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'customer_payment', type:'select', name: 'Тип оплаты', short: 'ТО', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter',
                list: [{id: 1, name: 'Наличная'}, {id: 2, name: 'Безналичная'}, {id: 3, name: 'Смешанная'}],
                text: (obj : ILid) => [{id: 1, name: 'Наличная'}, {id: 2, name: 'Безналичная'}, {id: 3, name: 'Смешанная'}].find(o => o.id === obj.customer_payment)?.name
            },{
                key: 'payment_rule', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'rules_prepayment', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'rules_postpayment', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'rules_prepayment_rest', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'rules_postpayment_rest', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'payment_condition', type:'text', name: 'Постоплата', short: 'П', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter',
                text:(obj: ILid) => obj.payment_condition
            },{
                key: 'payment_system', type:'select', name: 'Тип цены', short: 'T', width: 150, v: null, d: null, colspan: 1, show: true, q:'&filter',
                list:  [{id: 1, name: 'Фиксированная'}, {id: 2, name: 'Накопительная'}],
                text:(obj: ILid) => [{id: 1, name: 'Фиксированная'}, {id: 2, name: 'Накопительная'}].find(o =>o.id === obj.payment_system )?.name
            },{
                key: 'comment', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'balance', type:'number', name: 'Баланс', short: 'Б', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead',
                text: (obj: ILid) => this.priceFormat(Number(obj.balance))
            },{
                key: 'volumes_total', type:'number', name: 'Обьемы', short: 'О', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead',
                text: (obj: ILid) => this.priceFormat(Number(obj.volumes_total))
            },{
                key: 'price_total', type:'number', name: 'Цена', short: 'Ц', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_darker',
                text: (obj: ILid) => obj.payment_system === 1 ?
                    '<span class="h-mark h-tag-blue">'+this.priceFormat(Number(obj.price_total))+'</span>' :
                    '<span class="h-mark h-tag-yellow">'+this.priceFormat(Number(obj.volumes_total))+'</span>'
            },{
                key: 'price_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'price_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'kpi', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'receipts_total', type:'number', name: 'Поступ-я', short: 'П', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_darker',
                text: (obj: ILid) => this.priceFormat(Number(obj.receipts_total))
            },{
                key: 'receipts_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'receipts_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'exp_total', type:'number', name: 'Остаток', short: 'О', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_darker',
                text: (obj: ILid) => this.priceFormat(Number(obj.exp_total))
            },{
                key: 'exp_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'exp_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'volumes_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'volumes_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'payoff_total', type:'number', name: 'Выплаты', short: 'В', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_lighter',
                text: (obj: ILid) => this.priceFormat(Number(obj.payoff_total))
            },{
                key: 'payoff_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'payoff_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'payed_off_total', type:'number', name: 'Выплачено', short: 'В', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_lighter',
                text: (obj: ILid) => this.priceFormat(Number(obj.payed_off_total))
            },{
                key: 'payed_off_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'payed_off_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'to_payoff_total', type:'number', name: 'Остаток', short: 'О', width: 75, v: null, d: null, colspan: 1, show: true, q:'&filter', class:'money_thead_lighter',
                text: (obj: ILid) => this.priceFormat(Number(obj.to_payoff_total))
            },{
                key: 'to_payoff_cash', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'to_payoff_cashless', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter', class:'money_thead',
            },{
                key: 'created_at', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },{
                key: 'updated_at', type:'type', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: false, q:'&filter'
            },
        ]

        // table
        // table.cols


        valuesView(obj:ILid,key: string){
            let keys = key.split('.');
// @ts-ignore
            return  keys.reduce((p:object, c) => p && p.hasOwnProperty(c) ? p[c] : null, obj);




        }
        // eof table.cols
        // decorate
        priceFormat(val:number){
            return Number(val).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').slice(0, - 3)
        }
        // eof decorate

        // filter

        // eof filter




        getShowCols(key:string){
            return Object.keys(this.theadSettingsLid).indexOf(key) !== -1 ? this.theadSettingsLid[key] : true
        }
        theadStyle(i:any){
            if (this.getShowCols(i.key)){
                return i.width ? 'max-width:' + i.width + 'px; width:' + i.width + 'px; min-width:' + i.width + 'px' : 'width:auto'
            } else {
                return 'max-width:16px; width:16px; position:relative overflow: hidden: text-align: center'
            }

        }

        theadStyleNth2(i:any){
            if (this.getShowCols(i.key)){
                return i.width ? 'max-width:' + i.width + 'px; width:' + i.width + 'px; min-width:' + i.width + 'px' : 'width:auto'
            } else {
                return 'max-width:16px; width:16px; position:relative overflow: hidden: text-align: center'
            }
        }

        setShowCols(key:string, value:boolean){
            this.$set(this.$data.theadSettingsLid,key,value)
            localStorage.setItem('theadSettingsLid',JSON.stringify(this.theadSettingsLid))
        }
        getString: string = '?sort=-date'
        async filterData(){
            this.getString = '?sort=-date'
            let str = '';

            this.tableHead.forEach(th => {
                if(th.key === 'period' && this.period.start && this.period.end){
                    th.v = [this.period.start,this.period.end].join(',')
                }

                if(th.v){
                    let key = th.hasOwnProperty('alt') ? th.alt : th.key
                    str +=  th.q + `[${key}]=${th.v}`
                }
            })

            this.$set(this.$data,'getString',await (this.getString + str))
            await this.lid_initTable(this.getString);
            this.getString === '?sort=-date' ? this.filterBtn = false : this.filterBtn = true

        }

        // async filterDataPeriod(){
        //     await  this.tableHead.map(o => {
        //         if(o.key === 'period'){
        //             o.v = [this.period.start,this.period.end,].join(',')
        //         }
        //     })
        //     await this.filterData();
        //
        // }

        flushFilter(){
            for(let index in this.tableHead) {
                if (this.tableHead.hasOwnProperty(index)) {
                    // @ts-ignore
                    this.tableHead[index].v = this.tableHead[index].d
                }
            }
            this.period.start = false;
            this.period.end = false;
            this.filterData()
        }
        async onScroll(event:any){

            let container = event.target

            // console.log(container.scrollHeight , container.offsetHeight , container.scrollTop);
            if(container.scrollHeight <= container.offsetHeight + container.scrollTop) {
                console.log('scrl');
                if(this.page < this.last_page && this.page === this.pageNr){
                    console.log('scrl 2');
                    this.$set(this.$data,'pageNr', await (this.pageNr + 1))
                    await this.lid_fetchConcatTable(this.getString + '&page='+ (this.page + 1));
                }
            }
        }

        pageNr: number = 1

        created() {

            this.lid_initTable(this.getString);
            // this.fetchQueryBuilder('/vuex/crm-statistic')

            bus.$on('resetTableFilter', (val:boolean) => {
                this.flushFilter()
            });

        }

    }
</script>
