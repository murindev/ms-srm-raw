<template>
    <table class="statistic-act-table">
        <tbody>
        <!--lid row-->
        <template v-if="row.lid_id">
            <tr class="statistic-act-table_head">
                <td colspan="2"><span class="red">ЛИД</span>
                    <span></span>
                    <Button v-tooltip content="Привязка Лида к Клиенту"  color="primary" size="xs"
                            @click="$refs.lidLink.style.display = $refs.lidLink.style.display === 'none' ? 'block' : 'none'">&</Button>
                    <Button v-tooltip content="Привязка или создание Лида"  color="primary" size="xs" >+</Button>
                    <Button color="primary" size="xs"
                            @click="volume_searchField({field: 'lid_id', value: row.lid_id}), showVolumes = !showVolumes">A</Button>
                </td>
                <td colspan="1" class="payment_type"><span class="center">Тип цены</span></td><!--Система оплаты-->
                <td colspan="1" class="payment_type"><span class="center">Форма</span></td>
                <td class="payment_type"><span class="center" v-if="row.lid.payment_system === 1">Цена</span><span class="center" v-if="row.lid.payment_system === 2">Обьемы</span></td>
                <td class="payment_type"><span class="center">Нал</span></td>
                <td class="payment_type"><span class="center">Безнал</span></td>
                <td colspan="1" class="grouped"><span class="center">Оплата</span></td>
                <td colspan="1" class="grouped"><span class="center">Пред</span></td>
                <td colspan="1" class="grouped"><span class="center">Пост</span></td>
                <td colspan="1" class="grouped"><span class="center">Постоплата</span></td>
                <td colspan="2" class="payoff_col"><span class="center">Выплаты</span></td>
                <td colspan="1" class="payoff_col"><span class="center">Нал</span></td>
                <td colspan="1" class="payoff_col"><span class="center">Безнал</span></td>
            </tr>
            <tr>
                <td colspan="2" class="ta-left">
                    <span class="h-tag h-tag-blue">{{row.lid_id}}</span>

                    <a target="_blank" :href="`/layout/lid/${row.lid_id}/edit`">
                        <span class="h-tag h-tag-blue">{{row.lid.contract}}</span>
                    </a>
                </td>
                <!--payment_system-->
                <td colspan="1" class="payment_type">
                    <Select v-model="row.lid.payment_system" :datas="lid_paymentSystem" keyName="id" titleName="name" :null-option="false" @change="totalUpdate()"/>
                    <!--@change="lid_eUpdateRow({id:row.lid_id, payment_system: row.lid.payment_system})"-->
                </td>
                <!--customer_payment-->
                <td colspan="1"  class="payment_type">
                    <Select v-model="row.lid.customer_payment" :datas="lid_paymentType"  keyName="id" titleName="name" :null-option="false"  @change="totalUpdate()"

                    />
                    <!--@change="lid_eUpdateRow({id:row.lid_id, customer_payment: row.lid.customer_payment})"-->
                </td>
                <!--price_total-->
                <td class="payment_type">
                    <input v-if="row.lid.payment_system === 1" type="text" v-model="row.lid.price_total" disabled>
                    <input v-if="row.lid.payment_system === 2" type="text" v-model="row.lid.volumes_total" disabled>
                </td>
                <td class="payment_type">
                <!--price_cash-->
                    <input v-if="row.lid.payment_system === 1" type="text" v-model.number="row.lid.price_cash" :disabled="row.lid.customer_payment === 2 || row.lid.payment_system === 2"  @focusout="totalUpdate()">
                    <input v-if="row.lid.payment_system === 2" type="text" v-model.number="row.lid.volumes_cash" :disabled="row.lid.customer_payment === 2 || row.lid.payment_system === 2"  @focusout="totalUpdate()">
                    <!--@focusout="lid_eUpdateRow({id:row.lid_id, price_cash: row.lid.price_cash ? row.lid.price_cash : 0}), updateTotalPrice()"-->
                </td>
                <td class="payment_type">
                <!--price_cashless-->
                    <input v-if="row.lid.payment_system === 1" type="text" v-model.number="row.lid.price_cashless" :disabled="row.lid.customer_payment === 1 || row.lid.payment_system === 2" @focusout="totalUpdate()">
                    <input v-if="row.lid.payment_system === 2" type="text" v-model.number="row.lid.volumes_cashless" :disabled="row.lid.customer_payment === 1 || row.lid.payment_system === 2" @focusout="totalUpdate()">
                    <!--@focusout="lid_eUpdateRow({id:row.lid_id, price_cashless: row.lid.price_cashless ? row.lid.price_cashless : 0}), updateTotalPrice()"-->
                </td>
                <!--payment_rule-->
                <td colspan="1" class="grouped">
                    <Select v-model="row.lid.payment_rule" :datas="h.payment_rules" keyName="id" titleName="name" :null-option="false"
                            @change="lid_onChangePaymentRule"/>
                </td>
                <!--rules_prepayment-->
                <td colspan="1" class="grouped">
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid.rules_prepayment" :disabled="[1,2].indexOf(row.lid.payment_rule) !== -1"
                               @focusout="lid_onChangeCashPercent">
                        <span class="h-tag">%</span>
                    </div>
                </td>
                <td colspan="1" class="grouped">
                    <!--rules_postpayment-->
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid.rules_postpayment" :disabled="true">
                        <span class="h-tag">%</span>
                    </div>
                </td>
                <td colspan="1" class="grouped">
                    <!--payment_condition-->
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid.payment_condition" :disabled="row.lid.payment_rule === 1"
                               @focusout="lid_eUpdateRow({id:row.lid_id, payment_condition: row.lid.payment_condition})">
                        <span class="h-tag">дней</span>
                    </div>

                </td>
                <td class="payoff_col"><span class="top_5_right">Всего:</span></td>
                <td colspan="1" class="payoff_col"><input type="text" disabled :value="4000"></td>
                <td colspan="1" class="payoff_col"><input type="text" disabled :value="2000"></td>
                <td colspan="1" class="payoff_col"><input type="text" disabled :value="2000"></td>

            </tr>

            <tr>
                <td colspan="2"></td>
                <td class="payment_type"></td>
                <td class="payment_type">
                    <span class="top_5_right">
                        <Button color="blue" size="s" @click="finance_byLid(row.lid, row.customer)">Поступления:</Button>
                    </span>
                </td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.receipts_total" disabled @focusout="totalUpdate()"></td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.receipts_cash" disabled  @focusout="totalUpdate()"></td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.receipts_cashless" disabled  @focusout="totalUpdate()"></td>

                <td colspan="1" class="grouped"></td>
                <td colspan="1" class="grouped"><input type="text" v-model.number="row.lid.rules_prepayment_rest"></td>
                <td colspan="1" class="grouped"><input type="text" v-model.number="row.lid.rules_postpayment_rest"></td>
                <td colspan="1" class="grouped"></td>
                <td class="payoff_col">
                    <span class="top_5_right">
                    <Button color="blue"  size="s" @click="finance_byLid(row.lid, row.customer)">Выплачено:</Button>
                    </span>
                </td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="2000"></td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="1000"></td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="1000"></td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="payment_type"></td>
                <td class="payment_type"><span class="top_5_right">Остаток:</span></td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.exp_total" disabled @focusout="totalUpdate()"></td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.exp_cash" disabled @focusout="totalUpdate()"></td>
                <td class="payment_type"><input type="text" v-model.number="row.lid.exp_cashless" disabled @focusout="totalUpdate()"></td>
                <td colspan="1" class="grouped"></td>
                <td colspan="1" class="grouped"><span class="center"><input type="text" disabled :value="10"></span></td>
                <td colspan="1" class="grouped"><span class="center"><input type="text" disabled :value="10"></span></td>
                <td colspan="1" class="grouped"></td>
                <td class="payoff_col"><span class="top_5_right">Остаток:</span></td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="2000"></td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="1000"></td>
                <td colspan="1" class="payoff_col"><input disabled type="text" :value="1000"></td>
            </tr>
        </template>
        <!--statistic row-->
        <template v-else>
            <tr class="statistic-act-table_head">
                <td colspan="2"><span class="red">ЛИД</span>
                    <span></span>
                    <Button v-tooltip content="Привязка Лида к Клиенту"  color="primary" size="xs" icon="h-icon-link"
                            @click="$refs.lidLink.style.display = $refs.lidLink.style.display === 'none' ? 'block' : 'none'"/>
