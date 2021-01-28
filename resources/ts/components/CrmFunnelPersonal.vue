<template>
    <div>
        <div>
            <DateFullRangePicker v-if="!byPeriod"
                    class="roof" style="left: 50px" @change="showGraphs(period)"
                    v-model="period" :layout="['year', 'quarter', 'month', 'week','date', 'customize']" :startWeek="1"
            />
 <!--           <Checkbox class="roof" style="left: 305px; top: -53px;" :trueValue="true" :falseValue="false" @change="showGraphs(null)"
                      v-model="byPeriod">За весь период
            </Checkbox>-->

<!--            <Button color="primary" icon="h-icon-left" class="roof" style="left: 605px; top: -53px;" @click="ttt()" >dasdasd</Button>-->
<!--            <Button color="primary" icon="h-icon-left" class="roof" style="left: 805px; top: -53px;" @click="ddd()" >period</Button>-->

<div class="charts-table-wrapper">


            <table class="charts-table">
                <tbody>
                <tr>
                    <td colspan="5"><h4>По компании в целом:
                        <span v-if="byPeriod" class="h-tag h-tag-bg-red">за весь период от начала деятельности</span>
                        <span v-else class="h-tag h-tag-bg-red">{{period.start}} - {{period.end}}</span>
                    </h4>
                    </td>
                </tr>
                <tr class="cell-title">
                    <td colspan="2">Статистика по звонкам</td>
                    <td>По источникам</td>
                    <td>По клиентам</td>
                    <td>Финансы (новые обращения)</td>
                    <td>Финансы (чистые итоги)</td>
                </tr>
                <tr style="background: white">
                    <!-- Статистика по звонкам  -->
                    <td>
                        <div class="chart-coll">
                            <pie-chart v-if="!loading" :chart-data="{
                       datasets: [ {data: stat_callStatus.common.map(i => i.amount), backgroundColor: stat_callStatus.common.map(i => i.color)} ],
                       labels: stat_callStatus.common.map(i => i.name)
                   }"/>
                        </div>
                    </td>
                    <!-- Статистика по звонкам table  -->
                    <td>
                        <div class="table-coll" v-if="!loading">
                            <table>
                                <tfoot>
                                <tr>
                                    <th>Итого</th>
                                    <th>
                                        {{stat_callStatus.commonCnt}}
                                    </th>
                                    <th></th>
                                </tr>
                                </tfoot>

                                <tbody>
                                <tr v-for="status in stat_callStatus.common">
                                    <td>
                                        <Button class="h-btn-xs" :style="{background: status.color, color: 'white'}">
                                            {{status.name}}
                                        </Button>
                                    </td>
                                    <td>
                                        <Button class="h-btn-xs" :style="{background: status.color, color: 'white'}">
                                            {{status.amount}}
                                        </Button>
                                    </td>
                                    <td>
                                        <Button class="h-btn-xs" :style="{background: status.color, color: 'white'}">
                                            {{(status.amount/(stat_callStatus.commonCnt/100)).toFixed(2)}}
                                        </Button>
                                    </td>
                                </tr>
                                </tbody>

                            </table>
                        </div>

                    </td>
                    <!-- По источникам -->
                    <td>
                        <div class="chart-coll funnel" v-if="!loading" style="">
                            <funnel-chart
                                    :labels="['Всего обращений', 'Заказы']"
                                    :subLabels="stat_appStatus.common.map(i => i.name)"
                                    :values="[stat_appStatus.common.map(i => i.amount),stat_appStatus.common.map(i => i.order)]"
                                    :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF'],['#A0F9FF', '#7795FF']]"
                                    direction="vertical"
                                    gradientDirection="horizontal"
                                    height="200"
                                    width="300"
                            />
                        </div>
                    </td>
                    <!--  По клиентам   -->
                    <td>
                        <div class="chart-coll funnel" v-if="!loading">
                            <funnel-chart
                                    :labels="['Всего обращений', 'Заказы']"
                                    :subLabels="stat_customerStatus.common.map(i => i.short)"
                                    :values="[stat_customerStatus.common.map(i => i.amount),stat_customerStatus.common.map(i => i.order)]"
                                    :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF'],['#A0F9FF', '#7795FF']]"
                                    direction="vertical"
                                    gradientDirection="horizontal"
                                    height="200"
                                    width="300"
                            />
                        </div>
                    </td>
                    <!-- Финансы (новые обращения)  -->
                    <td>
                        <div class="chart-coll funnel" v-if="!loading">
                            <funnel-chart
                                    :labels="['Всего заказов на сумму', 'Выполнено']"
                                    :subLabels="['Нал','Безнал']"
                                    :values="[
                                        [Math.trunc(fin_activity.common.total.cash),Math.trunc(fin_activity.common.total.cashless)],
                                        [Math.trunc(fin_activity.common.implemented.cash),Math.trunc(fin_activity.common.implemented.cashless)]
                                        ]"
                                    :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF']]"
                                    direction="vertical"
                                    gradientDirection="horizontal"
                                    height="200"
                                    width="300"
                            />
                        </div>
                    </td>
                    <!-- Финансы (чистые итоги)  -->
                    <td>
                        <div class="chart-coll funnel" v-if="!loading && FF">

                            <table class="charts-table small">

                                <tbody>
                                <tr class="thead">
                                    <td colspan="3">Приход/расход финансов</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="">Поступления</td>
                                    <td colspan="">Отчисления</td>
                                </tr>
                                <tr>
                                    <td><span class="h-tag h-tag-bg-blue">Нал</span></td>
                                    <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinTotal.byFinTotal.debitCash).toLocaleString()}}</span></td>
                                    <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinTotal.byFinTotal.creditCash).toLocaleString()}}</span></td>
                                </tr>
                                <tr>
                                    <td><span class="h-tag h-tag-bg-blue">Безнал</span></td>
                                    <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinTotal.byFinTotal.debitCashless).toLocaleString()}}</span></td>
                                    <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinTotal.byFinTotal.creditCashless).toLocaleString()}}</span></td>
                                </tr>


                                <tr class="tfoot">
                                    <td>Итог:</td>
                                    <td colspan="2">{{Math.trunc(calcFinancesTotal()).toLocaleString()}}</td>
                                </tr>

                                <tr class="thead">
                                    <td colspan="3">Выработка по актам</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="">Завершено</td>
                                    <td colspan="">По плану</td>
                                </tr>

                                <tr>
                                    <td><span class="h-tag h-tag-bg-blue">Нал</span></td>
                                    <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinImplement.byImplementTotal.finishedCash).toLocaleString()}}</span></td>
                                    <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinImplement.byImplementTotal.cash).toLocaleString()}}</span></td>
                                </tr>
                                <tr>
                                    <td><span class="h-tag h-tag-bg-blue">Безнал</span></td>
                                    <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinImplement.byImplementTotal.finishedCashless).toLocaleString()}}</span></td>
                                    <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinImplement.byImplementTotal.cashless).toLocaleString()}}</span></td>
                                </tr>
                                <tr class="tfoot">
                                    <td>Итог:</td>
                                    <td>{{Math.trunc(FF.byFinImplement.byImplementTotal.finishedCash + FF.byFinImplement.byImplementTotal.finishedCashless).toLocaleString()}}</td>
                                    <td>{{Math.trunc(FF.byFinImplement.byImplementTotal.cash + FF.byFinImplement.byImplementTotal.cashless).toLocaleString()}}</td>
                                </tr>

                                </tbody>
                            </table>

                        </div>
                    </td>

                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <template v-if="ready && temp">
                            {{calcData.lid.common.actVolumeFinishedPrices.cashSum}} <br/>
                            {{calcData.lid.common.actVolumeFinishedPrices.cashlessSum}} <br/>
                        </template>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <!--МЕНЕДЖЕРЫ-->
                <template v-for="mgr in FD.managers" >
                    <tr>
                        <td colspan="4"><h3>{{getManagerField(mgr,'name')}} ({{mgr}})</h3></td>
                    </tr>
                    <tr>

                        <!--Статистика по звонкам-->
                        <td>
                            <div class="chart-coll" v-if="!loading">
                                <pie-chart :chart-data="{
                                            datasets: [ {
                                                data: stat_callStatus.byManagers[mgr].map(i => i.amount),
                                                backgroundColor: stat_callStatus.byManagers[mgr].map(i => i.color) } ],
                                            labels: stat_callStatus.byManagers[mgr].map(i => i.name),}"/>
                            </div>
                        </td>
                        <!--Статистика по звонкам таблица-->
                        <td>
                            <div class="table-coll" v-if="!loading">
                                <table>
                                    <tbody>
                                    <tr v-for="status in stat_callStatus.byManagers[mgr]">
                                        <td>
                                            <Button class="h-btn-xs"
                                                    :style="{background: status.color, color: 'white'}">{{status.name}}
                                            </Button>
                                        </td>
                                        <td>
                                            <Button class="h-btn-xs"
                                                    :style="{background: status.color, color: 'white'}">
                                                {{status.amount}}
                                            </Button>
                                        </td>
                                        <td>
                                            <Button class="h-btn-xs"
                                                    :style="{background: status.color, color: 'white'}">
                                                {{(status.amount/(stat_callStatus.byManagersCnt[mgr]/100)).toFixed(2)}}
                                            </Button>
                                        </td>
                                    </tr>
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th>Итого</th>
                                        <th v-if="ready">
                                            {{stat_callStatus.byManagersCnt[mgr]}}
                                        </th>
                                        <th></th>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>

                        </td>
                        <!--По источникам-->
                        <td>
                            <div class="chart-coll funnel" v-if="!loading">
                                <funnel-chart
                                        :labels="['Всего обращений', 'Заказы']"
                                        :subLabels="stat_appStatus.byManagers[mgr].map(i => i.name)"
                                        :values="[stat_appStatus.byManagers[mgr].map(i => i.amount),stat_appStatus.byManagers[mgr].map(i => i.order)]"
                                        :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF'],['#A0F9FF', '#7795FF']]"
                                        direction="vertical"
                                        gradientDirection="horizontal"
                                        height="200"
                                        width="300"
                                />
                            </div>
                        </td>
                        <!--По клиентам-->
                        <td>
                            <div class="chart-coll funnel" v-if="!loading">
                                <funnel-chart
                                        :labels="['Всего обращений', 'Заказы']"
                                        :subLabels="stat_customerStatus.byManagers[mgr].map(i => i.short)"
                                        :values="[stat_customerStatus.byManagers[mgr].map(i => i.amount),stat_customerStatus.byManagers[mgr].map(i => i.order)]"
                                        :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF'],['#A0F9FF', '#7795FF']]"
                                        direction="vertical"
                                        gradientDirection="horizontal"
                                        height="200"
                                        width="300"
                                />

                            </div>
                        </td>

                        <!--Финансы (новые обращения)-->
                        <td>
                            <div class="chart-coll funnel" v-if="!loading">

                                <funnel-chart v-if="fin_activity.byManagers.total[mgr]"
                                        :labels="['Всего заказов на сумму', 'Выполнено']"
                                        :subLabels="['Нал','Безнал']"
                                        :values="[
                                        [Math.trunc(fin_activity.byManagers.total[mgr].cash),Math.trunc(fin_activity.byManagers.total[mgr].cashless)],
                                        [Math.trunc(fin_activity.byManagers.implemented[mgr].cash),Math.trunc(fin_activity.byManagers.implemented[mgr].cashless)]
                                        ]"
                                        :colors="[['#FFB178','#FF3C8E'],['#A0BBFF', '#EC77FF']]"
                                        direction="vertical"
                                        gradientDirection="horizontal"
                                        height="200"
                                        width="300"
                                />

                            </div>
                        </td>
                        <!--Финансы (чистые итоги)-->
                        <td>
                            <div class="chart-coll funnel" v-if="!loading && FF">

                                <table class="charts-table small" >

                                    <tbody>
                                    <template v-if="FF.byFinTotal.byFinManagers.hasOwnProperty(mgr)">
                                        <tr class="thead">
                                            <td colspan="3">Приход/расход финансов</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="">Поступления</td>
                                            <td colspan="">Отчисления</td>
                                        </tr>
                                        <tr>
                                            <td><span class="h-tag h-tag-bg-blue">Нал</span></td>
                                            <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinTotal.byFinManagers[mgr].debitCash).toLocaleString()}}</span></td>
                                            <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinTotal.byFinManagers[mgr].creditCash).toLocaleString()}}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="h-tag h-tag-bg-blue">Безнал</span></td>
                                            <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinTotal.byFinManagers[mgr].debitCashless).toLocaleString()}}</span></td>
                                            <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinTotal.byFinManagers[mgr].creditCashless).toLocaleString()}}</span></td>
                                        </tr>

                                        <tr class="tfoot">
                                            <td>Итог:</td>
                                            <td colspan="2">
                                                {{Math.trunc(Number(FF.byFinTotal.byFinManagers[mgr].debitCash) + Number(FF.byFinTotal.byFinManagers[mgr].debitCashless) - Number(FF.byFinTotal.byFinManagers[mgr].creditCash) - Number(FF.byFinTotal.byFinManagers[mgr].creditCashless) ).toLocaleString()}}

                                            </td>
                                        </tr>
                                    </template>
                                    <template v-if="FF.byFinImplement.byImplementManagers.hasOwnProperty(mgr)">
                                        <tr class="thead">
                                            <td colspan="3">Выработка по актам</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="">Завершено</td>
                                            <td colspan="">По плану</td>
                                        </tr>

                                        <tr>
                                            <td><span class="h-tag h-tag-bg-blue">Нал</span></td>
                                            <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].finishedCash).toLocaleString()}}</span></td>
                                            <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].cash).toLocaleString()}}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="h-tag h-tag-bg-blue">Безнал</span></td>
                                            <td><span class="h-tag h-tag-bg-blue">{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].finishedCashless).toLocaleString()}}</span></td>
                                            <td><span class="h-tag h-tag-bg-gray">{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].cashless).toLocaleString()}}</span></td>
                                        </tr>
                                        <tr class="tfoot">
                                            <td>Итог:</td>
                                            <td>{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].finishedCash + FF.byFinImplement.byImplementManagers[mgr].finishedCashless).toLocaleString()}}</td>
                                            <td>{{Math.trunc(FF.byFinImplement.byImplementManagers[mgr].cash + FF.byFinImplement.byImplementManagers[mgr].cashless).toLocaleString()}}</td>
                                        </tr>
                                    </template>

                                    </tbody>
                                </table>

                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
