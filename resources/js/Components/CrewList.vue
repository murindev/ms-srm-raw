<template>
    <div style="user-select: none">
         <Row :space="10">
             <Cell width="11">Команда спецов</Cell>
             <Cell width="4">Период</Cell>
             <Cell width="4"><a @click="bus.$emit('fixed-expenses-open')">Ежемесячные расходы</a></Cell>
             <Cell width="3"><a @click="bus.$emit('daily-expenses-open')">Ежедневные расходы</a></Cell>
          </Row>

         <Row :space="10" >
             <Cell width="11">
                 <TreePicker v-if="h.roles_obj" :option="{keyName: 'id', titleName: 'name', dataMode: 'list', datas:rolesCategory}" multiple v-model="role" @change="refresh()" @choose="refresh()" />
             </Cell>
             <Cell width="4">
                 <DateFullRangePicker
                         v-model="period"
                         :layout="['year', 'quarter', 'month', 'week', 'date', 'customize']"
                         :startWeek="1"
                         @change="fetchCrewByImplement"
                         @confirm="fetchCrewByImplement"
                 />
             </Cell>
             <Cell width="4">
                 <fixed-expenses/>
             </Cell>
             <Cell width="3">
                 <daily-expenses/>
             </Cell>

          </Row>
        <div class="state-list">
            <span v-for="state in stafferStateList" :style="{background:state.color}">{{state.title}}</span>
        </div>

        <table class="table crew-tbl">

            <thead>
            <tr>
                <th>Сотрудник:</th>
                <th>Должность:</th>
                <th>Оклад:</th>
                <th style="width: 20px;">
                    <div class="flex" style="max-width: 842px;">
<!-- Календарь:-->
                        <fieldset>
                            <Checkbox v-model="withHours">Часы</Checkbox>
                            <Checkbox v-model="withPercents">%</Checkbox>
                            <Checkbox  v-model="withRate">Оклад</Checkbox>
                        </fieldset>
                        <!--
                        v-tooltip theme="white" :content="'Выработка в часах'" placement="right"
                        v-tooltip :content="'Заработанные проценты'" placement="right"
                        v-tooltip :content="'Выработка по окладу'" placement="right"
                        v-tooltip placement="right" :content="'Количество отработанных часов'"
                        v-tooltip placement="right" :content="'Количество отработанных дней'"
                        -->
                    </div>

                </th>
                <th colspan="2" >Часов:</th>
                <th colspan="2" >Дней:</th>
                <th v-tooltip theme="white" :content="'Удержания'">Уд-я:</th>
                <th>Комментарии:</th>
            </tr>

            </thead>

            <tbody>