<!--                    <Button v-tooltip content="Привязка или создание Лида"  color="primary" size="xs" icon="h-icon-plus" />-->
                </td>
                <td colspan="2"><span class="center">Тип цены</span></td><!--Система оплаты-->
                <td colspan="2" class="payment_type"><span class="center">Форма оплаты</span></td>
                <td class="payment_type"><span class="center">Нал</span></td>
                <td class="payment_type"><span class="center">Безнал</span></td>
                <td colspan="2" class="grouped"><span class="center">Порядок оплаты</span></td>
                <td colspan="1" class="grouped"><span class="center">Пред</span></td>
                <td colspan="1" class="grouped"><span class="center">Пост</span></td>
                <td colspan="1" class="grouped"><span class="center">Постоплата</span></td>
                <td colspan="1"><span class="center"></span></td>
                <td colspan="1"><span class="center"></span></td>
<!--                <td colspan="1"><span class="center">Поступления</span></td>-->
<!--                <td colspan="1"><span class="center">Остаток</span></td>-->
            </tr>
            <tr>
                <td colspan="2" class="ta-left"> <span class="h-tag h-tag-yellow">Не прикреплен</span></td>
                <td colspan="2">
                    <!--payment_system-->
                    <Select v-model="row.lid_payment_system" :datas="lid_paymentSystem" keyName="id" titleName="name" :null-option="false"
                            @change="statistic_updateRow({id:row.id, lid_payment_system: row.lid_payment_system})"/>
                </td>
                <td colspan="2" class="payment_type">
                    <!--customer_payment Форма оплаты-->
                    <Select v-model="row.lid_customer_payment" :datas="lid_paymentType"  keyName="id" titleName="name" :null-option="false"
                            @change="statistic_updateRow({id:row.id, lid_customer_payment: row.lid_customer_payment})"/>
                </td>
                <td class="payment_type">
                    <input type="text" v-model.number="row.lid_price_cash" :disabled="row.lid_customer_payment === 2"
                           @change="statistic_updateRow({id:row.id, lid_price_cash: row.lid_price_cash})">
                </td>
                <td class="payment_type">
                    <input type="text" v-model.number="row.lid_price_cashless" :disabled="row.lid_customer_payment === 1"
                           @change="statistic_updateRow({id:row.id, lid_price_cashless: row.lid_price_cashless})">
                </td>
                <td colspan="2" class="grouped">
                    <!--payment_rule-->
                    <Select v-model="row.lid_payment_rule" :datas="h.payment_rules" keyName="id" titleName="name" :null-option="false"
                            @change="statistic_onChangePaymentRule"/>
                </td>
                <td colspan="1" class="grouped">
                    <!--lid_rules_prepayment-->
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid_rules_prepayment" :disabled="[1,2].indexOf(row.lid_payment_rule) !== -1"
                               @focusout="statistic_onChangeCashPercent">
                        <span class="h-tag">%</span>
                    </div>
                </td>
                <td colspan="1" class="grouped">
                    <!--lid_rules_postpayment-->
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid_rules_postpayment" :disabled="true">
                        <span class="h-tag">%</span>
                    </div>
                </td>
                <td colspan="1" class="grouped">
                    <!--payment_condition-->
                    <div class="space-between">
                        <input type="text" v-model.number="row.lid_payment_condition" :disabled="row.lid_payment_rule === 1"
                               @focusout="statistic_eUpdateRow({id:row.id, lid_payment_condition: row.lid_payment_condition})">
                        <span style="padding: 8px 0 0 5px">дней</span>
                    </div>
                </td>
                <td></td>
                <td></td>
