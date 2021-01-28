<template>
    <div>

        <Button v-if="Object.keys(row).length" class="settings roof" style="left: 480px" color="primary"
                icon="h-icon-top"
                @click="$store.dispatch('lidStatisticEditRow',{ id: lidStatisticEditClonedRow, flag: 'open', manager: manager })">
            Редактировать
        </Button>

        <Modal className="statistic-modal" v-model="modal" :closeOnMask="false">


            <div slot="header">
                <!--HEADER-->
                <div class="statistic-form-header">
                    <div class="statistic-form-header_col">
                        <span class="h-tag h-tag-gray">{{row.id}}</span>
                    </div>
                    <div class="statistic-form-header_col" v-if="row.call_status !== 7">
                        <Button color="primary" icon="h-icon-left" @click="orderCheck">В заказ</Button>
                    </div>
                    <div class="statistic-form-header_col">
                        <Select v-model="row.call_status"
                                :datas="callStatus.filter(o => o.select && o.id !== 7)"
                                @change="changeCallStatus({id: row.id, call_status: row.call_status})" keyName="id" titleName="name"
                                style="width: 150px"/>
                    </div>
                    <div class="statistic-form-header_col">
                        <Select v-model="row.app_status" :datas="appStatus" keyName="id"
                                titleName="name" @change="statistic_updateRow({id: row.id, app_status: row.app_status})" style="width: 150px"/>
                    </div>
                    <div class="statistic-form-header_col">
                        <Select v-model="row.site" :datas="h.companies" keyName="id" titleName="name"
                                @change="statistic_updateRow({id: row.id, site: row.site})"/>
                    </div>
                    <div class="statistic-form-header_col">
                        <Select  v-model="row.manager" :datas="h.users"
                                :disabled="statistic_curatorsRole.indexOf( h.users[manager].role_id) === -1" keyName="id"
                                titleName="name"/>
                    </div>
                    <div class="statistic-form-header_col">
                        <span class="h-tag h-tag-blue">{{row.date}}</span>
                        <span class="h-tag h-tag-primary">{{row.time}}</span>
                    </div>
                    <div class="statistic-form-header_col">
                        <!--        @TODO-uretral:  -> реинициализация лида        -->
                        <input v-model="customerSearchStr" type="text" placeholder="Поиск клиента"
                               style="display: flex;" @input="fetchSearchCustomers(customerSearchStr)"/>
                        <Button v-if="row.customer" color="primary" size="s" icon="h-icon-edit" @click="editCustomer"
                                v-tooltip content="Редактировать клиента"/>

                        <Button v-else-if="customerSearchStr.length > 2 && !customerSearch.length"
                                color="primary" icon="h-icon-plus" @click="createCustomer" size="s"
                                v-tooltip content="Создать клиента"/>

                    </div>
                    <Button icon="h-icon-close" @click="closeModal">Закрыть</Button>

                </div>
            </div>


            <!--SEARCH-->
            <div class="statistic-search-container" :class="makeBorder(row.call_status)">

                <table class="table statistic-table" v-if="customerSearch.length">

                    <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Должн. лицо</th>
                        <th>Организация</th>
                        <th>Телефон</th>
                        <th>email</th>
                        <th>Адрес</th>
                        <th>Выбрать?</th>
                    </tr>
                    </thead>

                    <tbody>

                    <template v-for="customer in customerSearch">

                        <tr v-if="lids.length && customer.id === lids[0].customer"
                            :class="customer.id === searchCustomerActive ? 'active_header' : ''">

                            <td colspan="7"> Клиент</td>
                        </tr>

                        <!-- CUSTOMER -->
                        <tr
                            @click="setCustomerActive(customer.id)"
                            @dblclick="searchLid(customer.id)"
                            :class="customer.id === searchCustomerActive ? 'active' : ''">

                            <td>{{customer.name}}</td>
                            <td>{{customer.management_name}}</td>
                            <td>{{customer.organization}}</td>
                            <td style="white-space: nowrap">
                                <the-mask v-if="customer.phone" :value="customer.phone" mask="+#(###) ### ## ##"
                                          disabled/>
                                <br/>
                                <the-mask v-if="customer.phone_ext" :value="customer.phone_ext" mask="+#(###) ### ## ##"
                                          disabled/>
                            </td>
                            <td style="white-space: nowrap">{{customer.email}}</td>
                            <td>{{customer.address}}</td>
                            <td class="center">

                                <span v-if="customer.id === row.customer_id" class="h-tag h-tag-primary table">Прикреплен</span>
                                <Poptip v-else class="table statistic-table_attachCustomer"
                                        content="Прикрепить клиента?"
                                       @confirm="statistic_updateRow({id: row.id, customer_id: customer.id}), fetchSearchCustomers('')">
                                    <!-- @confirm="attachCustomer(customer)"-->
                                    <Button color="blue">Прикрепить</Button>
                                </Poptip>

                                <!--MODAL CONFIRM-->
                                <template v-if="showAttachedCustomerSwitcher">
                                    <Modal className="customer-modal-confirm" v-model="contractNrConfirmModal"
                                           :hasMask="true">
                                        <div slot="header">Создать новый лид?</div>
                                        <div class="customer-modal-confirm_body">
                                            <div>Уникальный номер контракта</div>
                                            <div><input type="text" v-model="contract"></div>
                                        </div>
                                        <div slot="footer">
                                            <button class="h-btn" @click="contractNrConfirmModal = false">Отмена
                                            </button>
                                            <button class="h-btn h-btn-primary" @click="createLid()">OK</button>
                                        </div>
                                    </Modal>
                                    <Button color="blue" icon="h-icon-plus" @click="generateContractNumber">Лид</Button>
                                </template>

                                <Poptip class="table statistic-table_attachCustomer"
                                        content="Создать новый лид?"
                                        @confirm="">

                                </Poptip>
                            </td>
                        </tr>
                        <!-- LID -->
                        <tr v-if="lids.length && customer.id === lids[0].customer"
                            :class="customer.id === searchCustomerActive ? 'active_header' : ''">
                            <td colspan="7"> Лиды</td>
                        </tr>
                        <tr v-for="lid in lids" v-if="customer.id === lid.customer"
                            :class="customer.id === searchCustomerActive ? 'active' : ''"
                        >
                            <td>{{lid.id}}</td>
                            <td v-if="lid.manager_starter">{{h.users[lid.manager_starter].name}}</td>
                            <td v-else-if="lid.manager">{{h.users[lid.manager].name}}</td>
                            <td v-else></td>
                            <!--                            ?open_act=4776-->
                            <td><a :href="`/layout/lid/${lid.id}/edit`" target="_blank">{{lid.contract}}</a></td>
                            <td colspan="3">{{lid.comment}}</td>
                            <td>
                                <span v-if="row.lid_id === lid.id" class="h-tag h-tag-primary">Прикреплен</span>
                                <Button v-else color="primary"
                                        @click="statistic_updateRow({ id: row.id, lid_id: lid.id, customer_id: lid.customer })"
                                >Прикрепить
                                </Button>
                            </td>
                        </tr>

                    </template>
                    </tbody>

                </table>
            </div>

            <!--ROW DATA-->
            <table class="statistic-act-table">
                <tbody>
                <tr>
                    <td v-for="i in 15"></td>
                </tr>
                <!--customer-->
                <tr class="statistic-act-table_head">
                    <td colspan="1">
                        <span class="red">КЛИЕНТ </span>
                    </td>
                    <td colspan="2"><span class="center">Имя</span></td>
                    <td colspan="1"><span class="center">Ю/Ф</span></td>
                    <td colspan="2"><span class="center">Телефон</span></td>
                    <td colspan="5"><span class="center">Адрес</span></td>
                    <td colspan="4"><span class="center">Организация</span></td>

                </tr>
                <tr>
                    <td colspan="1">
                        <template v-if="row.customer">
                            <Button color="blue" @click="showAttachedCustomer" size="xs">{{row.customer_id}}</Button>
                            &nbsp;
                        </template>
                        <template v-else><span class="h-tag h-tag-yellow">Не прикреплен</span></template>
                    </td>
                    <td colspan="2"><!--Имя-->
                        <template v-if="row.customer && row.customer.name">{{row.customer.name}}</template>
                    </td>
                    <td colspan="1"><!--Ю/Ф-->
                        <template v-if="row.customer">
                            <span class="h-tag " :class="row.customer.status ? 'h-tag-yellow':''"
                                  v-html="row.customer.status ? 'Ю':'Ф'" style="margin-left: 12px;"/>
                        </template>
                    </td>
                    <td colspan="2"><!--Телефон-->
                        <template v-if="row.customer && row.customer.phone">
                            {{maskText(row.customer.phone)}}
                            <template v-if="row.customer && row.customer.phone_sub"> [ {{row.customer.phone_sub}} ]
                            </template>
                        </template>
                        <template v-if="row.customer && row.customer.phone_ext">
                            <br/>
                            {{maskText(row.customer.phone_ext)}}
                            <template v-if="row.customer && row.customer.phone_ext_sub"> [
                                {{row.customer.phone_ext_sub}}
                                ]
                            </template>
                        </template>
                    </td>
                    <td colspan="5"><!--Адрес-->
                        <template v-if="row.customer && row.customer.address">{{row.customer.address}}</template>
                    </td>
                    <td colspan="4"><!--Организация-->
                        <template v-if="row.customer && row.customer.organization">{{row.customer.organization}}
                        </template>
                    </td>
                </tr>
                <tr>
                    <td v-for="i in 15" class="slice"></td>
                </tr>
                </tbody>
            </table>
            <!--lid-->
            <row-lid :h="h"/>
            <finance-by-lid :h="h" :manager="manager"/>
            <!--act-->
            <table class="statistic-act-table">
                <tbody>
                <tr class="statistic-act-table_head">
                    <td colspan="15">
                        <span class="red">АКТ</span> <span></span>
                        <Button color="primary" size="xs" icon="h-icon-link" v-tooltip content="Привязка Акта к Лиду"
                                @click="$refs.actLink.style.display = $refs.actLink.style.display === 'none' ? 'block' : 'none'"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="15" class="ta-left">
                        <span v-if="row.act" class="h-tag h-tag-blue">{{row.act_id}}</span>
                        <a target="_blank" :href="`/layout/lid/${row.lid_id}/edit?open_act=${row.act_id}`">
                            <span v-if="row.act" class="h-tag h-tag-blue">  Акт# {{row.act.act_nr}} </span>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td colspan="15" ref="actLink" style="display: none">
                        <div class="space-between" style="width: 180px; margin: 10px 0">
                            <input type="text" v-model="act_searchRowStr" placeholder="id акта">
                            <Button color="primary" size="s" icon="h-icon-search"
                                    @click="
                                                act_setSearchRow({}),
                                                act_fetchSearchRow({field:'id',value: act_searchRowStr})"
                            />
                            <Button color="primary" size="s" icon="h-icon-close"
                                    @click="
                                                act_setSearchRow({}),
                                                act_searchRowStr = ''"
                            />
                        </div>
                    </td>
                </tr>

                </tbody>
            </table>


            <table class="table table statistic-table"
                   v-if="act_searchRowStr.length > 0 && Object.keys(act_searchRow).length">

                <thead>
                <tr>
                    <th>id</th>
                    <th>Менеджер</th>
                    <th>Адрес</th>
                    <th>Телефон</th>
                    <th>Имя</th>
                    <th>Предмет работ</th>
                    <th>Цена факт</th>
                    <th>Комментарий</th>
                    <th>Действия</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>{{act_searchRow.id}}</td>
                    <td v-if="act_searchRow.creator !== null">{{h.users[act_searchRow.creator].name}}</td>
                    <td v-else>{{act_searchRow.creator}}</td>
                    <td>{{act_searchRow.address}}</td>
                    <td>
                        <div class="stair" v-if="act_searchRow.phone">{{act_searchRow.phone}}</div>
                        <div class="stair" v-if="act_searchRow.phone_ext">{{act_searchRow.phone_ext}}</div>
                    </td>
                    <td>{{act_searchRow.contact}}</td>
                    <td>
                        <div class="stair" v-for="volume in  act_searchRow.volumes">
                            {{h.pests[volume.pest].name}}
                        </div>
                    </td>
                    <td>
                        <div class="stair" v-for="volume in  act_searchRow.volumes">
                            {{volume.price_fact}}
                        </div>
                    </td>
                    <td>{{act_searchRow.comment}}</td>
                    <td>
                        <Poptip content="Это действие привяжет АКТ к текущему ЛИДу. Продолжить?"
                                @confirm="attachAct(act_searchRow)">
                            <Button color="primary" icon="h-icon-left">Привязать к Лиду</Button>
                        </Poptip>
                    </td>

                </tr>
                </tbody>

            </table>

            <row-act :row="row"/>


            <row-volume :h="h" :row="row"/>


        </Modal>

        <Modal className="customer-modal-add" v-model="customerAddModal" :hasCloseIcon="true" :closeOnMask="false"
               type="drawer-left">
            <customer-add/>
        </Modal>


    </div>
