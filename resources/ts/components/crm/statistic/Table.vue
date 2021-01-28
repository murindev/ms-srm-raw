<template>
    <div>
        <div class="statistic-table-container">
            <table class="statistic-table">

                <thead>
                <tr>
                    <th v-for="i in tableHead"
                        v-if="i.colspan && i.colspan !== 0"
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
                    <th v-for="(obj,key) in tableHead" v-if="obj.key !== 'time'" :class="obj.class"
                        :style="theadStyleNth2(obj)">
<!--Статусы-->
                        <input v-if="obj.key === 'id' && getShowCols(obj.key)" type="text" v-model.number="obj.v" @input="filterData()">

                        <DatePicker v-else-if="obj.key === 'date' && getShowCols(obj.key)" v-model="obj.v" :placeholder="'Дата'" @input="filterData()"/>

                        <Select v-else-if="obj.key === 'site' && getShowCols(obj.key)" v-model="obj.v" :datas="h.companies" keyName="id" titleName="name"/>

                        <Select v-else-if="obj.key === 'manager' && getShowCols(obj.key)" v-model="obj.v" :datas="obj.select()" keyName="id" titleName="name"/>

                        <Select v-else-if="obj.key === 'call_status' && getShowCols(obj.key)" v-model="obj.v" :datas="callStatus" keyName="id" titleName="name"/>

                        <Select v-else-if="obj.key === 'app_status' && getShowCols(obj.key)" v-model="obj.v" :datas="appStatus" keyName="id" titleName="name"/>


                        <input v-else-if="obj.key === 'cause' && getShowCols(obj.key)" type="text" v-model="obj.v">
<!--Клиент-->
                        <input v-else-if="obj.key === 'phone' && getShowCols(obj.key)" type="text" v-model="obj.v" style="width: 100%;">

                        <Select v-else-if="obj.key === 'customer_status' && getShowCols(obj.key)"  v-model="obj.v" :datas="customer_status" keyName="id" titleName="short"></Select>

                        <input v-else-if="obj.key === 'name' && getShowCols(obj.key)" type="text" v-model="obj.v" style="width: 100%;">

                        <input v-else-if="obj.key === 'address' && getShowCols(obj.key)" type="text" v-model="obj.v">
                        <input v-else-if="obj.key === 'organization' && getShowCols(obj.key)" type="text" v-model="obj.v">

                        <input v-else-if="obj.key === 'email' && getShowCols(obj.key)" type="text" v-model="obj.v" >
<!--Обьект-->
                        <!--Тип обьекта-->
                        <Select v-else-if="obj.key === 'property_type' && getShowCols(obj.key)" v-model="obj.v" :datas="act_propertyType" keyName="id" titleName="name"/>
                        <!--Предмет работ-->
                        <Select v-else-if="obj.key === 'pest' && getShowCols(obj.key)" v-model="obj.v" :datas="h.pests_asc" keyName="id" :filterable="true"
                                titleName="name" style="min-width: 190px; margin-right: 4px;"/>

                        <!--Площадь-->
                        <!--Пл. факт-->
                        <!--Единица площади-->
                        <!--Методы-->

<!--Финансы-->
                        <!--Нал_сумма-->
                        <input type="text" v-else-if="obj.key === 'cash' && getShowCols(obj.key)"  v-model="obj.v">
                        <!--**Нал tag-->
                        <Checkbox v-else-if="obj.key === 'cash_tag' && getShowCols('cash')"  v-model="obj.v"/>
                        <!--Б_н_сумма-->
                        <input type="text" v-else-if="obj.key === 'cashless' && getShowCols(obj.key)"  v-model="obj.v">
                        <!--**Безнал tag-->
                        <Checkbox v-else-if="obj.key === 'cashless_tag' && getShowCols('cashless')"  v-model="obj.v"/>
                        <!--**Карта-->
                        <Checkbox v-else-if="obj.key === 'card_payment' && getShowCols(obj.key)"  v-model="obj.v"/>
                        <!--Мастер+-->
                        <input type="text" v-else-if="obj.key === 'overpay' && getShowCols(obj.key)"  v-model="obj.v">
                        <!--Субчику-->
                        <input type="text" v-else-if="obj.key === 'contractor_fee' && getShowCols(obj.key)"  v-model="obj.v">


                        <!--Цена ф.-->



                    <!--Отказ-->