<!--                <td colspan="1">
                    <input type="text" v-model.number="row.lid_price" :disabled="row.lid_payment_system === 2"
                           @focusout="statistic_eUpdateRow({id:row.id, lid_price: row.lid_price})">
                </td>
                <td colspan="1">
                    <input type="text" v-model.number="row.lid_price_fact" :disabled="row.lid_payment_system === 2"
                           @focusout="statistic_eUpdateRow({id:row.id, lid_price_fact: row.lid_price_fact})">
                </td>
                <td colspan="1">
                    <Button size="s">5000</Button>
                </td>
                <td colspan="1">
                    <span class="h-tag">1500</span>
                </td>-->
            </tr>
        </template>

        <tr>
            <td colspan="15" ref="lidLink" style="display: none">
                <div class="space-between" style="width: 180px; margin: 10px 0">
                    <input type="text" v-model="searchContract" placeholder="№ договора">
                    <Button color="primary" size="s" icon="h-icon-search"
                            @click="
                                                customer_setSearchRow({}),
                                                customer_setHasManyLids({}),
                                                searchLidContract('contract',searchContract)"
                    />
                    <Button color="primary" size="s" icon="h-icon-close"
                            @click="
                                                customer_setSearchRow({}),
                                                customer_setHasManyLids({}),
                                                lid_setSearch({}),
                                                searchContract = ''"
                    />
                </div>
            </td>
        </tr>
        <!--search lid-->
        <tr>
            <td colspan="15">
                <table class="table table statistic-table" v-if="searchContract.length > 3 && lids.length > 0">

                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Договор</th>
                        <th>Менеджер</th>
                        <th>Клиент</th>
                        <th v-if="customer_hasManyLids">Лиды клиента</th>
                        <th>Коммент</th>
                        <th>Действие</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="lid in lids">
                        <td>{{lid.id}}</td>
                        <td>{{lid.contract}}</td>

                        <td v-if="lid.manager_starter !== null">{{h.users[lid.manager_starter].name}}</td>
                        <td v-else>{{lid.manager_starter}}</td>

                        <td v-if="lid.customer">
                            <div v-if="Object.keys(customer_searchRow).length">
                                <div class="stair" v-if="customer_searchRow.name">
                                    <span class="h-tag h-tag-blue">{{customer_searchRow.id}}</span>
                                </div>
                                <div class="stair" v-if="customer_searchRow.name">
                                    <span class="h-tag h-tag-blue">{{customer_searchRow.name}}</span>
                                </div>
                                <div class="stair" v-if="customer_searchRow.organization">
                                    <span class="h-tag h-tag-blue">{{customer_searchRow.organization}}</span>
                                </div>
                                <div class="stair" v-if="customer_searchRow.phone">
                                    <span class="h-tag h-tag-blue">{{customer_searchRow.phone}}</span>
                                </div>
                            </div>
                            <div v-if="Object.keys(customer_searchRow).length">
                                <div class="stair">
                                    <Button color="primary" icon="h-icon-left"
                                            @click="customer_fetchHasManyLids({field: 'customer', value: lid.customer})"
                                    >Показать привязанные лиды</Button>
                                </div>
                            </div>

                            <Button v-else color="red" @click="customer_fetchSearchRow({field:'id', value: lid.customer})">Показать</Button>
                        </td>
                        <td v-else>
                            <span class="h-tag h-tag-blue">не прикреплен</span>
                        </td>

                        <td v-if="customer_hasManyLids">
                            <a target="_blank" v-for="attachedLid in customer_hasManyLids" :href="`/layout/lid/${attachedLid.id}/edit`">
                                <span class="h-tag h-tag-blue">{{attachedLid.contract}}</span>
                            </a>
                        </td>

                        <td style="max-width: 200px;">{{lid.comment}}</td>
                        <td>
                            <div class="stair center">
                                <span v-if="lid.customer" class="h-tag h-tag-red">У Лида уже есть Клиент</span>

                                <Button v-else color="primary" @click="attachLid(lid.id)">Прикрепить</Button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <!--Продолжить?-->
                    <!--                <Poptip v-if="lid.customer" content="У Лида уже есть Клиент " @confirm="attachLid(lid.id)">-->
                    <!--                    x                         <Button color="primary">Прикрепить</Button>-->
                    <!--                </Poptip>-->
                </table>
            </td>
        </tr>

        <tr><td v-for="i in 15" class="slice"></td></tr>
        <tr v-if="showVolumes">
            <td colspan="15"><volumes-check :h="h"/></td>
        </tr>
        </tbody>
    </table>