</template>

<script lang="ts">

    import {Component, Watch, Prop, Vue} from 'vue-property-decorator'
    import {namespace} from 'vuex-class'
    import {bus} from "../../../bus";
    // interfaces
    import * as Globals from '../../../types/Global'
    import {ICustomer, ICustomersLids} from '../../../types/customer'
    import {IAppStatus, ICallStatus, ICrmStatistic} from "../../../types/crm.statistic"
    import {ILid} from "../../../types/lid"
    import {IFieldValue} from "../../../types/Global"
    // components
    import CustomerAdd from "../../customers/Add.vue";
    import DadataInput from "../../autocomplete/DadataInput.vue";
    import RowAct from "./Act.vue";
    import RowLid from "./Lid.vue";
    import RowVolume from "./Volume.vue";
    import FinanceByLid from "../../Finance/FinanceByLid.vue";
    // namespaces
    const CrmStatistic = namespace('CrmStatistic')
    const Customer = namespace('Customer')
    const Lid = namespace('Lid')
    const Act = namespace('Act')
    import * as moment from 'moment';
    import {IAct} from "../../../types/act";
    import {IVolume} from "../../../types/volume";


    @Component({
        components: {CustomerAdd, DadataInput, RowAct, RowVolume, RowLid, FinanceByLid}
    })
    export default class CrmStatisticRow extends Vue {
// Props
        @Prop() public h!: Globals.IHelper
        @Prop() public manager!: number

// Statistic
        @CrmStatistic.State('row') static_row!: ICrmStatistic
        @CrmStatistic.State('curatorsRole') statistic_curatorsRole!: number[]
        @CrmStatistic.State('callStatus') callStatus!: ICallStatus[]
        @CrmStatistic.State('appStatus') appStatus!: IAppStatus[]
        @CrmStatistic.Action('updateRow') statistic_updateRow!: (payload: ICrmStatistic) => void
        @CrmStatistic.Action('newLid') statistic_newLid!: (payload: {id:number,data:ILid}) => void
        @CrmStatistic.Action updateTableRow!: (payload: ICrmStatistic) => void

        async createLid() {
            this.errors = false
            let data = {
                manager: this.manager,
                manager_starter: this.manager,
                site: this.row.site,
                // site: this.row.site ? this.row.site : this.messages('Поле сайт обязательно','warn'),
                customer: this.row.customer_id,
                contract: this.contract,
                customer_payment: this.row.lid_customer_payment,
                payment_rule: this.row.lid_payment_rule,
                payment_condition: this.row.lid_payment_condition,
                payment_system: this.row.lid_payment_system,
                price_cash: this.row.lid_price_cash,
                price_cashless: this.row.lid_price_cashless,
                rules_prepayment: this.row.lid_rules_prepayment,
                rules_postpayment: this.row.lid_rules_postpayment,
                price_total: Number(this.row.lid_price_cash) + Number(this.row.lid_price_cashless),
                date_start: moment().format('YYYY-MM-DD HH:mm:ss')
            }
            if (!this.errors) {
                await this.statistic_newLid({id: this.row.id, data: data})
            }
        }

// LID

        // contract
        public contractNrConfirmModal = false
        get contract() {return this.$store.state.Lid.contract}
        set contract(value) {this.setContract(value)}

        generateContractNumber() {
            this.contractNrConfirmModal = !this.contractNrConfirmModal
            this.contractNrConfirmModal ? this.fetchContract(true) : this.fetchContract(false)
        }
//-------------------------------------------------------------------------------------

        @Lid.State('search') public lids!: ILid[]
        @Lid.Mutation('setSearch') lid_setSearch!: (data: ILid) => void
        @Lid.Action('fetchFieldSearch') public lidFieldSearch!: (payload: object) => void
        @Lid.Action('fetchUpdateRow') lid_fetchUpdateRow!: (data: ILid) => void





        get row() {
            return this.$store.state.CrmStatistic.row
        }

        set row(value) {
            this.setTableRow(value)
        }

        public modal: boolean = false

        @CrmStatistic.Mutation setTableRow!: (payload: object) => void
        @CrmStatistic.Mutation clearTableRow!: () => void

        /** @this CrmStatistic.eUpdateRow */
        @CrmStatistic.Action('eUpdateRow') eUpdateRowSrmStatistic!: (payload: ICrmStatistic) => void



        @Watch('row')
        onChildChanged(val: object, oldVal: object) {
            this.modal = !!Object.keys(val).length
        }

        @CrmStatistic.Action('initTableRow') public statistic_initTableRow!: (data: ICrmStatistic) => void

        async closeModal() {
            await this.statistic_initTableRow({
                id: this.row.id,
                editing: null
            })
            this.row = {}
            this.customerSearchStr = ''
            this.fetchSearchCustomers('')
            this.searchLid(0)

        }

// COMMON
        //-> errors
        public errors: boolean = false

        messages(message: string, type: string) {
            // @ts-ignore
            this.$Message[type](message)
            if (type === 'warn' || type === 'error') {
                this.errors = true
            }
        }

// CUSTOMERS

        public customerSearchStr: string = ''
        public customer: ICustomer = {}
        @Customer.Action('fetchSearch') fetchSearchCustomers!: (payload: string) => void


        get customerSearch() {
            return this.$store.state.Customer.search
        }

        set customerSearch(value: string) {
            this.fetchSearchCustomers(value)
        }


        @Customer.Action('fetchUpdateRow') customer_fetchUpdateRow!: (data: ICustomer) => void


        //{field:ILid,value:any}
        public showAttachedCustomerSwitcher: boolean = false
        @Customer.Action('fetchSearchField') public fetchSearchFieldCustomer!: (payload: IFieldValue) => void

        public showAttachedCustomer() {

            this.showAttachedCustomerSwitcher = !this.showAttachedCustomerSwitcher

            if (this.showAttachedCustomerSwitcher) {
                // this.customerSearchStr = ''
                // let tel = this.row.phone ? this.row.phone : this.row.customer.phone
                // tel = tel ? tel.replace(/\D+/g, '') : ''
                // this.fetchSearchCustomers(tel)
                this.fetchSearchFieldCustomer({
                    field: 'id',
                    value: this.row.customer_id
                })
                this.searchLid(this.row.customer_id)
            } else {
                this.fetchSearchCustomers('')
                this.searchLid(0)
            }
        }

        emitUpdateActContacts() {
            this.eUpdateRowSrmStatistic({
                id: this.row.id,
                phone: this.row.customer.phone,
                phone_sub: this.row.customer.phone_sub,
                phone_ext: this.row.customer.phone_ext,
                phone_ext_sub: this.row.customer.phone_ext_sub,
                name: this.row.customer.name,
                address: this.row.customer.address,
                lat: this.row.customer.lat,
                lon: this.row.customer.lon,
                region: 'Москва',// this.row.customer.region,
                destination: this.row.customer.destination,
            })
        }


        public searchCustomerActive: number | null = null

        setCustomerActive(id: number) {
            this.searchCustomerActive = id
        }

        public customerAddModal: boolean = false


        @Customer.Mutation('setRow') public currentCustomer!: (payload: ICustomer) => void

        createCustomer() {
            this.currentCustomer({
                status: 0,
                name: this.row.name,
                phone: this.row.phone
            })
            this.customerAddModal = true
        }

        async editCustomer() {
            await this.currentCustomer(this.row.customer)
            this.customerAddModal = true
        }

        attachCustomer(item: ICustomer) {
            // if(this.row.lid_id){
            //     this.messages('Лид имеется','warn')
            //
            // } else {
            this.updateTableRow({
                id: this.row.id,
                customer_id: item.id,
                name: item.name,
                address: item.address,
                phone: item.phone,
                phone_sub: item.phone_sub,
                phone_ext: item.phone_ext,
                phone_ext_sub: item.phone_ext_sub,
            })
            this.fetchSearchCustomers('')
            // }

        }




        public searchLid(customerID: number) {
            // if (this.row.call_status === 7) {
            this.lidFieldSearch({field: 'customer', value: customerID})
            // }
            // else {
            //     // @ts-ignore
            //     this.$Message['warn']('Для этого действия статус лида должен иметь значение "ЗАКАЗ"')
            // }
        }


        async attachLid(id: number) {
            console.log('sdasd', id)
            if (!this.row.customer_id) {
                this.messages('Не прикреплен КЛИЕНТ', 'error')
                return false
            }
            await this.lid_fetchUpdateRow({
                id: id,
                customer: this.row.customer_id
            })

            /*            await this.eUpdateRowSrmStatistic({
                            id: this.row.id,
                            customer_id: this.row.customer_id,
                            lid_id: id
                        })
                        this.searchContract = ''*/
        }



        // @Lid.State('contract') public contract!: string
        @Lid.Mutation('setContract') public setContract!: (payload: string) => void
        @Lid.Action('fetchContract') fetchContract!: (payload: boolean) => void
        // row
        @Lid.State('row') public lid!: ILid
        /** {@link Lid.eCreateRow } */
        @Lid.Action('eCreateRow') eCreateRowLid!: (payload: ILid) => void







// EOF LID
// ACT

        @Act.State('table') public acts!: IAct
        @Act.State('row') public act!: IAct
        @Act.Action('updateRow') act_updateRow!: (payload: IAct) => void
        @Act.Action('eCreateRow') eCreateRowAct!: (payload: IAct) => void
        @Act.Action('eCreateRowWithVolumes') eCreateRowWithVolumesAct!: (payload: IAct) => void
        @Act.Action('eUpdateRow') eUpdateRowAct!: (payload: IAct) => void

        public act_searchRowStr = ''
        @Act.State('searchRow') public act_searchRow!: IAct
        @Act.Mutation('setSearchRow') act_setSearchRow!: (payload: IAct) => void
        @Act.Action('fetchSearchRow') act_fetchSearchRow!: (payload: IFieldValue) => void

        @Act.Mutation('setRow') act_setRow!: (payload: IAct) => void
        @Act.Action('fetchUpdateRow') act_fetchUpdateRow!: (payload: IAct) => void

        async attachAct(act: IAct) {
            if (!this.row.lid_id) {
                this.messages('Не прикреплен ЛИД', 'error')
                return false
            }
            await this.act_fetchUpdateRow({
                id: act.id,
                parent: this.row.lid_id,
                creator: this.manager,
                statistic_id: this.row.id
            })

            await this.eUpdateRowSrmStatistic({
                id: this.row.id,
                act_id: act.id
            })
            this.act_searchRowStr = '';
            await this.act_setSearchRow({});
            await this.act_setRow({});
        }


        async changeCallStatus(obj: ICrmStatistic){
            this.act_updateRow({id: this.row.act_id ,parent: null})
            this.statistic_updateRow(obj) // {id: row.id, call_status: row.call_status}
        }
        orderCheck() {
            let check = true

            if (!this.row.customer_id) {// Клиент
                this.messages('Привяжите или создайте КЛИЕНТА', 'error')
                return false
            }
            if (!this.row.lid_id) { // Лид
                this.messages('Привяжите или создайте ЛИД', 'error')
                return false
            }
            if (!this.row.phone) { // ТЕЛЕФОН
                this.messages('Не указан хотя бы один ТЕЛЕФОН', 'error')
                return false
            }
            if (!this.row.name) { // ИМЯ
                this.messages('Не указано ИМЯ КОНТАКТА', 'error')
                return false
            }
            if (!this.row.property_type) { // ТИП ОБЬЕКТА
                this.messages('Не указан ТИП ОБЬЕКТА', 'error')
                return false
            }
            if (!this.row.address) { // АДРЕС
                this.messages('Не указан АДРЕС ОБЬЕКТА', 'error')
                return false
            }
            if (!this.row.lat || !this.row.lon) { // ДОЛГОТА или ШИРОТА
                this.messages('Не указана ДОЛГОТА или ШИРОТА', 'error')
                return false
            }
            if (!this.row.destination) { // РАССТОЯНИЕ
                this.messages('Не указано РАССТОЯНИЕ', 'error')
                return false
            }
            if (!this.row.cash_tag && !this.row.cashless_tag) { // ТИП ОПЛАТЫ
                this.messages('Не указан ТИП ОПЛАТЫ', 'error')
                return false
            }
            if (!this.row.volumes.length) { // ОБЬЕМ РАБОТ
                this.messages('Не указан ОБЬЕМ РАБОТ', 'error')
                return false
            } else {
                this.row.volumes.forEach((o: IVolume, k: number) => {
                    if (!o.pest) {
                        this.messages(`Не указан ПРЕДМЕТ РАБОТ ( ${k + 1} - й ряд )`, 'error')
                        check = false
                    }
                    if (!o.square) {
                        this.messages(`Не указана ПЛОЩАДЬ ПО ДОГОВОРУ ( ${k + 1} - й ряд )`, 'error')
                        check = false
                    }
                    if (!o.square_fact) {
                        this.messages(`Не указана ФАКТИЧЕСКАЯ ПЛОЩАДЬ ( ${k + 1} - й ряд )`, 'error')
                        check = false
                    }
                    if (!o.entity) {
                        this.messages(`Не указана сущность ПЛОЩАДИ ( ${k + 1} - й ряд )`, 'error')
                        check = false
                    }
                    /*                    if(!o.method?.length){
                                            this.messages(`Не указан ни один МЕТОД ( ${k+1} - й ряд )`,'error')
                                            check = false
                                        }*/
/*                    if (!o.price_fact) {
                        this.messages(`Не указана ФАКТИЧЕСКАЯ ЦЕНА ( ${k + 1} - й ряд )`, 'error')
                        check = false
                    }*/
                })
            }
            if (!check) {
                return false
            } else {
                // обновить
                if(this.row.act_id){
                    this.act_updateRow({id: this.row.act_id, parent: this.row.lid_id})
                    this.statistic_updateRow({id: this.row.id, call_status: 7})
                } else {
                    // создать акт
                    this.eCreateRowWithVolumesAct({
                        parent: this.row.lid.id,
                        statistic_id: this.row.id,
                        creator: this.manager,

                        phone: this.row.phone,
                        phone_sub: this.row.phone_sub,
                        phone_ext: this.row.phone_ext,
                        phone_ext_sub: this.row.phone_ext_sub,

                        contact_name: this.row.name,
                        address: this.row.address,
                        lat: this.row.lat,
                        lon: this.row.lon,
                        destination: this.row.destination,
                        region: 'Москва',
                        cash: this.row.cash,
                        cashless: this.row.cashless,
                        cash_tag: this.row.cash_tag,
                        cashless_tag: this.row.cashless_tag,
                        property_type: this.row.property_type,
                        comment: this.row.cause,
                        volumes: this.row.volumes

                        /*                    floating: number,
                                            floating_date_from: string,
                                            floating_date_to: string,*/
                    })
                }



                // создать обьемы -> привязать id
            }

        }

// EOF ACT

        // public searchLid = {
        //     modal: '',
        //     model: '',
        // }

        maskText(value: string) {
            let b = value.replace(/\D+/g, '').split('')
            b.splice(0, 0, '+');
            b.splice(2, 0, ' (');
            b.splice(6, 0, ') ');
            b.splice(10, 0, ' ');
            b.splice(13, 0, ' ');
            return b.join('')
        }

        setAddress(payload: any) {
            this.eUpdateRowSrmStatistic({
                id: this.row.id,
                address: payload.title,
                lat: payload.data.geo_lat,
                lon: payload.data.geo_lon,
                destination: payload.destination
            })
        }

        // design
        makeBorder(status: number){
            let cl = status ? this.callStatus.find(c => c.id === status)?.design : ''
            return 'border-' + cl
        }

        created() {
            this.customerSearchStr = '';
            bus.$on('customerCreated', (obj: ICustomer) => {
                this.statistic_updateRow({
                    id: this.row.id,
                    customer_id: obj.id,
                })
                this.customerAddModal = false
            })

            bus.$on('customerAddModal', (modalState: boolean) => {
                this.customerAddModal = modalState
            })
            bus.$on('contractNrConfirmModal', (modalState: boolean) => {
                this.contractNrConfirmModal = modalState
            })
            bus.$on('customerSearch', (arr: any) => {
                this.customerSearch = arr
            })
            bus.$on('phoneSearch', (str: string) => {
                let val: string = (str.replace(/\D+/g, '').replace('7', ''))
                if (val.length > 2) {
                    this.fetchSearchCustomers(val)
                    this.customerSearchStr = val
                } else {
                    this.fetchSearchCustomers('')
                    this.customerSearchStr = ''
                    this.searchLid(0)
                }
            })

        }
    }
</script>
