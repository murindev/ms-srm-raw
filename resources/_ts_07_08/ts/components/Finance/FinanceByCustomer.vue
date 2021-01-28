<template>
    <div>
        <Modal class="actModal" v-model="open" type="drawer-right"  :hasCloseIcon="true">
            <div class="table_container" >

<!--                Внести

                Дебет/Кредит
                Форма-->
{{finance_table}}

                <table class="table statistic-act-table">

                    <thead>
                    <tr>
                        <!--amount-->
                        <th>Сумма</th>
                        <!--form -->
                        <th>Дебет</th>
                        <!--form-->
                        <th>Кредит</th>
                        <!--type-->
                        <th>Форма</th>
                        <!--lid_id-->
                        <th>Лид</th>
                        <!--act_id-->
                        <th>Акт</th>
                        <!--customer-->
                        <th>Клиент</th>
                        <!--manager-->
                        <th>Менеджер</th>
                        <!--created_at-->
                        <th>Дата внесения</th>
                        <!--updated_at-->
                        <th>Дата обновления</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <!--amount-->
                        <td></td>
                        <!--form -->
                        <td></td>
                        <!--form-->
                        <td></td>
                        <!--type-->
                        <td></td>
                        <!--lid_id-->
                        <td></td>
                        <!--act_id-->
                        <td></td>
                        <!--customer-->
                        <td></td>
                        <!--manager-->
                        <td></td>
                        <!--created_at-->
                        <td></td>
                        <!--updated_at-->
                        <td></td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>



                </table>
            </div>
        </Modal>

    </div>
</template>

<script lang="ts">
    import { Component, Vue, Prop } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import { bus } from "../../bus";
    import {IFinance} from "../../types/finance";
    import {IFieldValue} from "../../types/Global";
    const Finances = namespace('Finance')

    @Component
    export default class FinanceByCustomer extends Vue {
        @Prop() manager!: number
        public open: boolean = true


        // ROW
        @Finances.State('table') finance_table!: IFinance
        @Finances.Action('readTable') finance_readTable!: (data: IFieldValue) => void
        // @Finances.('')


        created(){

            this.finance_readTable({field: 'customer', value: 1251})

            bus.$on('finance_byClient',(customer_id: number) => {
                this.finance_readTable({field: 'customer', value: customer_id})
                this.open = true


            })
        }
    }
</script>