<!--                        <input v-else-if="obj.key === 'cashless_tag' && getShowCols(obj.key)" type="number" v-model="obj.v"
                               :style="obj.width ? 'max-width:' + obj.width + 'px' : 'width:auto' && getShowCols(obj.key)">-->


<!--                        <Select v-else-if="obj.key === 'card_payment' && getShowCols(obj.key)" v-model="obj.v" :datas="cardPayment" keyName="value" titleName="name"></Select>-->



                        <input v-else-if="obj.key === 'contract' && getShowCols(obj.key)" type="text" v-model="obj.v" style="width: 100%;">

                        <input v-else-if="obj.key === 'implement_date' && getShowCols(obj.key)" type="text" v-model="obj.v"
                               style="width: 100%;">

                        <Button v-else-if="obj.key === 'set' && getShowCols(obj.key)" class="h-btn-s" icon="h-icon-close" disabled></Button>

                        <template v-else></template>
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr
                    v-for="(row, rowKey) in filterData()"
                    :id="'tr'+row.id"
                    :class="row.call_status ? callStatus.find(o => o.id === row.call_status).class : ''"
                    :style="row.editing ? 'background: #ff000014' : ''"
                    @dblclick="editRow(row)"
                    @click="editRow(row)"

                >


                    <template v-for="th in tableHead">
<!--Статусы-->
                        <!--id 1-->
                        <td v-if="th.key === 'id'"  class="indicator-nest">
                            <div class="indicator" v-if="row.editing">
                                <span class="h-tag h-tag-yellow">Редактирует {{h.users[row.editing].name}} </span>
                            </div>
                            <div v-if="getShowCols('id')">
                                {{row.id}}
                                <template v-if="manager === 1"> - {{rowKey}}</template>
                            </div>
                        </td>
                        <!--date 1-->
                        <td v-if="th.key === 'date'" class="no-wrap" rel="datetime">
                            <div v-if="getShowCols('date')">
                                <span class="h-tag h-tag-blue">{{row.date}}</span>
                                <span class="h-tag h-tag-primary">{{row.time}}</span>
                            </div>
                        </td>
                        <!--site 1-->
                        <td v-if="th.key === 'site'">
                            <div v-if="row.site && getShowCols('site')">{{h.companies[row.site].name}}</div>
                        </td>
                        <!--manager 1-->
                        <td v-if="th.key === 'manager'">
                            <div v-if="getShowCols('manager')">
                                <template v-if="row.manager " class="no-wrap">
                                    <span v-if="row.manager === 9999">g Семен и Co</span>
                                    <span v-else>{{h.users[Number(row.manager)].name}}</span>
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
                        <!--call_status 1-->
                        <td v-if="th.key === 'call_status'">
                            <div v-if="getShowCols('call_status')">
                                <template v-if="row.call_status && callStatus">
                                    <span class="h-tag " :class="callStatus.find(o => o.id === row.call_status).design">
                                        {{showCallStatus(Number(row.call_status))}}
                                    </span>
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
                        <!--app_status 1-->
                        <td v-if="th.key === 'app_status'">
                            <div v-if="getShowCols('app_status')">
                                <template v-if="row.app_status">
                                    <span class="h-tag " :class="appStatus.find(o => o.id === row.app_status).design">
                                            {{showAppStatus(Number(row.app_status))}}
                                    </span>
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
                        <!--act.comment / cause 1-->
                        <td v-if="th.key === 'cause'">
                            <div v-if="getShowCols('cause')">
                                <template v-if="row.act && row.act.comment" style="max-width: 200px" class="act-cell">
                                    <div>{{row.act.comment}}</div>
                                </template>
                                <template v-else style="max-width: 200px">
                                    <div>{{row.cause}}</div>
                                </template>
                            </div>
                        </td>
                        <!--phone 1-->
                        <td v-if="th.key === 'phone'">
                            <div v-if="getShowCols('phone')">
                                <template v-if="row.act" class="act-cell">
                                    <template v-if="row.act.phone && row.act.phone.length === 11">
                                        <the-mask :value="row.act.phone" mask="+#(###) ### ## ##" disabled style="width: 110px"/>
                                    </template>
                                    <template v-else>{{row.act.phone}}</template>

                                    <template v-if="row.act.phone_ext">
                                        <the-mask :value="row.act.phone_ext" mask="+#(###) ### ## ##" disabled style="width: 110px"/>
                                    </template>
                                </template>
                                <template v-else>
                                    <template v-if="row.phone && row.phone.length === 11">
                                        <the-mask :value="row.phone" mask="+#(###) ### ## ##" disabled style="width: 110px"/>
                                    </template>
                                    <template v-else>{{row.phone}}</template>

                                    <template v-if="row.phone_ext">
                                        <the-mask :value="row.phone_ext" mask="+#(###) ### ## ##" disabled style="width: 110px"/>
                                    </template>
                                </template>
                            </div>
                        </td>
