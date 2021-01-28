<template>
    <div>
        <table class="table" v-if="open">

            <thead>
            <tr>
                <th class="center"><span>Поступления</span> </th>
                <th class="center"><span>Выплаты</span></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    <table class="table statistic-act-table"  style="background: #3c8dbc2b">
                        <tbody>
                        <tr>
                            <td>
                                <Button v-if="[1,3].indexOf(lid.customer_payment) !== -1" color="primary" size="s" @click="newRow(1,1)">+Нал.</Button>
                                <span v-else>Нал.</span>
                            </td>
                            <td>
                                <Button v-if="[2,3].indexOf(lid.customer_payment) !== -1" color="primary" size="s" @click="newRow(2,1)">+Безнал.</Button>
                                <span v-else>Безнал.</span>
                            </td>
                            <td>Акт</td>
                            <td>Дата</td>
                            <td colspan="6">Примечание</td>
                        </tr>
                        <tr v-for="col in finance_table" v-if="Number(col.type) === 1">
                            <td>
                                <template v-if="col.form === 1">
                                    <input v-if="editDebitID === col.id" type="text" v-model.number="col.amount">
                                    <span v-else>{{col.amount}}</span>
                                </template>
                            </td>
                            <td>
                                <template v-if="col.form === 2">
                                    <input v-if="editDebitID === col.id" type="text" v-model.number="col.amount">
                                    <span v-else >{{col.amount}}</span>
                                </template>
                            </td>
                            <td>
                                <template v-if="editDebitID === col.id"></template>
                                <template v-else></template>
                            </td>
                            <td >{{col.created_at.slice(0,-9)}}</td>
                            <td colspan="5" style="text-align: left;">
                                <textarea v-if="editDebitID === col.id" v-model="col.comment"></textarea>
                                <template v-else>{{col.comment}}</template>
                            </td>
                            <td>

                                <template v-if="editDebitID === col.id">
                                    <Button color="primary" size="s" icon="h-icon-complete" @click="updateRow(col)"/>
                                </template>
                                <template v-else>
                                    <Button color="blue" size="s" icon="h-icon-edit" @click="editDebitID = col.id"/>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="i in 10"></tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table statistic-act-table" style="background: rgba(120,131,134,0.17)">
                        <tbody>
                        <tr>

                            <td>
                                <Button v-if="[1,3].indexOf(lid.customer_payment) !== -1" color="primary" size="s" @click="newRow(1,2)">+Нал.</Button>
                                <span v-else>Нал.</span>
                            </td>
                            <td>
                                <Button v-if="[2,3].indexOf(lid.customer_payment) !== -1" color="primary" size="s" @click="newRow(2,2)">+Безнал.</Button>
                                <span v-else>Безнал.</span>
                            </td>
                            <td>Акт</td>
                            <td>Дата</td>
                            <td colspan="5">Назначение</td>
                            <td></td>
                        </tr>
                        <tr v-for="col in finance_table" v-if="col.type === 2">

                            <td>
                                <template v-if="col.form === 1">
                                    <input v-if="editCreditID === col.id" type="text" v-model.number="col.amount">
                                    <span v-else>{{col.amount}}</span>
                                </template>
                            </td>
                            <td>
                                <template v-if="col.form === 2">
                                    <input v-if="editCreditID === col.id" type="text" v-model.number="col.amount">
                                    <span v-else >{{col.amount}}</span>
                                </template>
                            </td>
                            <td></td>
                            <td>{{col.created_at.slice(0,-9)}}</td>
                            <td colspan="5">{{col.comment}}</td>
                            <td>
                                <template v-if="editCreditID === col.id">
                                    <Button color="primary" size="s" icon="h-icon-complete" @click="updateRow(col)"/>
                                </template>
                                <template v-else>
                                    <Button color="blue" size="s" icon="h-icon-edit" @click="editCreditID = col.id"/>
                                </template>
                            </td>
                        </tr>
                        <tr v-for="i in 10"></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>

        </table>


    </div>
</template>

<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import { bus } from "../../bus";
    import {IFinance, IPaymentForm, IPaymentType} from "../../types/finance";
    import {IFieldValue, IHelper, IIdName} from "../../types/Global";
    import {ILid} from "../../types/lid";
    import {ICustomer} from "../../types/customer";
    const Finance = namespace('Finance')

    @Component
    export default class FinanceByLid extends Vue {
        @Prop() manager!: number
        @Prop() h!: IHelper

        public editDebitID: number = 0
        public editCreditID: number = 0

        public amount = 0
        public form = 1
        public type = 1


        @Finance.State('type') private payment_type!: IPaymentType[]
        @Finance.State('form') private payment_form!: IPaymentForm[]
        public open: boolean = false
        private lid:ILid = {}
        private customer: ICustomer = {}


        // TABLE
        @Finance.State('table') finance_table!: IFinance[]
        @Finance.Action('readTable') finance_readTable!: (data: IFieldValue) => void
        @Finance.Action('createAppendRow') finance_createAppendRow!: (data: IFinance) => void
        @Finance.Action('readUpdateRow') finance_readUpdateRow!: (data: IFinance) => void
        // @Finances.('')

        async newRow(form: number, type: number){
            await this.finance_createAppendRow({
                amount: 0,
                form: form,
                type: type,
                lid_id: this.lid.id,
                contract: this.lid.contract,
                customer: this.customer.id,
                manager: this.manager,
            })
        }
        async updateRow(col: IFinance){
            await this.finance_readUpdateRow(col)
            this.editDebitID = 0
            this.editCreditID = 0
        }
        async insertRow(){
            await this.finance_createAppendRow({
                amount: this.amount,
                form: this.form,
                type: this.type,
                lid_id: this.lid.id,
                // act_id: this.,
                contract: this.lid.contract,
                customer: this.customer.id,
                manager: this.manager,
            })
            this.amount = 0
            this.type = 1
            this.form = 1
        }

        makeTotal(){

        }





        sumTotal(){
            return this.finance_table.reduce((sum: number, o: any) =>  o.type === 1 ? sum + o.amount : sum - o.amount, 0)
        }
        sumDebit(){
            return this.finance_table.reduce((sum: number, o: any) =>  o.type === 1 ? sum + o.amount : sum, 0)
        }
        sumCredit(){
            return this.finance_table.reduce((sum: number, o: any) =>  o.type === 2 ? sum + o.amount : sum, 0)
        }
        sumCash(){}
        sumCashless(){}

        summary(){

            let s = {

            }
        }


        created() {
            bus.$on('finance_byLid', (lid: ILid,customer:ICustomer) => {
                this.finance_readTable({field: 'lid_id', value: lid})
                this.lid = lid
                this.customer = customer
                this.open = !this.open
            })
        }
        mounted(){

            // this.finance_readTable({field: 'lid_id', value: 2108})
        }
    }
</script>