</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import {IAct, IPropertyType} from "../../../types/act";
    import {ICrmStatistic} from "../../../types/crm.statistic";
    const Lid = namespace('Lid')
    const CrmStatistic = namespace('CrmStatistic')
    const Customer = namespace('Customer')
    const Volume = namespace('Volume')
    const Finance = namespace('Finance')
    import { bus } from "../../../bus";
    import * as Globals from "../../../types/Global";
    import {ICustomer} from "../../../types/customer";
    import {ILid} from "../../../types/lid";
    import {IFieldValue} from "../../../types/Global";
    import {IVolume} from "../../../types/volume";
    import VolumesCheck from "./VolumesCheck.vue";
    import {IFinance} from "../../../types/finance";

    @Component({
        components: {VolumesCheck}
    })
    export default class CrmStatisticLid extends Vue {
        @Prop() public h!: Globals.IHelper
        get row() {return this.row_statistic}
        set row(value) {this.setTableRow(value)}
        public showVolumes = false;



/*
        @Watch('finance_receipts') onFinanceBillingChange(){
            this.totalUpdate()
        }
        @Watch('volume_implementsCost') onVolumeBillingChange(){
            this.totalUpdate()
        }
*/

//Customer
        @Customer.State('searchRow') public customer_searchRow!: ICustomer
        @Customer.Mutation('setSearchRow') customer_setSearchRow!: (data: ICustomer) => void
        @Customer.Action('fetchSearchRow') customer_fetchSearchRow!: (data: ICustomer) => void

        @Customer.State('hasManyLids') customer_hasManyLids!: ILid[]
        @Customer.Mutation('setHasManyLids') customer_setHasManyLids!: (payload: ILid[]) => void
        @Customer.Action('fetchHasManyLids') customer_fetchHasManyLids!: (data: IFieldValue) => void

//CrmStatistic
        @CrmStatistic.State('row') public row_statistic!: ICrmStatistic
        @CrmStatistic.Action('updateRow') public statistic_updateRow!: (payload: ICrmStatistic) => void

        /** @this CrmStatistic.eUpdateRow */
        @CrmStatistic.Action('eUpdateRow') public statistic_eUpdateRow!: (payload: ICrmStatistic) => void
        @CrmStatistic.Mutation setTableRow!: (payload: object) => void

//Lid
        public searchContract: string = ''
        @Lid.State('search') public lids!: ILid[]
        @Lid.Mutation('setSearch') lid_setSearch!:(data: ILid) => void
        @Lid.Action('fetchFieldSearch') public lidFieldSearch!: (payload: object) => void

        @Lid.State('row') public lid_row!: ILid
        @Lid.Action('getRow') lid_getRow!: (data: ILid) => void

        /** @link Lid.fetchUpdateRow */
        @Lid.Action('fetchUpdateRow') lid_fetchUpdateRow!: (data: ILid) => void
        @Lid.Action('eUpdateRow') lid_eUpdateRow!: (data: ILid) => void

        @Lid.State('paymentType') lid_paymentType!:{ id: number, name: string }[]
        @Lid.State('paymentSystem') lid_paymentSystem!:{ id: number, name: string }[]
//Finance
        @Finance.State('table') finance_table!: IFinance[]
        @Finance.Action('readTable') finance_readTable!: (data: IFieldValue) => void
        @Finance.Getter('receipts') public finance_receipts!: {cash:number,cashless:number, total: number}
        @Finance.Getter('payoff') public finance_Payoff!: {cash:number,cashless:number, total: number}
//Volume


        @Volume.Getter('implements') public volume_implementsCost!: {cash:number,cashless:number, total: number}
        @Volume.Getter('searchArray') public volume_searchArray!: IVolume[]
        @Volume.Action('searchField') public volume_searchField!: (data: IFieldValue) => void



        public messages(message: string, type: string, timeout = 0){
            // @ts-ignore
            this.$Message[type](message,timeout)
        }

        statistic_onChangePaymentRule(){
            let cash = 100, cashless = 0

            switch (this.row.lid_payment_rule) {
                case 1 :
                    cash = 100
                    cashless = 0
                    break
                case 2:
                    cash = 0
                    cashless = 100
                    break
                case 5:
                    cash = 100
                    cashless = 0
                    break
                case  6 :
                    cash = 100
                    cashless = 0
                    break
            }
            this.statistic_updateRow({
                id: this.row.id,
                lid_payment_rule: this.row.lid_payment_rule,
                lid_rules_prepayment: cash,
                lid_rules_postpayment: cashless,
            })
        }
        statistic_onChangeCashPercent(){
            this.statistic_updateRow({
                id: this.row.id,
                lid_payment_rule: this.row.lid_payment_rule,
                lid_rules_prepayment: this.row.lid_rules_prepayment,
                lid_rules_postpayment: 100 - Number(this.row.lid_rules_prepayment),
            })
        }

        lid_onChangePaymentRule(){
            let cash = 100, cashless = 0

            switch (this.row?.lid?.payment_rule) {
                case 1 :
                    cash = 100
                    cashless = 0
                    break
                case 2:
                    cash = 0
                    cashless = 100
                    break
                case 5:
                    cash = 100
                    cashless = 0
                    break
                case  6 :
                    cash = 100
                    cashless = 0
                    break
            }
            this.lid_eUpdateRow({
                id: Number(this.row.lid_id),
                payment_rule: this.row?.lid?.payment_rule,
                rules_prepayment: cash,
                rules_postpayment: cashless,
            })
        }

        lid_onChangeCashPercent(){
            this.lid_eUpdateRow({
                id: Number(this.row.lid_id),
                payment_rule: this.row?.lid?.payment_rule,
                rules_prepayment: this.row?.lid?.rules_prepayment,
                rules_postpayment: 100 - Number(this.row?.lid?.rules_prepayment),
            })
        }




        public searchLidContract(field: string,value: any){
            this.lidFieldSearch({field:field,value:value})
        }


        finance_byLid(lid: ILid, customer: ICustomer){
            bus.$emit('finance_byLid', lid, customer)
        }






        async checkLidFields(){

            if(!this.row.lid_id){
                return false
            }

            let result = true
            let cp = this.row.lid?.customer_payment, ps = this.row.lid?.payment_system



            if(ps === 1 && cp === 1){// fix 1 + cash 1
                if(this.row.lid && Number(this.row.lid?.price_cashless) > 0 ){
                    this.messages('Что бы перейти на <b>наличные</b> , нужно исправить все <b>безналичные</b> платежи  (<b class="red">цена безнал больше нуля </b>) - <b>поле изменено на смешанную форму</b>','error')
                    cp = 3
                    result = false
                }
                if(this.volume_implementsCost.cashless > 0) {
                    this.confirm('У этого Лида существуют <b class="red">Акты</b> с БЕЗНАЛИЧНЫМ расчетом', 'Показать?')
                    cp = 3
                    result = false
                }
                if(this.finance_receipts.cashless > 0) {
                    this.confirmFinance('У этого Лида существуют <b class="red">Поступления/Расходы</b> с БЕЗНАЛИЧНЫМ расчетом', 'Показать?')
                    cp = 3
                    result = false
                }

                /* price_cash > 0
                * price_cashless === 0
                * price_total -> recalculate
                * fin cash >=0
                * fin cashless === 0S
                * */

            }
            else if(ps === 1 && cp === 2){// fix 1 + cashless 2
                console.log('fix 1 + cashless 2');
                if(this.row.lid && Number(this.row.lid?.price_cash) > 0){
                    this.messages('Что бы перейти на безнал, нужно исправить все <b>наличные</b> платежи  (<b class="red">цена нал больше нуля </b>) - <b>поле изменено на смешанную форму</b>','error')
                    cp = 3
                    result = false
                }
                if(this.volume_implementsCost.cash > 0) {
                    this.confirm('У этого Лида существуют <b class="red">Акты</b> с НАЛИЧНЫМ расчетом', 'Показать?')
                    cp = 3
                    result = false
                }
                if(this.finance_receipts.cash > 0) {
                    this.confirmFinance('У этого Лида существуют <b class="red">Поступления/Расходы</b> с НАЛИЧНЫМ расчетом', 'Показать?')
                    cp = 3
                    result = false
                }
            }
            else if(ps === 1 && cp === 3){// fix 1 + mix 3
                console.log('fix 1 + mix 3');
                // price_total -> recalculate
            }
            else if(ps === 2){// acc 2 + cash 1
/*                if (Number(this.row.lid?.price_cash) > 0 || Number(this.row.lid?.price_cashless) > 0){
                    this.messages('Что бы перейти на <b>Накопительную систему</b>, нужно  <b class="red">обнулить поля нал и безнал</b>  - <b>поле изменено на Фиксированную систему</b>','error')
                    ps = 1
                    result = false
                }*/
            }
            else if(ps === 2 && cp === 1){// acc 2 + cash 1

            }
            else if(ps === 2 && cp === 2){// acc 2 + cashless 2

            }
            else if(ps === 2 && cp === 3){// acc 2 + mix 3

            }

            this.lid_eUpdateRow({id:this.row.lid_id, customer_payment: cp})
            this.lid_eUpdateRow({id:this.row.lid_id, payment_system: ps})

            return result


        }


        async totalUpdate() {

            await this.volume_searchField({field: 'lid_id', value: this.row.lid_id})
            await this.finance_readTable({field: 'lid_id', value: this.row.lid_id})
            let rules_prepayment_rest = 0, rules_postpayment_rest = 0

            if (this.row.lid && await this.checkLidFields()) {
                let exp_total = 0, exp_cash = 0, exp_cashless = 0;
                let price_total = Number(this.row.lid.price_cash) + Number(this.row.lid.price_cashless)
                let percent = 0
                if(this.row.lid.payment_system === 1) {
                    percent = price_total/100

                    exp_total = Number(this.row.lid.price_total) - this.finance_receipts.total
                    exp_cash = Number(this.row.lid.price_cash) - this.finance_receipts.cash
                    exp_cashless = Number(this.row.lid.price_cashless) - this.finance_receipts.cashless
                } else if(this.row.lid.payment_system === 2)  { // накопительная
                    percent = this.volume_implementsCost.total/100
                    exp_total = Number(this.volume_implementsCost.total) - this.finance_receipts.total
                    exp_cash = Number(this.volume_implementsCost.cash) - this.finance_receipts.cash
                    exp_cashless = Number(this.volume_implementsCost.cashless) - this.finance_receipts.cashless
                }
                console.log('percent',percent);
                console.log('this.finance_receipts.total',this.finance_receipts.total);
                rules_prepayment_rest = this.finance_receipts.total  ? Number(this.finance_receipts.total/percent) : 0
                rules_postpayment_rest = Number(100 - rules_prepayment_rest ? rules_prepayment_rest : 0)
                console.log('rules_prepayment_rest',rules_prepayment_rest);
                console.log('rules_postpayment_rest',rules_postpayment_rest);
                let obj = {
                    id: this.row.lid.id,
                    payment_system: this.row.lid.payment_system,
                    customer_payment: this.row.lid.customer_payment,
                    price_total: price_total,
                    price_cash: this.row.lid.price_cash,
                    price_cashless: this.row.lid.price_cashless,
                    payment_rule: this.row.lid.payment_rule,
                    rules_prepayment: this.row.lid.rules_prepayment,
                    rules_postpayment: this.row.lid.rules_postpayment,
                    rules_prepayment_rest: Number(rules_prepayment_rest),
                    rules_postpayment_rest: Number(rules_postpayment_rest),
                    payment_condition: this.row.lid.payment_condition,
                    receipts_total: this.finance_receipts.total,
                    receipts_cash: this.finance_receipts.cash,
                    receipts_cashless: this.finance_receipts.cashless,
                    volumes_total: this.volume_implementsCost.total,
                    volumes_cash: this.volume_implementsCost.cash,
                    volumes_cashless: this.volume_implementsCost.cashless,
                    exp_total: exp_total,
                    exp_cash: exp_cash,
                    exp_cashless: exp_cashless,
                }
                this.lid_eUpdateRow(obj)
            }

        }




        confirm(message:string, title: string) {
            // @ts-ignore
            this.$Confirm(title, message).then(() => {this.showVolumes = true;}).catch(() => {/* this.$Message.error('cancel');*/});
        }

        confirmFinance(message: string, title: string) {
            // @ts-ignore
            this.$Confirm(title, message).then(() => {
                if (this.row.lid && this.row.customer) {
                    this.finance_byLid(this.row.lid, this.row.customer)

                }
            })
                .catch(() => {/* this.$Message.error('cancel');*/
                });
        }

        updateTotalPrice() {
            this.lid_eUpdateRow({
                    id: Number(this.row.lid_id),
                    price_total: Number(this.row.lid?.price_cash) + Number(this.row.lid?.price_cashless)
                }
            )
        }




        created(){

            this.totalUpdate()
        }




    }
</script>