<!--SUB HEADER-->
            <tr>
                <td/>
                <td/>
                <td/>
                <td :rowspan="Object.keys(users()).length + 16" >
                    <div class="calendar-wrapper">
                        <div class="calendar-tbl">
                            <div class="calendar-col"
                                 v-for="day in between(period.start,period.end)"
                                 :class="{'weekends':showDate(day).format('ddd') === 'вс' || showDate(day).format('ddd') === 'сб'}"
                            >
                                <div class="calendar-col_cell" >
                                    <div class="calendar-col_top">
                                        {{showDate(day).format('DD')}}
                                    </div>
                                    <div class="calendar-col_bottom">
                                        {{showDate(day).format('ddd')}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="calendar-tbl" v-for="(dates,id) in stafferByDays">

                            <div class="calendar-col"
                                 v-for="(dayInfo,date) in dates"
                                 :class="{'weekends': isWeekend(date)}"
                                 :style="highlight(date,id),stateStyle(date,id)"
                                 @click="openMemberModal(date,id)"
                                 @mouseenter="highlightOn(date,id)"
                                 @mouseleave="highlightOff()"
                            >
                                <span
                                        v-if="stafferByDays[id][date].work_start" class="start"
                                        :class="{'approved': stafferByDays[id][date].work_start_proof}"/>
                                <span
                                        v-if="stafferByDays[id][date].work_finish" class="finish"
                                        :class="{'approved': stafferByDays[id][date].work_finish_proof}"/>
                                <div class="calendar-col_day">
                                    <div class="calendar-col_top" v-if="withHours && stafferByDays[id][date].workHours">{{stafferByDays[id][date].workHours}}</div>
                                    <div class="calendar-col_down"
                                         v-if="!withPercents && withRate"
                                         v-html="thousands(stafferByDays[id][date].rate)"></div>
                                    <div class="calendar-col_down"
                                         v-if="withPercents && !withRate"
                                         v-html="thousands(stafferByDays[id][date].perCent)"></div>
                                    <div class="calendar-col_down"
                                         v-if="withPercents && withRate"
                                         v-html="thousands(stafferByDays[id][date].perCent + stafferByDays[id][date].rate)"></div>
                                </div>
                            </div>

                        </div>

                        <div class="calendar-tbl">
                            <div class="calendar-col"
                                 v-for="day in between(period.start,period.end)"
                                 :class="{'weekends':showDate(day).format('ddd') === 'вс' || showDate(day).format('ddd') === 'сб'}"
                            >
                                <div class="calendar-col_cell" >
                                    <div class="calendar-col_top">
                                        {{showDate(day).format('DD')}}
                                    </div>
                                    <div class="calendar-col_bottom">
                                        {{showDate(day).format('ddd')}}
                                    </div>
                                </div>
                            </div>
                        </div>
<!--Выплаты-->
                        <div class="calendar-tbl">
                            <div class="calendar-col" v-for="(staffer,day) in dayByStaffers">
                                {{thousands(dayPayoff(day).sum)}}
                            </div>
                        </div>
                        <div class="calendar-tbl" v-if="salarySwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers">
                                {{thousands(dayPayoff(day).rate)}}
                            </div>
                        </div>
                        <div class="calendar-tbl " v-if="salarySwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers">
                                {{thousands(dayPayoff(day).percent)}}
                            </div>
                        </div>

<!--Доходы-->
                        <div class="calendar-tbl ">
                            <div class="calendar-col" v-for="(staffer,day) in dayByStaffers">
                                {{thousands(receipt(day))}}
                            </div>
                        </div>
                        <div class="calendar-tbl" v-if="gainSwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]">
                                {{thousands(dayTotalGain[day].cash)}}
                            </div>
                        </div>
                        <div class="calendar-tbl" v-if="gainSwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]">
                                {{thousands(dayTotalGain[day].cashless)}}
                            </div>
                        </div>
<!--Kbyrb-->
                        <div class="calendar-tbl">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]">
                                <a v-if="dayTotalGain[day].emptyCost.length" href="javascript:"
                                   @click="emptyActs(dayTotalGain[day].emptyCost)">
                                    {{dayTotalGain[day].emptyCost.length}}
                                </a>
                                <template v-else>
                                    {{dayTotalGain[day].emptyCost.length}}
                                </template>
                            </div>
                        </div>

                        <!--1-->
                        <div class="calendar-tbl">
                            <div class="calendar-col" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryFixed(day).sum)"></div>
                        </div>
                        <div class="calendar-tbl" v-if="fixedSwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryFixed(day).cash)"></div>
                        </div>
                        <div class="calendar-tbl" v-if="fixedSwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryFixed(day).cashless)"></div>
                        </div>
                        <!--2-->
                        <div class="calendar-tbl">
                            <div class="calendar-col" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryDaily(day).sum)"></div>
                        </div>
                        <div class="calendar-tbl" v-if="dailySwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryDaily(day).cash)"></div>
                        </div>
                        <div class="calendar-tbl" v-if="dailySwitch">
                            <div class="calendar-col op" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]" v-html="thousands(summaryDaily(day).cashless)"></div>
                        </div>