<!--Клиент-->
                        <!--customer_status 1-->
                        <td v-if="th.key === 'customer_status'">
                            <div v-if="getShowCols('customer_status')">
                                <span v-if="row.customer && row.customer.status === 2" class="h-tag h-tag-primary">Ю</span>
                                <span v-else-if="row.customer && row.customer.status === 1" class="h-tag">Ф</span>
                                <template v-else></template>
<!--                                <template v-if="row.customer" class="act-cell">-->
<!--                                    <span v-if="row.customer.status" class="h-tag h-tag-primary">Ю</span>-->
<!--                                    <span v-else class="h-tag">Ф</span>-->
<!--                                </template>-->
<!--                                <template v-else-if="row.customer_status">-->
<!--                                    <span v-if="row.customer_status" class="h-tag h-tag-primary">Ю</span>-->
<!--                                    <span v-else class="h-tag">Ф</span>-->
<!--                                </template>-->
<!--                                <template v-else></template>-->
                            </div>
                        </td>
                        <!--name 1-->
                        <td v-if="th.key === 'name'">
                            <div v-if="getShowCols('name')">
                                <template v-if="row.act" class="act-cell">{{row.act.contact_name}}</template>
                                <template v-else>{{row.name}}</template>
                            </div>
                        </td>
                        <!--address 1-->
                        <td v-if="th.key === 'address'">
                            <div v-if="getShowCols('address')">
                                <template v-if="row.act && row.act.address" style="max-width: 200px; text-align: left;" class="act-cell">{{row.act.address}}</template>
                                <template  v-else-if="row.customer && row.customer.address"
                                           style="max-width: 200px; text-align: left;" class="customer-cell">{{row.customer.address}}</template>
                                <template v-else style="max-width: 200px; text-align: left;">{{row.address}}</template>
                            </div>
                        </td>
                        <!--organization 1-->
                        <td v-if="th.key === 'organization'">
                            <div v-if="getShowCols('organization')">
                                <template v-if="row.customer && row.customer.organization" style="max-width: 200px; text-align: left;" class="act-cell">{{row.customer.organization}}</template>
                                <template v-else-if="row.customer && row.customer.organization_long" style="max-width: 200px; text-align: left;" class="act-cell">{{row.customer.organization_long}}</template>
                                <template v-else></template>
                            </div>
                        </td>
                        <!--email 1-->
                        <td v-if="th.key === 'email'">
                            <div v-if="getShowCols('email')">
                                <template v-if="row.customer && row.act"  class="act-cell">
                                    <div v-if="row.customer.email">
                                        <Poptip content="Переход на страницу создания коммерческих предложений. Продолжить?"
                                                @confirm="makeCommercialMail(row.customer.email,row)">
                                            <!--Предложение уже было отправлено! Отправить еще одно?', 'warn'-->
                                            <Button :color="row.offer_sent ? 'primary': ''" size="xs">{{row.customer.email}}</Button>
                                        </Poptip>
                                    </div>
                                    <div v-if="row.customer.emails">
                                        <Poptip content="Переход на страницу создания коммерческих предложений. Продолжить?"
                                                @confirm="makeCommercialMail(row.customer.email,row)">
                                            <Button :color="row.offer_sent ? 'primary': ''" size="xs">{{row.customer.emails}}</Button>
                                        </Poptip>
                                    </div>
                                </template>

                                <template v-else-if="row.customer" style="max-width: 200px">
                                    <div v-if="row.customer.email">
                                        <Poptip content="Переход на страницу создания коммерческих предложений. Продолжить?"
                                                @confirm="makeCommercialMail(row.customer.email,row)">

                                            <Button :color="row.offer_sent ? 'primary': ''" size="xs">{{row.customer.email}}</Button>
                                        </Poptip>
                                    </div>
                                    <div v-if="row.customer.emails">
                                        <Poptip content="Переход на страницу создания коммерческих предложений. Продолжить?"
                                                @confirm="makeCommercialMail(row.customer.email,row)">
                                            <Button :color="row.offer_sent ? 'primary': ''" size="xs">{{row.customer.emails}}</Button>
                                        </Poptip>
                                    </div>
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
<!--Обьект-->
                        <!--property_type 1-->
                        <td v-if="th.key === 'property_type'">
                            <div v-if="getShowCols('property_type')">
                                <template v-if="row.act && row.act.property_type" class="act-cell">
                                    {{act_propertyType.find(o => o.id === row.act.property_type).name}}
                                </template>
                                <template v-else-if="row.property_type">
                                    {{act_propertyType.find(o => o.id === row.property_type).name}}
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
                        <!--pest 1-->
                        <td v-if="th.key === 'pest'">
                            <div v-if="getShowCols('pest')">
                                <template v-if="row.act && row.volumes.length" style="max-width: 200px" class="act-cell">
                                    <div v-for="volume in row.volumes">
                                        <template v-if="volume.pest">{{h.pests[volume.pest].name}}</template>
                                    </div>
                                </template>
                                <template v-else-if="row.volumes && row.volumes.length" style="max-width: 200px">
                                    <div v-for="volume in row.volumes">
                                        <template v-if="volume.pest">{{h.pests[volume.pest].name}}</template>
                                    </div>
                                </template>
                                <template v-else></template>
                            </div>
                        </td>
