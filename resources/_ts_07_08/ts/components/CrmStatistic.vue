<template>
    <div>
        <top-buttons :manager="manager"/>
        <statistic-table  :h="h" :manager="manager"/>
        <statistic-row :h="h" :manager="manager"/>
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator'
    import {State, Getter, Action, Mutation, namespace} from 'vuex-class'
    // interfaces
    import * as Globals from '../types/Global'
    import * as Types from '../types/crm.statistic'
    import {ICrmStatistic} from '../types/crm.statistic'
    // eof interfaces
    import axios from 'axios'
    import * as moment from 'moment';


    const Statistic = namespace('CrmStatistic')
    const Customer = namespace('Customer')
    const Act = namespace('Act')

    import TopButtons from "./crm/statistic/TopButtons.vue";
    import StatisticRow from "./crm/statistic/Row.vue";
    import StatisticTable from "./crm/statistic/Table.vue";

    import {ICustomer} from "../types/customer";
    import {bus} from "../bus";
    import {ILid} from "../types/lid";
    import {IPropertyType} from "../types/act";
    import {IFieldValue, IIdName, IKeyValue} from "../types/Global";


    @Component({
        components: {TopButtons, StatisticTable, StatisticRow}
    })
    export default class CrmStatistic extends Vue {
// Props
        @Prop(Number) manager!: number
        @Prop() h!: Globals.IHelper

// Statistic


        // @State
        @Statistic.State('row') public row!: ICrmStatistic
        // @Mutation
        @Statistic.Mutation('setUpdateRow') statistic_setUpdateRow!: (payload: ICrmStatistic) => void
        @Statistic.Mutation('unshiftRow') public unshiftRowStatistic!: (payload: ICrmStatistic) => void
        @Statistic.Mutation public addUpdatedTableRow!: (payload: ICrmStatistic) => void
        @Statistic.Mutation('setUpdatedTableByLid') public statistic_setUpdatedTableByLid!: (payload: ILid) => void
        // @Action
        @Statistic.Action('updateRow') public updateRow_CrmStatistic!: (payload: ICrmStatistic) => void








        /** {@link  CrmStatistic.initTableRow} */
        @Statistic.Action('initTableRow') public initTableRow!: (payload: ICrmStatistic) => void
        /** {@link CrmStatistic.updateRow } */
        @Statistic.Action('updateRow') public updateRowCrmStatistic!: (payload: ICrmStatistic) => void
        /** {@link CrmStatistic.fetchRow } */
        @Statistic.Action('fetchRow') public fetchRow_CrmStatistic!: (payload: ICrmStatistic) => void
        @Statistic.Action('eUpdateField') public row_eUpdateField!: (payload: IFieldValue) => void

        //@Mutation


// customer
        @Customer.State('status') customer_status!: IIdName[]
        @Customer.Mutation('setRow') public setRowCustomer!: (payload: ICustomer) => void

// METHODS

        public async attachCustomer(customer: ICustomer) {
            this.row.customer_id = await customer.id
            this.row.customer = await customer
            await this.addUpdatedTableRow(this.row)
            // @ts-ignore
            this.$Message['success']('Клиент прикреплен')
        }

        public messages(message: string, type: string){
            // @ts-ignore
            this.$Message[type](message)
        }

        created() {

            /*crmStatistic*/
            (window as any).Echo.join('crmStatistic').listen('CrmStatistic', (event: any) => {

                switch (event.result.action) {
                    case 'create':
                        this.unshiftRowStatistic(event.result.data)
                        break;
                    case 'update_row':
                        if(event.user.id !== this.manager){
                            console.log('from statistic update' , event);
                            this.statistic_setUpdateRow(event.result.data)
                        }
                        break;
                    case 'update_row_new_lid':
                        if(event.user.id !== this.manager){
                            this.statistic_setUpdateRow(event.result.data)
                        } else {
                            bus.$emit('contractNrConfirmModal', false)
                        }
                        break;
                }
            });

            (window as any).Echo.join('customer').listen('Customer', (event: any) => {

                switch (event.result.action) {
                    case 'update':
                        this.attachCustomer(event.result.data)
                        bus.$emit('customerAddModal', false)
                        break;
                    case 'create':
                        this.attachCustomer(event.result.data)
                        bus.$emit('customerAddModal', false)
                        break;
                }
            });

/*lid*/
            (window as any).Echo.join('lid').listen('Lid', (event: any) => {
                console.log('from echo lid', event);
                let eLidData: ILid = event.result.data
                switch (event.result.action) {
                    case 'update':
                        this.statistic_setUpdatedTableByLid(event.result.data)
                        break;
                    case 'create': // создан ЛИД
                        this.updateRowCrmStatistic({
                            id: this.row.id,
                            lid_id: eLidData.id
                        })
                        bus.$emit('contractNrConfirmModal', false)
                        bus.$emit('customerSearch', [])
                }
            });


            /*Act*/
            (window as any).Echo.join('act').listen('Act', (event: any) => {
                console.log('from echo Act', event);
                switch (event.result.action) {
                    case 'create':

                        // this.unshiftRowStatistic(event.result.data)
                        break;
                    case 'create_with_volumes':
                        if (event.result.data) {
                            console.log(event.result.data);
                            this.updateRow_CrmStatistic({ // addUpdatedTableRow_crmStatistic
                                id: event.result.data.statistic_id,
                                act_id: event.result.data.id,
                                call_status: 7
                            })
                        } else {
                            // @ts-ignore
                            this.$Message['error']('Ошибка создания акта')
                        }

                        break;
                    case 'update':
                        this.fetchRow_CrmStatistic({id: event.result.data.statistic_id})
                        break;
                }
            });
            /*Implement*/
            (window as any).Echo.join('implement').listen('Implement', (event: any) => {
                console.log('from echo Implement', event);
                switch (event.result.action) {
                    case 'create':
                        // this.unshiftRowStatistic(event.result.data)
                        break;

                    case 'update':
                        // this.addUpdatedTableRow(event.result.data)
                        // bus.$emit('customerAddModal', false)
                        break;
                }
            });
            /*Volume*/
            (window as any).Echo.join('volume').listen('Volume', (event: any) => {
                console.log('from echo Volume', event);
                switch (event.result.action) {
                    case 'create':
                        this.fetchRow_CrmStatistic({id: event.result.data.statistic_id})
                        break;
                    case 'update':
                        this.fetchRow_CrmStatistic({id: event.result.data.statistic_id})
                        break;
                    case 'delete':
                        if (event.result.data) {
                            this.fetchRow_CrmStatistic({id: event.result.data.statistic_id})
                        } else {
                            // @ts-ignore
                            this.$Message['error']('Ошибка удаления задачи')
                        }
                        break;
                }
            });

        }



    }
</script>