<!--Расходы-->
                        <div class="calendar-tbl ">
                            <div class="calendar-col" v-for="(staffer,day) in dayByStaffers" v-if="dayTotalGain[day]">
                                {{thousands(receipt(day) - dayPayoff(day).sum - summaryFixed(day).sum - summaryDaily(day).sum)}}
                            </div>
                        </div>

                    </div>
                </td>
                <td v-tooltip theme="white" :content="'План'">П</td>
                <td v-tooltip theme="white" :content="'Факт'">Ф</td>
                <td v-tooltip theme="white" :content="'План'">П</td>
                <td v-tooltip theme="white" :content="'Факт'">Ф</td>
            </tr>
<!--TABLE CONTENT-->
            <tr v-for="(user,userKey) in users()">
                <td class="no-wrap"  :style="highlight('',user.id)">{{user.name}}({{user.id}})</td>
                <td>{{h.roles_obj[user.role_id].name}} </td>
                <td>{{thousands(user.salary)}}</td>
                <td>21</td>
                <td>21</td>
                <td>{{user.timeline}}</td>
                <td>4</td>
                <td class="no-wrap">13 285</td>
                <td>здесь будет комментарий</td>
            </tr>
            <tr>
                <td>ИТОГИ</td>
                <td></td>
                <td/>
                <td colspan="4">За период</td>
<!--                <td/>-->
<!--                <td/>-->
<!--                <td/>-->
<!--                <td/>-->
            </tr>
            <tr>
                <td><a @click="salarySwitch = !salarySwitch">Выплаты</a></td>
                <td>ВСЕГО:</td>
                <td/>
                <td>{{summarySalaryTotal(crewPeriodArr)}}</td>
                <td/>
                <td/>
                <td/>
                <td/>
            </tr>
            <tr class="op" v-if="salarySwitch">
                <td/>
                <td>в том числе ОКЛАД</td>
                <td/>
                <td>{{summarySalaryRate(crewPeriodArr)}}</td>
                <td/>
                <td/>
                <td/>
                <td/>
            </tr>
            <tr class="op" v-if="salarySwitch">
                <td/>
                <td>в том числе %</td>
                <td/>
                <td>{{summarySalaryPercent(crewPeriodArr)}}</td>
                <td/>
                <td/>
                <td/>
                <td/>
            </tr>
<tr>
    <td><a @click="gainSwitch = !gainSwitch">Выполнено</a> </td>
    <td>ВСЕГО: </td>
    <td/>
    <td>{{summaryTotal(crewPeriodArr)}}</td>
    <td/>
    <td/>
    <td/>
    <td/>
</tr>
<tr class="op" v-if="gainSwitch">
    <td/>
    <td> в том числе НАЛ </td>
    <td/>
    <td>{{summaryCash(crewPeriodArr)}}</td>
    <td/>
    <td/>
</tr>
<tr class="op" v-if="gainSwitch">
    <td/>
    <td> в том числе БЕЗНАЛ </td>
    <td/>
    <td>{{summaryCashless(crewPeriodArr)}}</td>
    <td/>
    <td/>
    <td/>
    <td/>
</tr>
<tr>
    <td/>
    <td> акты с нулевой стоимостью</td>
    <td/>
    <td/>
    <td/>
    <td/>
    <td/>
    <td/>
</tr>