<!--Финансы-->
                        <!--cash 1-->
                        <td v-if="th.key === 'cash'" class="act-cell" colspan="2">
                            <div v-if="getShowCols('cash')">
                                <template v-if="row.act">
                                    <span v-if="row.act.cash" class="space-between no-wrap">
                                        {{Number(row.act.cash).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').slice(0, - 3)}}
                                        <span v-if="row.act.cash_tag" class="h-tag h-tag-primary">&#10003;</span>
                                    </span>
                                </template>
                                <template v-else>
                                    <span v-if="row.cash" class="space-between no-wrap">
                                        {{Number(row.cash).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').slice(0, - 3)}}
                                         <span v-if="row.cash_tag" class="h-tag h-tag-primary">&#10003;</span>
                                    </span>
                                </template>
                            </div>
                        </td>
                        <!--cashless 1-->
                        <td v-if="th.key === 'cashless'"  class="act-cell" colspan="2">
                            <div v-if="getShowCols('cashless')">
                                <template v-if="row.act">
                                    <span v-if="row.act.cashless" class="space-between no-wrap">
                                        {{Number(row.act.cashless).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').slice(0, - 3)}}
                                         <span v-if="row.act.cashless_tag" class="h-tag h-tag-primary">&#10003;</span>
                                    </span>
                                </template>
                                <template v-else>
                                    <span v-if="row.cashless" class="space-between no-wrap">
                                        {{Number(row.cashless).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$& ').slice(0, - 3)}}
                                         <span v-if="row.cashless_tag" class="h-tag h-tag-primary">&#10003;</span>
                                    </span>
                                </template>
                            </div>
                        </td>
                        <!--sum_total-->
                        <td v-if="th.key === 'sum_total'">
                            <div v-if="getShowCols('sum_total')">
                                <span v-if="row.lid_id">
<!--                                    {{Number(row.lid.cash) + Number(row.lid.cashless)}}-->
                                </span>
                                <span v-else>
<!--                                    {{Number(row.cash) + Number(row.cashless)}}-->
                                </span>
                            </div>
                        </td>
                        <!--card_payment 1-->
                        <td v-if="th.key === 'card_payment'">
                            <div v-if="getShowCols('card_payment')">
                                <template v-if="row.act" class="act-cell" style="width: 75px;">
                                    <span v-if="row.act.card_payment" class="h-tag h-tag-primary">&#10003;</span>
                                </template>
                                <template v-else style="width: 75px;">
                                    <span v-if="row.card_payment" class="h-tag h-tag-primary">&#10003;</span>
                                </template>
                            </div>
                        </td>
                        <!--overpay 1-->
                        <td v-if="th.key === 'overpay'">
                            <div v-if="getShowCols('overpay')">
                                <template v-if="row.act && row.act.overpay" class="act-cell" style="width: 75px;">
                                    <span class="h-tag">{{row.act.overpay}}</span>
                                </template>
                                <template v-else-if="row.overpay" style="width: 75px;">
                                    <span class="h-tag">{{row.overpay}}</span>
                                </template>
                                <template v-else style="width: 75px;"></template>
                            </div>
                        </td>
                        <!--contractor_fee 1-->
                        <td v-if="th.key === 'contractor_fee'">
                            <div v-if="getShowCols('contractor_fee')">
                                <template v-if="row.act && row.act.contractor_fee" class="act-cell" style="width: 75px;">
                                    <span class="h-tag">{{row.act.contractor_fee}}</span>
                                </template>
                                <template v-else-if="row.contractor_fee" style="width: 75px;">
                                    <span class="h-tag">{{row.contractor_fee}}</span>
                                </template>
                                <template v-else style="width: 75px;" :class="row.act ? 'act-cell': ''"></template>
                            </div>
                        </td>
                        <!--contract 1-->
                        <td v-if="th.key === 'contract'">
                            <div v-if="getShowCols('contract')">
                                <template style="max-width: 100px;">
                                    <a target="_blank" v-if="row.lid" :href="`/layout/lid/${row.lid.id}/edit`">
                                        <Button  color="primary" size="s">{{row.lid.contract}}</Button>
                                    </a>
                                    <a target="_blank" v-else-if="row.contract" :href="`/layout/lid/${row.lid_id}/edit`">
                                        <Button color="primary" size="s">{{row.contract}}</Button>
                                    </a>
                                </template>
                            </div>
                        </td>
                        <!--implement_date 1-->
                        <td v-if="th.key === 'implement_date'">
                            <div v-if="getShowCols('implement_date')">
                                <template>
                                    <a target="_blank" v-if="row.act" :href="`/layout/lid/${row.lid_id}/edit?open_act=${row.act.id}`">
                                        <Button v-if="row.implement_date" size="s" color="primary">{{row.implement_date}}</Button>
                                        <Button v-else color="yellow" size="s">Акт</Button>
                                    </a>
                                    <a target="_blank" v-else-if="row.implement_date" :href="`/layout/lid/${row.lid_id}/edit?open_act=${row.act_id}`">
                                        <Button color="primary" size="s" style="white-space: nowrap">{{row.implement_date}}</Button>
                                    </a>
                                </template>
                            </div>
                        </td>


                    </template>
                </tr>
                </tbody>

            </table>
        </div>


    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
    import {State, Getter, Action, Mutation, namespace} from 'vuex-class'
    // interfaces
    import * as Globals from '../../../types/Global'
    import * as Types from '../../../types/crm.statistic'
    import {IAppStatus, ICallStatus, ICardPayment, ICrmStatistic} from '../../../types/crm.statistic'
    import {ICustomer} from "../../../types/customer";
    import {ILid} from "../../../types/lid";
    import {IPropertyType} from "../../../types/act";
    import {IFieldValue, IIdName, IKeyValue} from "../../../types/Global";
    // eof interfaces


    const Statistic = namespace('CrmStatistic')
    const Customer = namespace('Customer')
    const Act = namespace('Act')

    import {bus} from "../../../bus";
    import customer from "../../../store/modules/customer";



    @Component({
        components: {}
    })
    export default class CrmStatisticTable extends Vue {
// Props
        @Prop(Number) manager!: number
        @Prop() h!: Globals.IHelper

// Statistic
        // @State
        // @Statistic.State('callStatus') callStatus!: ICallStatus[]
        @Statistic.Getter('getCallStatus') callStatus!: ICallStatus[]
        @Statistic.State('appStatus') appStatus!: IAppStatus[]
        @Statistic.State('table') statistic_table!: ICrmStatistic[]

        // @Action
        @Statistic.Action('initTable') statistic_initTable!: () => void
        @Statistic.Action('initTableRow') initTableRow!: (payload: ICrmStatistic) => void





        // ??
        // 1
        @Statistic.Mutation public addUpdatedTableRow!: (payload: ICrmStatistic) => void
        public async attachCustomer(customer: ICustomer) {
            this.row.customer_id = await customer.id
            this.row.customer = await customer
            await this.addUpdatedTableRow(this.row)
            // @ts-ignore
            this.$Message['success']('Клиент прикреплен')
        }
        // 2
        @Statistic.State('row') public row!: ICrmStatistic
        // 3
        //------------------

// table






        public tableHead = [
            {
                key: 'id', name: 'ID', short: 'id', width: 70, v: null, d: null, colspan: 1, show: true,
                f: function (el: ICrmStatistic) {
                    return el.id === this.v;
                },
            },
            {
                key: 'date', name: 'Дата', short: 'Д', width: 150, v: null, d: null, colspan: 1, show: true,
                f: function (el: ICrmStatistic) {
                    return el.date === this.v;
                },
            },
            {
                key: 'site', name: 'Сайт', short: 'С', width: 150, colspan: 1, show: true,
                select: () => {
                    // if (this.settingsSite.length) {
                    //     return this.settingsSite.map(a => {
                    //         return this.h.companies[a];
                    //     })
                    // } else {
                    //     return Object.keys(this.h.companies).map((key:any) => {
                    //         return this.h.companies[key];
                    //     });
                    // }
                },
                v: null,
                d: null,
                f: function (el: ICrmStatistic) {
                    return el['site'] === this.v
                }
            },
            {
                key: 'manager',
                name: 'Менеджер', short: 'М',
                width: 150, colspan: 1,
                type: 'select', show: true,

                select: () => {
                    let res = [];
                    let permission = [1, 2, 4]
                    for (let a in this.h.users) {
                        //@ts-ignore
                        if ([1, 2, 4].indexOf(this.h.users[a].role_id) !== -1) {
                            //@ts-ignore
                            res.push(this.h.users[a])
                        }
                    }
                    res.push({id: 9999, name: 'Семен и Co'});
                    return res
                    //     if (this.settingsUsers.length) {
                    //         let s = this.settingsUsers.map(a => {
                    //             return this.h.users[a]
                    //         });
                    //         s.push({id: 9999, name: 'Семен и Co'});
                    //         return s;
                    //     } else {
                    //         let s = Object.keys(this.h.users).map((a) => {
                    //             return this.h.users[a]
                    //         });
                    //         s.push({id: 9999, name: 'Семен и Co'});
                    //         return s;
                    //     }
                },
                fields: {
                    9999: 'Семен и Co',
                    2: 'dd',
                    21: 'Татьяна',
                    22: 'Константин',
                    40: 'Светлана',
                    20: 'Андрей',
                },
                v: null,
                d: null,
                f: function (el: ICrmStatistic) {
                    return el.manager === this.v
                }

            },
            {
                key: 'call_status',
                name: 'Статус', short: 'С',
                width: 125, colspan: 1,
                type: 'select',
                select: this.callStatus, show: true,
                /*                    [
                                    {id: 1, name: 'спам'},
                                    {id: 2, name: 'перезвонить'},
                                    {id: 3, name: 'невыгодно'},
                                    {id: 4, name: 'нецелевой'},
                                    {id: 5, name: 'отказ'},
                                    {id: 6, name: 'история'}, //
                                    {id: 7, name: 'заказ'},
                                    {id: 8, name: 'база'}, //
                                    {id: 9, name: 'новая'}, //
                                    {id: 10, name: 'на удаление'},
                                ]*/

                v: null,
                d: null,
                f: function (el: ICrmStatistic) {
                    return el.call_status === this.v
                }
            },
            {
                key: 'app_status',
                name: 'Статус заявки', short: 'С',
                width: 130, colspan: 1,
                type: 'select',
                select: this.appStatus, show: true,
                v: null,
                f: function (el: ICrmStatistic) {
                    return el.app_status === this.v
                }

            },
            {
                key: 'cause', name: 'Комментарий', short: 'К', width: 150, colspan: 1, v: '', d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.cause && el.cause.toLowerCase().indexOf(this.v.toLowerCase()) !== -1 ||
                        el.act?.comment && el.act.comment.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                }
            },
// Клиент
            {
                key: 'phone',
                name: 'Телефон', short: 'Т',
                width: 110, colspan: 1,
                class: 'customer-thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.phone && el.phone.toLowerCase().indexOf(this.v.toLowerCase()) !== -1 ||
                        el.act?.phone && el.act.phone.toLowerCase().indexOf(this.v.toLowerCase()) !== -1

                }
            },
            {
                key: 'customer_status',
                name: 'Ю/Ф', short: 'Ю',
                width: 75,
                colspan: 1,
                class: 'customer-thead',
                v: null,
                d: null, show: true,
                f: function (el: ICrmStatistic) {
                    return el.customer?.status === this.v
                }
            },
            {
                key: 'name',
                name: 'Имя', short: 'И',
                width: 150, colspan: 1,
                class: 'customer-thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.name && el.name.toLowerCase().indexOf(this.v.toLowerCase()) !== -1 ||
                        el.act?.contact_name && el.act.contact_name.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                }
            },
            {
                key: 'address',
                name: 'Адрес', short: 'А',
                width: 150, colspan: 1,
                class: 'customer-thead',
                max: true,
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.address && el.address.toLowerCase().indexOf(this.v.toLowerCase()) !== -1 ||
                        el.act?.address && el.act.address.toLowerCase().indexOf(this.v.toLowerCase()) !== -1 ||
                        el.customer?.address && el.customer.address.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                }
            },
            {
                key: 'organization',
                name: 'Организация', short: 'О',
                width: 150, colspan: 1,
                class: 'customer-thead',
                max: true,
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    if(el.customer?.organization){
                        return el.customer.organization.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                    } else if (el.customer?.organization_long) {
                        return el.customer.organization_long.toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                    }
                }
            },

            {
                key: 'email',
                name: 'запрос кп', short: 'З',
                width: 100, colspan: 1,
                class: 'customer-thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.customer?.email && String(el.customer.email).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                }
            },
// Обьект
            {
                key: 'property_type',
                name: 'Тип обьекта', short: 'Т',
                width: 150, colspan: 1,
                class: 'object_thead',
                v: null,
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.act?.property_type === this.v || el.property_type === this.v
                }
            },
            {
                key: 'pest',
                name: 'Сущность', short: 'С',
                width: 200, colspan: 1,
                min_width: 200,
                class: 'object_thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.volumes?.find(o => Number(o.pest) === Number(this.v))
                }
            },
    //Площадь-->
    //Пл. факт-->
    //Единица площади-->
    //Методы-->


// Финансы
            {
                key: 'cash',
                name: 'Нал', short: 'Н',
                width: 75,
                colspan: 2,
                max: true,
                class: 'money_thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    if(el.act){
                        return el.act.cash && String(el.act.cash).indexOf(this.v) !== -1
                    } else  {
                        return el.cash && String(el.cash).indexOf(this.v) !== -1
                    }
                }
            },
            {
                key: 'cash_tag',
                name: 'Н', short: 'Н',
                width: 20,
                colspan: 0,
                class: 'money_thead',
                v: false,
                d: false, show: true,
                f: function (el: ICrmStatistic) {
                    if(this.v){
                        return el.cash_tag === 1 || el.act?.cash_tag
                    } else  {return false}
                }
            },
            {
                key: 'cashless',
                name: 'Безнал', short: 'Б',
                colspan: 2,
                width: 75,
                class: 'money_thead',
                max: true,
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    if(el.act){
                        return el.act.cashless && String(el.act.cashless).indexOf(this.v) !== -1
                    } else  {
                        return el.cashless && String(el.cashless).indexOf(this.v) !== -1
                    }
                }
            },
            {
                key: 'cashless_tag',
                name: 'БН', short: 'Б',
                width: 20,
                class: 'money_thead',
                colspan: 0,
                v: false,
                d: false, show: true,
                f: function (el: ICrmStatistic) {
                    if(this.v){
                        return el.cashless_tag === 1 || el.act?.cashless_tag === 1
                    } else  {return false}
                }
            },
            {
                key: 'sum_total',
                name: 'Сумма', short: '∑',
                tip: 'Сумма Н + БН',
                width: 50, colspan: 1,
                class: 'money_thead',
                v: false,
                d: false, show: true,
                f: function (el: ICrmStatistic) {
                    if(this.v){
                        return el.card_payment === 1 || el.act?.card_payment === 1
                    } else  {return false}
                }
            },


            {
                key: 'card_payment',
                name: 'Карта', short: 'К',
                width: 50, colspan: 1,
                class: 'money_thead',
                v: false,
                d: false, show: true,
                f: function (el: ICrmStatistic) {
                    if(this.v){
                        return el.card_payment === 1 || el.act?.card_payment === 1
                    } else  {return false}
                }
            },
            {
                key: 'overpay',
                name: 'Мастер+', short: 'М',
                width: 50, colspan: 1,
                class: 'money_thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    if(el.act){
                        return el.act?.overpay && Number(el.act.overpay) >= Number(this.v)
                    } else  {return false}
                }
            },
            {
                key: 'contractor_fee',
                name: 'Суб-чик', short: 'С',
                tip: 'Выплаты субподрядчикам',
                width: 75, colspan: 1,
                class: 'money_thead',
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.act?.contractor_fee && Number(el.act.contractor_fee) >= Number(this.v) ||
                        el.contractor_fee && Number(el.contractor_fee) >= Number(this.v)
                }
            },

            // Отказ

            //-------- Движение финансов
            //стоимость лида / или перманентная
            //Приход по лиду / нал + безнал
            //остаток
            //
            //
            //
            {
                key: 'contract',
                name: 'Договор', short: 'Д',
                width: 100, colspan: 1,
                v: '',
                d: '', show: true,
                f: function (el: ICrmStatistic) {
                    return el.contract && String(el.contract).toLowerCase().indexOf(this.v.toLowerCase()) !== -1 || el.lid?.contract && String(el.lid.contract).toLowerCase().indexOf(this.v.toLowerCase()) !== -1

                }
            },
            {
                key: 'implement_date',
                name: 'Выполнение', short: 'В',
                width: 150, colspan: 1,
                v: null,
                d: null, show: true,
                f: function (el: ICrmStatistic) {
                    return el.implement_date === this.v
                }
            }
        ]
        public theadSettings: Globals.IKeyValue = {}

        mounted(){
            if(localStorage.getItem('theadSettings')){
                try {
                    // @ts-ignore
                    this.theadSettings = JSON.parse(localStorage.getItem('theadSettings'))
                } catch (e) {
                    localStorage.removeItem('theadSettings')
                }
            } else {
                let nawLSData:Globals.IKeyValue = {}
                this.tableHead.map(o => nawLSData[o.key] = true)
                localStorage.setItem('theadSettings', JSON.stringify(nawLSData))
            }
        }

        setShowCols(key:string, value:boolean){
            this.$set(this.$data.theadSettings,key,value)
            localStorage.setItem('theadSettings',JSON.stringify(this.theadSettings))
        }
        getShowCols(key:string){
            return Object.keys(this.theadSettings).indexOf(key) !== -1 ? this.theadSettings[key] : true
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



        // table -> methods
        public blender(arr: ICrmStatistic[], filters: any) {
            return arr.filter(function (el: any) {
                return filters.every(function (filter: any) {
                    return filter.f(el)
                })
            })
        }

         filterData() {
            let filteredTable =   this.blender(this.statistic_table, this.tableHead.filter((obj) => obj.v))
            if(filteredTable.length !== this.statistic_table.length){
                bus.$emit('showTableFilterDetachBtn', true)
            } else  {
                bus.$emit('showTableFilterDetachBtn', false)
            }
            return filteredTable
        }

        async editRow(row: ICrmStatistic) {

            // if(this.manager === row.manager || [1,4].indexOf(this.h.user.role_id) !== -1) {
               await this.initTableRow({
                    id:row.id,
                    editing:this.manager
                })
            bus.$emit('openRowModal',true)
            // } else {
            //     let manager = Number(row.manager)
            //     this.messages(`редактировать может только ${this.h?.users[manager]?.name} или руководитель`,'warn')
            // }
        }

// eof table
// customer
        @Customer.State('status') customer_status!: IIdName[]

        makeCommercialMail(email: string, row: ICrmStatistic){
            if(/^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i.test(email)){
                this.messages('В процессе разработки', 'success')
            } else {
                this.messages('Не валидный email', 'error')
            }
        }




// eof customer

// acts
        @Act.State('propertyType') public act_propertyType!: IPropertyType[]

// METHODS
        public statusDesign(id: number) {
            let cl = '';
            switch (id) {
                case 7:
                    cl = 'row_green';
                    break;
                case 5:
                    cl = 'row_red';
                    break;
                case 10:
                    cl = 'row_red';
                    break;
                case 2:
                    cl = 'row_yellow';
                    break;
                case 1:
                    cl = 'row_grey';
                    break;
                case 3:
                    cl = 'row_grey';
                    break;
                case 4:
                    cl = 'row_grey';
                    break;
                default:
                    cl = 'row_default';
            }
            return cl;
        }


        public showCallStatus(id:number){
            return id ? this.callStatus.find(a => a.id === id)?.name : ''
        }
        public showAppStatus(id:number){
            return id ? this.appStatus.find(a => a.id === id)?.name : ''
        }



        public messages(message: string, type: string){
            // @ts-ignore
            this.$Message[type](message)
        }

        created() {
            this.statistic_initTable();

            bus.$on('resetTableFilter', (val:boolean) => {
                for(let index in this.tableHead) {
                    if (this.tableHead.hasOwnProperty(index)) {
                        // @ts-ignore
                        this.tableHead[index].v = this.tableHead[index].d
                    }
                }
            });

        }



    }
</script>

