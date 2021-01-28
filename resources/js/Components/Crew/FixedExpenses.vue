<template>
<div>
    <DateFullRangePicker
            v-model="date"
            :layout="['month']"
    />

    <Modal v-model="fixedModal"  :hasCloseIcon="true" :middle="true" :closeOnMask="false">
        <div class="h-panel w600" style="margin: 12px;">
<!--Новая сущность-->
            <div class="h-panel-bar">
                <span class="h-panel-title">Расходы за месяц</span>
                <div class="h-panel-right">
                    <input placeholder="Новая сущность" type="text" v-width="200" v-model="helper.name" @keyup="checkSimilarity()">
                    <i class="h-split"></i>
                    <Button
                            :disabled="helper.name.length <= 2 && checkSimilarity()"
                            @click="createExpensesHelpers(helper)"
                            class="h-btn h-btn-green h-btn-m">Добавить</Button>
                    <i class="h-split"></i>
                    <Button color="h-btn-m" :text="true" icon="h-icon-setting" @click="settingBtn = !settingBtn"></Button>


                </div>
            </div>
<!--Настройка сущностей-->
            <div class="h-panel-bar bg-gray4-color" v-if="settingBtn">
                <div class="text-center"><span class="h-tag h-tag-yellow">Настройка сущностей</span></div>

                <template v-for="hlp in expensesHelpersFixed">
                    <Row :space="10" class="row-middle">
                        <Cell width="22"><input class="w100 bg-gray4-color" type="text" v-model="hlp.name" @focusout="updateExpensesHelpers(hlp)"></Cell>
                        <Cell width="2">
                            <Poptip :content="`Удалить поле '${hlp.name}'?`" @confirm="deleteExpensesHelpers(hlp.id)">
                                <Button class="bg-gray4-color" size="s" :text="true"  icon="h-icon-trash"></Button>
                            </Poptip>
                        </Cell>
                    </Row>
                </template>
            </div>
<!--Выбор сущности-->
            <div class="h-panel-bar">
                <Select
                        v-model="selectedHelper"
                        @change="createExpense()"
                        :datas="avoidSimilarity()" keyName="id" titleName="name"></Select>
            </div>
<!--Список сущностей и значений-->
            <div class="h-panel-body">
                <template v-for="(exp,expKey) in expensesFixed">
                    <Row :space="10" class="row-middle">
                        <Cell width="10">
                            <span v-if="expensesHelpersFixed && expensesHelpersFixed.length">{{expensesHelpersFixed.find(o => o.id === exp.ref).name}}</span>
                        </Cell>
                        <!--showHelper(exp.ref,'name')-->
                        <Cell width="4">
                            <Select v-model="exp.cash" :datas="cash" @change="updateExpenses(exp)"></Select>
<!--                            <Checkbox v-model="exp.cash" :value="exp.cash === 1"  @change="updateExpenses(exp)"/>-->
                        </Cell>
                        <Cell width="8">
                            <input class="w100" type="text"
                                   :value="thousands(exp.value)"
                                   @keyup="thousandsOut(expKey)"
                                   @focusout="updateExpenses(exp)"
                            >
                        </Cell>
                        <Cell width="2">
<!--                            <Poptip content="Удалить?" @confirm="deleteExpenses(exp)">
                                <Button size="s" :text="true"  icon="h-icon-trash"/>
                            </Poptip>-->
                        </Cell>
                    </Row>
                </template>
            </div>
        </div>

    </Modal>
</div>
</template>

<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "FixedExpenses",
        data(){
            return {
                helper: {
                    name: '',
                    type: 'fixed'
                },
                expense: {
                    type: 'fixed',
                    ref : null,
                    value: null,
                    date: null,
                    cash: 0,
                },
                cash: [
                    {key:0,title:'Безнал'},
                    {key:1,title:'Нал'},
                ],
                settingBtn:false,
                selectedHelper: '',
                newEntity: '',
                fixedModal:false,
                selectAll: false
            }
        },
        computed:{
            date:{
                get() {return  this.$store.getters.fixedDate;},
                set(value) {this.$store.commit('FIXED_DATE',{
                    start:value.start.slice(0,7),
                    end:value.end.slice(0,7),
                    type:'month'
                });}
            },
            ...mapGetters(['expensesHelpersFixed','expensesFixed']),

        },
        methods: {
            ...mapActions(['readExpensesHelpers','createExpensesHelpers','updateExpensesHelpers','deleteExpensesHelpers','readExpenses','createExpenses','updateExpenses','deleteExpenses']),

            showHelper(ref,key){
              let ob = this.expensesHelpersFixed.filter(o => o.id === ref);
                console.log( ob);
                console.log( ob[key]);
              return ob[key];
            },

            checkSimilarity(){

            },
            avoidSimilarity(){
                let rest = this.expensesFixed.map(ob => ob.ref);
                return this.expensesHelpersFixed.filter(o => rest.indexOf(o.id) === -1);
            },
            async createExpense(){
                await this.$set(this.expense,'ref',this.selectedHelper);
                await this.$set(this.expense,'date',this.date.start);
                await this.$store.dispatch('createExpenses',this.expense);
            },
            async updateExpense(){

            },
            async updateHelper(ob){
                console.log('ob',ob);

            },
            async confirmDeleteExpense(){

            },

            async confirmDeleteHelpers(){

            },

            thousands(val) {
                return val ? val.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ') : null;
            },
            thousandsOut(key) {
                let value = Number(event.target.value.replace(/ /g, ''));
                this.$store.commit('EXPENSE_FIXED_VALUE',{key,value})
            },

        },
        created() {
            this.$store.dispatch('readExpensesHelpers');
            this.$store.dispatch('readExpenses',moment().format('YYYY-MM'));
            this.bus.$on('fixed-expenses-open', async (v) => {
                    await this.$store.dispatch('readExpenses',this.date.start);
                    console.log('1',this.date.start);
                    // this.$set(this.$data.'')
                    this.fixedModal = true;
                    console.log('2');
            })

        }
    }
</script>