<tr>
    <td><a @click="fixedSwitch = !fixedSwitch">Ежемесячные расходы</a> </td>
    <td>ВСЕГО: </td>
    <td/>
    <td><template v-if="Object.keys(totalFixed).length">{{totalPeriodFixed(crewPeriodArr).sum}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
    <td/>
    <td/>
</tr>
<tr class="op" v-if="fixedSwitch">
    <td/>
    <td> в том числе НАЛ </td>
    <td/>
    <td><template v-if="Object.keys(totalFixed).length">{{totalPeriodFixed(crewPeriodArr).cash}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
</tr>
<tr class="op"  v-if="fixedSwitch">
    <td/>
    <td> в том числе БЕЗНАЛ </td>
    <td/>
    <td><template v-if="Object.keys(totalFixed).length">{{totalPeriodFixed(crewPeriodArr).cashless}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
    <td/>
    <td/>
</tr>

<tr>
    <td><a @click="dailySwitch = !dailySwitch">Ежедневные расходы</a></td>
    <td>ВСЕГО: </td>
    <td/>
    <td><template v-if="Object.keys(totalDaily).length">{{totalPeriodDaily(crewPeriodArr).sum}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
    <td/>
    <td/>
</tr>
<tr class="op"  v-if="dailySwitch">
    <td/>
    <td> в том числе НАЛ </td>
    <td/>
    <td><template v-if="Object.keys(totalDaily).length">{{totalPeriodDaily(crewPeriodArr).cash}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
</tr>
<tr class="op"  v-if="dailySwitch">
    <td/>
    <td> в том числе БЕЗНАЛ </td>
    <td/>
    <td><template v-if="Object.keys(totalDaily).length">{{totalPeriodDaily(crewPeriodArr).cashless}}</template></td>
<!--    <td/>-->
    <td/>
    <td/>
    <td/>
    <td/>
</tr>











<tr>
    <td> Прибыль без учета расходов</td>
    <td/>
    <td/>
    <td>{{summaryTotal(crewPeriodArr) - summarySalaryTotal(crewPeriodArr) - totalPeriodFixed(crewPeriodArr).sum - totalPeriodDaily(crewPeriodArr).sum}}</td>
    <td/>
    <td/>
    <td/>
    <td/>
</tr>

            </tbody>

        </table>

        <staffer-modal/>

        <Modal v-model="emptyActModal"  :hasCloseIcon="true">
            <div v-for="link in emptyActsLink">
                <a target="_blank" :href="link">Исправить</a>
            </div>

        </Modal>

    </div>
</template>


<script>

    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    // import {bus} from "../bus";
    import moment from 'moment';
    moment.locale('ru');
    require('moment-weekday-calc');


    import dayPayment from '../modules/crew/dayPayment'


    console.log(moment().isoWeekdayCalc([2015,0,1],[2015,5,30],[1,2,3,4,5]));

    import StafferModal from "../Components/Crew/StafferModal"
    import FixedExpenses from '../Components/Crew/FixedExpenses'
    import DailyExpenses from '../Components/Crew/DailyExpenses'

    export default {
        name: "CrewList",
        data(){
            return {
                role: [1,2,3,4,5,6,7,8,9],
                now: new Date,
                startWeek: 1,
                layout: ['year', 'quarter', 'month', 'week', 'date', 'customize'],
                // table decoration
                horizontal: null,
                vertical: null,
                // view types
                withHours: true,
                withPercents: true,
                withRate: true,
                // MODAL
                userModal:false,
                crewMember: {},
                // arrays
                payOffs:{},
                dayTotalGain:{},
                //emptyActModal
                emptyActModal:false,
                emptyActsLink: [],
                total: {},
                totalFixed:{},
                totalDaily:{},

                //------
                salarySwitch:false,
                gainSwitch:false,
                fixedSwitch:false,
                dailySwitch:false,

            }
        },
        components:{
            StafferModal,FixedExpenses,DailyExpenses
        },
        computed:{
            period:{
                get(){return this.$store.getters.crewPeriod;},
                set(value){this.$store.commit('CREW_PERIOD',value);}
            },
            roles:{
                get(){return this.$store.getters.roles;},
                set(value){this.$store.commit('CREW_PERIOD',value);}
            },
            ...mapGetters(['crew','h','daysInMonth','crewScheduling','staffer','staffers','stafferStateList','crewPeriodArr','dayByStaffers','stafferByDays']),
            // ...mapMutations(['STAFFER'])
        },
        methods: {
            ...mapActions(['crewCreate','crewUpdate','fetchCrewByImplement']),
            // calculations

            summaryFixed(date){
                let month = date.slice(0, 7),
                    daysInMonth = moment(month, "YYYY-MM").daysInMonth(),
                    exp = this.crew.expFixed[month],
                    cash = 0,
                    cashless = 0,
                    sum = 0;
                for(let e in exp){
                   let val = exp[e].value ? exp[e].value : 0;
                    sum = sum + val;
                    exp[e].cash ? cash = cash + val : cashless = cashless + val;
                }
                let ob = {
                    sum: Math.ceil(sum/daysInMonth),
                    cash: Math.ceil(cash/daysInMonth),
                    cashless: Math.ceil(cashless/daysInMonth)
                };
                this.totalFixed[date] = ob;
                return  ob;
            },
            summaryDaily(date){

                   let
                    exp = this.crew.expDaily[date],
                    cash = 0,
                    cashless = 0,
                    sum = 0;
                for(let e in exp){
                    let val = exp[e].value ? exp[e].value : 0;
                    sum = sum + val;
                    exp[e].cash ? cash = cash + val : cashless = cashless + val;
                }
                let ob = {
                    sum: Math.ceil(sum),
                    cash: Math.ceil(cash),
                    cashless: Math.ceil(cashless)
                };
                this.totalDaily[date] = ob;
                return ob;
            },


             summarySalaryRate(arPeriod){
                let salary_rate = 0;
                for(let date of  arPeriod){salary_rate = salary_rate + this.payOffs[date].rate;}
                return salary_rate;
            },
            summarySalaryPercent(arPeriod){
                let salary_percent = 0;
                for(let date of  arPeriod){salary_percent = salary_percent + this.payOffs[date].percent;}
                return salary_percent;
            },
            summarySalaryTotal(arPeriod){
                let salary_total = 0;
                for(let date of  arPeriod){salary_total = salary_total + this.payOffs[date].sum;}
                return salary_total;
            },

             summaryTotal(arPeriod){
                let total = 0;
                for(let date of arPeriod){
                    total = total + this.dayTotalGain[date].sum;
                }
                return Math.ceil(total);
            },
            summaryCash(arPeriod){
                let cash = 0;
                for(let date of arPeriod){
                    cash = cash + this.dayTotalGain[date].cash;
                }
                return Math.ceil(cash);
            },
            summaryCashless(arPeriod){
                let cashless = 0;
                for(let date of arPeriod){
                    cashless = cashless + this.dayTotalGain[date].cashless;
                }
                return Math.ceil(cashless);
            },

            emptyActs(arr){
                this.emptyActsLink = arr;
                this.emptyActModal = true;
            },
            dayPayoff(date){
                let rate = 0;
                let percent = 0;
                for (let s in this.dayByStaffers[date]) {
                    rate = rate + this.dayByStaffers[date][s].rate;
                    percent = percent + this.dayByStaffers[date][s].perCent;
                }
                this.payOffs[date] = {
                    rate: rate,
                    percent: percent,
                    sum: rate + percent,
                };
                return {
                    rate: rate,
                    percent: percent,
                    sum: rate + percent,
                };
            },
            receipt(date){
                let byDate = this.crew.byDate[date], sum = 0,emptyCost = [];
                let cash = 0;
                let cashless = 0;
                if(byDate){
                    for(let imp of byDate) if(this.crew.acts[imp.parent].finished){
                        let actID = imp.parent;
                        let lidID = imp.lid_id;

                        let cost = this.crew.actCost[imp.parent];

                        if(cost === 0){
                            emptyCost.push("/layout/lid/"+lidID+"/edit?open_act="+actID);
                        }
                        sum = sum + cost;
                        if(this.crew.lids[lidID] && this.crew.lids[lidID].customer_payment === 0){
                            cashless = cashless + cost;
                        } else  {
                            cash = cash + cost;
                        }
                    }
                }
                this.dayTotalGain[date] = {sum,cash,cashless,emptyCost}
                return sum;
            },


            totalPeriodFixed(arPeriod){
                let sum = 0;
                let cash = 0;
                let cashless = 0;

                for(let date of arPeriod) if(Object.keys(this.totalFixed).indexOf(date) !== -1) {
                    sum = sum + this.totalFixed[date].sum;
                    cash = cash + this.totalFixed[date].cash;
                    cashless = cashless + this.totalFixed[date].cashless;
                }
                return {
                    sum,cash,cashless
                };
            },
            totalPeriodDaily(arPeriod){
                let sum = 0;
                let cash = 0;
                let cashless = 0;
                for(let date of arPeriod) if(Object.keys(this.totalDaily).indexOf(date) !== -1) {
                    sum = sum + this.totalDaily[date].sum;
                    cash = cash + this.totalDaily[date].cash;
                    cashless = cashless + this.totalDaily[date].cashless;
                }
                return {
                    sum,cash,cashless
                };
            },
            //---
            refresh(){
                this.$store.dispatch('fetchCrewByImplement')
            },
            users(){
                let users = {};
                for(let u in this.h.users) if(this.h.users[u].active && this.role.indexOf(this.h.users[u].role_id) !== -1){
                    users[u] = this.h.users[u]
                }
                this.$store.commit('STAFFERS',users);
                return users;
            },
            isWeekend(date){return [0,6].indexOf(moment(date).day()) !== -1;},
            highlight(date,user){
                if(this.horizontal === user ||  this.vertical === date) {
                    return 'opacity: 1; color:#45b984'
                }
            },
            highlightOn(date,id){
                this.horizontal = id;
                this.vertical = date;
            },
            highlightOff(){
                this.horizontal = null;
                this.vertical = null;
            },
            stateStyle(date,id){
                if(id in this.crewScheduling && this.crewScheduling[id][date] !== undefined){
                    let state = this.crewScheduling[id][date].state;
                    for( let i of this.stafferStateList) if(i.key === state) {
                        return 'background:' + i.color;
                    }
                } else {
                    return '';
                }
            },

            async openMemberModal(date,user_id){
                if(this.crewScheduling[user_id] === undefined ){
                    await this.$store.dispatch('crewCreate',{user_id, date}); // 1
                } else if(user_id in this.crewScheduling && this.crewScheduling[user_id][date] === undefined){
                    await this.$store.dispatch('crewCreate',{user_id:user_id, date:date}); //2
                }
                await this.$store.commit('STAFFER', {
                    id: user_id,
                    date: date,
                    open: true
                });
                // bus.$emit('staffer_modal_open',)
            },


            // ******


            workingDaysInMonth(date,timeline){
                let start = moment(date).startOf('month').format("YYYY-MM-DD");
                let end = moment(date).endOf('month').format("YYYY-MM-DD");
                let arTimeline = Array.from({length: timeline}, (v, k) => k+1);
                return moment().isoWeekdayCalc(start,end,arTimeline);
            },
            stafferDayRate(date,staffer){
                let rate = staffer.salary / this.workingDaysInMonth(date,staffer.timeline);
                return Math.floor(rate);
            },
            rolesCategory(){
                let res = [];
                for(let roleKey in this.h.roles_obj){
                    res.push({id:this.h.roles_obj[roleKey].id, name:this.h.roles_obj[roleKey].name});
                }
                return res;
            },
            between(start,end){return moment(end).diff(moment(start), 'days')},
            showDate(day){return moment(new Date(this.period.start)).add(day-1, 'days')},
            thousands(val) {
                val = Math.ceil(val);
                return String(val).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')
            },
        },
        created() {
            this.$store.dispatch('fetchCrewByImplement');
            window.onload = function () {
                let scr = $(".calendar-wrapper");
                scr.mousedown(function () {
                    let startX = this.scrollLeft + event.pageX;
                    let startY = this.scrollTop + event.pageY;
                    scr.mousemove(function () {
                        this.scrollLeft = startX - event.pageX;
                        this.scrollTop = startY - event.pageY;
                        return false;
                    });
                });
                $(window).mouseup(function () {
                    scr.off("mousemove");
                });
            }

        },
        mounted() {
            this.$store.dispatch('fetchHelpers');

        }
    }
</script>

<style lang="scss">
    .h-notify-body {
        z-index: 10500!important;
    }
    .userModalContainer {
        min-width: 600px;
        margin-right: 30px;
    }
    .state-list {
        position: absolute;
        z-index: 10000;
        left: 293px;
        top: -54px;
    }
    .state-list span {
        display: inline-block;
        padding: 3px 5px;
        border: 1px solid white;
        margin: 0 3px;

    }
    .i-start {

    }
</style>