</div>
        </div>
        <Loading text="Думаю..." :loading="loading"/>
    </div>

</template>


<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'
    import {namespace} from 'vuex-class'
    import * as moment from "moment";
    import Axios from 'axios';
    import {IAppStatus, ICallStatus, ICrmStatistic} from "../types/crm.statistic";

    const CrmStat = namespace('CrmStatistic');

    const Lid = namespace('Lid')

    const Funnel = namespace('Funnel');
    const Customer = namespace('Customer');

    import LineChart from "./charts/Line.vue";
    import BarsChart from "./charts/Bars.vue";
    import PieChart from "./charts/Pie.vue";

    import FunnelChart from "./charts/Funnel.vue"
    import {IHelperUsers, IArrHelper} from "../types/helper";
    import {IAct} from "../types/act";
    import {IFinance} from "../types/finance";
    import {IImplement} from "../types/implement";
    import {IVolume} from "../types/volume";
    import {ILid} from "../types/lid";
    import {IFunnel, IFunnelByLid, IFunnelData, IFunnelFinances} from "../types/funnel";
    import {IIdName} from "../types/Global";
    import {key} from "flatpickr/dist/types/locale";


    @Component({
        components: {
            LineChart, BarsChart, PieChart, FunnelChart
        },

    })
    export default class CrmFunnelPersonal extends Vue {

        @Prop() h!: IArrHelper;


        public temp: boolean = false
        public ready = false;
        public loading: boolean = true;
        public byPeriod = false;


        public period = {
            start: moment().startOf('month').format('YYYY-MM-DD'),
            end: moment().endOf('month').format('YYYY-MM-DD'),
            type: 'month'
        }

        @Funnel.State('funnelData') FD!: IFunnelData
        @Funnel.Action('fetchFunnelData') fetchFunnelData!: (date:any) => void

        @Funnel.State('funnelFinances') FF!: IFunnelFinances
        @Funnel.Action('fetchFunnelFinances') fetchFunnelFinances!: (date: any) => void


        @CrmStat.Getter('getCallStatus') callStatus!: ICallStatus[];
        @CrmStat.Getter('getAppStatus') appStatus!: IAppStatus[];
        @Customer.State('status') customerStatus!: IIdName[];


// data
        public stat_callStatus = {
            common: {},
            commonCnt: 0,
            byManagers: {},
            byManagersCnt: {},
        }
        public stat_appStatus = {
            common: {},
            byManagers: {},
        }

        public stat_customerStatus = {
            common: {},
            byManagers: {},
        }

        public fin_activity = {
            common: {
                total: {
                    cash:0,
                    cashless:0,
                },
                implemented: {
                    cash:0,
                    cashless:0,
                }

            },
            byManagers: {},
        }





// COMMON METHODS

        objSum(ob:{[key: string ]:number}) {
            return Object.keys(ob).reduce((acc, i) => acc + ob[i], 0)
        }
        arrSum(arr: [], key: string) {
            return arr.reduce((acc, i) => acc + i[key] || 0, 0)
        }

        getManagerField(id: number, field: string) {
            this.h.users.push({id:9999,name:'Старые менеджеры'})
            let user = this.h.users.find(u => u.id == id)
            return user ? user[field as keyof IHelperUsers] : '';
        }
// METHODS

        async engine(){
            await this.set_stat_callStatus()
            await this.set_stat_appStatus()
            await this.set_stat_customerStatus()

            await this.set_fin_activity()
        }


        set_stat_callStatus() {
            let commonCnt = 0;



            this.stat_callStatus.common = this.callStatus.filter(cs => {
                if (this.FD.byStat.callStatuses.common.hasOwnProperty(cs.id)) {
                    commonCnt += this.FD.byStat.callStatuses.common[cs.id]
                    cs.amount = this.FD.byStat.callStatuses.common[cs.id]
                    this.stat_callStatus.commonCnt + cs.amount
                    return cs
                }
            });
            this.$set( this.stat_callStatus,'commonCnt',commonCnt);

            Object.keys(this.FD.byStat.callStatuses.byManagers).forEach(menegerId => {
                let arr: object[] = [];
                let cnt = 0;
                Object.keys(this.FD.byStat.callStatuses.byManagers[menegerId]).forEach(statusId => {
                    let status = this.callStatus.find(i => i.id.toString() == statusId)
                    if(status){
                        cnt += this.FD.byStat.callStatuses.byManagers[menegerId][statusId]
                        arr.push({
                            name: status.name,
                            amount:this.FD.byStat.callStatuses.byManagers[menegerId][statusId],
                            color: status.color,
                        })
                    }
                })
                this.$set( this.stat_callStatus.byManagers,menegerId,arr);
                this.$set( this.stat_callStatus.byManagersCnt,menegerId,cnt);

            })
        }

        set_stat_appStatus() {
            this.stat_appStatus.common = this.appStatus.filter(app => {
                if (this.FD.byStat.appStatuses.common.hasOwnProperty(app.id)) {
                    app.amount = this.FD.byStat.appStatuses.common[app.id]
                    app.order = this.FD.byStat.appStatuses.commonOrdered[app.id]
                    // this.stat_callStatus.commonCnt + app.amount
                    return app
                }
            });

            Object.keys(this.FD.byStat.appStatuses.byManagers).forEach(menegerId => {
                let arr: object[] = [];
                Object.keys(this.FD.byStat.appStatuses.byManagers[menegerId]).forEach(statusId => {
                    let status = this.appStatus.find(i => i.id.toString() == statusId)
                    if(status){
                        arr.push({
                            name: status.name,
                            amount:this.FD.byStat.appStatuses.byManagers[menegerId][statusId] ?? 0,
                            order: this.FD.byStat.appStatuses.byManagersOrdered[menegerId][statusId] ?? 0,
                        })
                    }
                })
                this.$set( this.stat_appStatus.byManagers,menegerId,arr);

            })

        }

        set_stat_customerStatus() {
            this.stat_customerStatus.common = this.customerStatus.filter(app => {
                if (this.FD.byStat.customer.common.hasOwnProperty(app.id)) {
                    app.amount = this.FD.byStat.customer.common[app.id] ?? 0
                    app.order = this.FD.byStat.customer.commonOrdered[app.id] ?? 0
                    // this.stat_callStatus.commonCnt + app.amount
                    return app
                }
            });

            Object.keys(this.FD.byStat.customer.byManagers).forEach(menegerId => {
                let arr: object[] = [];
                Object.keys(this.FD.byStat.customer.byManagers[menegerId]).forEach(statusId => {
                    let status = this.customerStatus.find(i => i.id.toString() == statusId)
                    if(status){
                        arr.push({
                            short: status.short,
                            name: status.name,
                            amount:this.FD.byStat.customer.byManagers[menegerId][statusId] ?? 0,
                            order: this.FD.byStat.customer.byManagersOrdered[menegerId][statusId] ?? 0,
                        })
                    }
                })
                this.$set( this.stat_customerStatus.byManagers,menegerId,arr);

            })

        }


         set_fin_activity(){
             this.fin_activity.common.total.cash = 0;
             this.fin_activity.common.total.cashless = 0;
             this.fin_activity.common.implemented.cash = 0;
             this.fin_activity.common.implemented.cashless = 0;

           Object.keys( this.FF.byFinActivity.newTotal).map( f => {
                this.fin_activity.common.total.cash = Number(this.fin_activity.common.total.cash) + Number(this.FF.byFinActivity.newTotal[f].cash)
                this.fin_activity.common.total.cashless = Number(this.fin_activity.common.total.cashless) + Number(this.FF.byFinActivity.newTotal[f].cashless)
            })

             Object.keys( this.FF.byFinActivity.newImplemented).map( f => {
                 this.fin_activity.common.implemented.cash = Number(this.fin_activity.common.implemented.cash) + Number(this.FF.byFinActivity.newImplemented[f].cash)
                 this.fin_activity.common.implemented.cashless = Number(this.fin_activity.common.implemented.cashless) + Number(this.FF.byFinActivity.newImplemented[f].cashless)
             })

             this.$set(this.fin_activity.byManagers,'total',this.FF.byFinActivity.newTotal)
             this.$set(this.fin_activity.byManagers,'implemented',this.FF.byFinActivity.newImplemented)


        }

        calcFinancesTotal(){
            let credit = Number(this.FF.byFinTotal.byFinTotal.creditCash) + Number(this.FF.byFinTotal.byFinTotal.creditCashless);
            let debet = Number(this.FF.byFinTotal.byFinTotal.debitCash) + Number(this.FF.byFinTotal.byFinTotal.debitCashless);
            return debet - credit;
        }


       async showGraphs(date: any){
           await this.$set(this.$data, 'loading', true);
            await this.fetchFunnelData(date);
            await this.fetchFunnelFinances(date);
           await this.engine();
           await this.$set(this.$data, 'loading', false);
        }

        async mounted() {
           await this.showGraphs(this.period);
        }
    }
</script>
