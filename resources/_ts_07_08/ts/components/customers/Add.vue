<template>
    <div class="customer-add">
        <div class="customer-add-insert">

            <Form :label-width="110">
                <FormItem label="Поиск организации" v-if="table.status" class="customer-add-insert_formItem">
                    <dadata-input :entity="'organization'" @dadata="setOrganization" placeholder="... Организация / Инн / ОГРН"/>
                </FormItem>
                <FormItem label="Поиск адреса" v-else class="customer-add-insert_formItem">
                    <dadata-input :entity="'address'" @dadata="setAddress" placeholder="... Улица / дом / корпус / квартира"/>
                </FormItem>
<!--                <FormItem label="Логотип" :required="true">-->
<!--                    <input type="text" v-model="customer.name">-->
<!--                </FormItem>-->
                <FormItem label="Статус">
                    <Select v-model="table.status" titleName="name" keyName="value" v-if="h.customer"  :datas="h.customer" @change="clearTable"/>
                </FormItem>
                <FormItem v-if="table.status" label="Наименование краткое"><input type="text" v-model="table.organization" ></FormItem>
                <FormItem v-if="table.status" label="Наименование"><textarea class="txt100" v-model="table.organization_long" /></FormItem>
                <FormItem v-if="table.status" label="Руководитель"><input type="text" v-model="table.management_name" ></FormItem>
                <FormItem v-if="table.status" label="Должность"><input type="text" v-model="table.management_post" ></FormItem>
                <FormItem label="Контактное лицо"  :required="true"  prop="name">
                    <input type="text" v-model="table.name" >
                </FormItem>
                <FormItem label="Телефоны"  :required="true">
                    <div class="h-input-group">
                        <the-mask mask="+7 (###) ### ## ##"  v-model="table.phone" :masked="true" prop="phone"/>
                        <span class="h-input-addon">доб:</span>
                        <input type="text" v-model="table.phone_sub">
                    </div>
                    <div class="h-input-group">
                        <the-mask mask="+7 (###) ### ## ##"  v-model="table.phone_ext" :masked="true"/>
                        <span class="h-input-addon">доб:</span>
                        <input type="text" v-model="table.phone_ext_sub">
                    </div>
                </FormItem>
                <FormItem label="Email">
                        <input type="text" v-model="table.email">
                        <input type="text" v-model="table.emails">
                </FormItem>
                <FormItem label="Адрес">
                    <textarea class="txt100" v-model="table.address"/>
                </FormItem>
                <FormItem  v-if="table.status" label="Адрес для договора"><textarea class="txt100" v-model="table.address_source"/></FormItem>
                <FormItem  v-if="table.status" label="ZIP" :required="true"><input type="text" v-model="table.zip" ></FormItem>
                <FormItem  v-if="table.status" label="Регион" :required="true"><input type="text" v-model="table.region" ></FormItem>
                <FormItem label="Долгота"><input type="text" v-model="table.lat" ></FormItem>
                <FormItem label="Широта"><input type="text" v-model="table.lon" ></FormItem>
                <FormItem label="Расстояние"> <input type="text" v-model="table.destination" > </FormItem>
                <FormItem  v-if="table.status" label="ОГРН"><input type="text" v-model="table.ogrn" ></FormItem>
                <FormItem  v-if="table.status" label="ИНН"><input type="text" v-model="table.inn" ></FormItem>
                <FormItem  v-if="table.status" label="КПП"><input type="text" v-model="table.kpp" ></FormItem>
<!--       банк         -->
                <FormItem v-if="table.status" label="Банковские реквизиты">
                    <dadata-input :entity="'bank'" @dadata="setBank" placeholder="... Вводите адрес"/>
<!--                    <dadata-input :entity="'organization'" @dadata="setOrganization" placeholder="... Организация / Инн / ОГРН"/>-->
                </FormItem>
                <FormItem v-if="table.status" label="Наименование банка"><input type="text" v-model="table.bank_payment" ></FormItem>
                <FormItem v-if="table.status" label="К/С"><input type="text" v-model="table.bank_ca" ></FormItem>
                <FormItem v-if="table.status" label="БИК"><input type="text" v-model="table.bank_bic" ></FormItem>
                <FormItem v-if="table.status" label=" Р/Счет организации"><input type="text" v-model="table.bank_account" ></FormItem>



            </Form>

        </div>
        <div class="customer-add-list">

            <Button v-if="formerRow.id" color="yellow" @click="updateCustomer" class="customer-add-list_saveBtn">Обновить</Button>
            <Button v-else color="primary" @click="saveCustomer" class="customer-add-list_saveBtn">Сохранить</Button>

<!--            <Button color="primary" icon="h-icon-left">Закрыть</Button>-->
        </div>
    </div>
</template>

<script lang="ts">

    import { Component, Prop, Watch, Vue } from 'vue-property-decorator'
    import {State, Mutation, Action, namespace } from 'vuex-class'

    import axios from "axios";
    import DadataInput from "../autocomplete/DadataInput.vue";
    import * as H from '../../types/helper'
    import * as S from '../../types/crm.statistic'
    const Helper = namespace('Helper')
    const Customer = namespace('Customer')
    import { bus } from "../../bus";
    import {ICustomer} from "../../types/customer";


    @Component({
        components: {
            DadataInput
        }
    })
    export default class CustomerAdd extends Vue {
        // @Prop() public row!: S.IStatisticTableObject
        @Customer.State('columns') columns!: any
        @Customer.Action('fetchColumns') fetchColumns!: () => void

        @Customer.Action('fetchUpdateRow') fetchUpdateRow!: (payload: ICustomer) => void

        @Helper.State public h!: H.IHelper
        @Helper.Action public fetchHelpers!: () => void

        public formerRow: ICustomer = {}

        get table() {return this.$store.state.Customer.row}
        @Customer.Mutation setRow!: (payload: ICustomer) => void
        set table(payload) { this.setRow(payload)}

        public validationRules = {
            required: ['name', 'phone']
        }

        setOrganization(payload: any) {
            let data = payload.data
            this.table = {...this.columns}
            this.table.id = this.formerRow.id ? this.formerRow.id : null
            this.table.name = this.formerRow.name ? this.formerRow.name : ''
            this.table.status = 1
            this.table.organization = data.name.short_with_opf || ''
            this.table.organization_long = data.name.full || ''
            this.table.phone = this.formerRow.phone ? this.formerRow.phone : ''
            this.table.phone_ext = this.formerRow.phone_ext ? this.formerRow.phone_ext : ''
            this.table.phone_sub = this.formerRow.phone_sub ? this.formerRow.phone_sub : ''
            this.table.phone_ext_sub = this.formerRow.phone_ext_sub ? this.formerRow.phone_ext_sub : ''
            this.table.address = data.address.value || ''
            this.table.address_source = data.address.data.source || ''
            this.table.email = this.formerRow.email ? this.formerRow.email : ''
            this.table.emails = this.formerRow.emails ? this.formerRow.emails : ''
            this.table.region = data.address.data.region || ''
            this.table.lat = data.address.data.geo_lat || ''
            this.table.lon = data.address.data.geo_lon || ''
            this.table.inn = data.inn || ''
            this.table.kpp = data.kpp || ''
            this.table.ogrn = data.ogrn || ''
            this.table.zip = data.address.data.postal_code || ''
            this.table.management_name = data.management ? data.management.name : ''
            this.table.management_post = data.management ? data.management.post : ''
            this.table.destination = payload.destination
        }

        setBank(payload: any){
            let data = payload.data
            // this.table = {...this.columns}
            this.table.id = this.formerRow.id ? this.formerRow.id : null
            this.table.status = 1
            this.table.bank_payment = data.name.payment
            this.table.bank_ca = data.correspondent_account
            this.table.bank_bic = data.bic
        }

        setAddress(payload: any){
            let name = this.table.name
            let phone = this.table.phone
            let phone_ext = this.table.phone_ext
            let phone_sub = this.table.phone_sub
            let phone_ext_sub = this.table.phone_ext_sub
            let email = this.table.email
            let emails = this.table.emails

            this.table = {...this.columns}
            this.table.id = this.formerRow.id ? this.formerRow.id : null
            this.table.status = 0
            this.table.name = name
            this.table.phone = phone
            this.table.phone_ext = phone_ext
            this.table.phone_sub = phone_sub
            this.table.phone_ext_sub = phone_ext_sub
            this.table.email = email
            this.table.emails = emails
            this.table.address = payload.title
            this.table.lat = payload.data.geo_lat
            this.table.lon = payload.data.geo_lon
            this.table.destination = payload.destination
        }
// create customer
        @Customer.State public newCustomer!: ICustomer
        @Customer.Action public createCustomer!: (payload: ICustomer) => void
        @Customer.Action('eUpdate') public customer_eUpdate!: (data: ICustomer, logic?: object) => void
        @Customer.Action('eCreate') public customer_eCreate!: (data: ICustomer, logic?: object) => void
        saveCustomer(){
            if (!this.table.name?.length){
                // @ts-ignore
                this.$Notice['warn']('поле "Контактное лицо" не может быть пустым');
                return false
            }
            if(!this.table.phone){
                // @ts-ignore
                this.$Notice['warn']('поле "Телефон" не может быть пустым');
                return false
            }
            if (this.table.phone && this.table.phone?.replace(/\D+/g,'').length !== 11){
                // @ts-ignore
                this.$Notice['warn']('поле "Телефон" не соответствует стандартам');
                return false
            }
            this.createCustomer(this.table)
        }

        async updateCustomer(){
            if (!this.table.name?.length){
                // @ts-ignore
                this.$Notice['warn']('поле "Контактное лицо" не может быть пустым');
                return false
            }
            if(!this.table.phone){
                // @ts-ignore
                this.$Notice['warn']('поле "Телефон" не может быть пустым');
                return false
            }
            if (this.table.phone && this.table.phone?.replace(/\D+/g,'').length !== 11){
                // @ts-ignore
                this.$Notice['warn']('поле "Телефон" не соответствует стандартам');
                return false
            }
            await this.customer_eUpdate(this.table)
           // await this.fetchUpdateRow(this.table)
        }

        @Watch('newCustomer')
        onNewCustomerChange(obj: ICustomer) {
            bus.$emit('customerCreated', obj)
        }


        clearTable(){
            let obj = {
                status: this.table.status,
                name: this.table.name,
                phone: this.table.phone,
                phone_sub: this.table.phone_sub,
                phone_ext: this.table.phone_ext,
                phone_ext_sub: this.table.phone_ext_sub,
            }
            this.$set(this.$data,'table',obj)
        }

        created() {
            this.fetchHelpers()
            this.fetchColumns()
            this.formerRow = {...this.table}


        }
    }
</script>
