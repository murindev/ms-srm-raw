<template>
    <div>
        <Modal class="userModal" v-model="open" type="drawer-left"  :hasCloseIcon="true">

            <div class="userModalContainer"
                 v-if="staffer.id in crewScheduling && staffer.date in crewScheduling[staffer.id]">
                <Row :space="10">
                    <Cell class="title" width="6">Сотрудник:</Cell>
                    <Cell width="18">{{h.users[staffer.id].name}} -
                        <span style="color: red">{{h.roles[h.users[staffer.id].role_id]}}</span>
                    </Cell>
                </Row>
                <Row :space="10">
                    <Cell class="title" width="6">Дата:</Cell>
                    <Cell width="18">{{staffer.date}}</Cell>
                </Row>
                <Row :space="10">
                    <Cell class="title" width="6">Занятость:</Cell>
                    <Cell width="18" v-if="stafferStateList">
                        <Select
                                v-model="crewScheduling[staffer.id][staffer.date].state" :datas="stafferStateList"
                                @change="stafferSet(staffer.date,staffer.id)"
                        />
                    </Cell>
                </Row>
                <Row :space="10">
                    <Cell class="title" width="6">Начало работы:</Cell>
                    <Cell width="6"><DatePicker
                            v-model="crewScheduling[staffer.id][staffer.date].work_start" type="time"
                            @change="stafferSet(staffer.date,staffer.id)"
                            :disabled="crewScheduling[staffer.id][staffer.date].work_start_proof === 1"
                    /></Cell>
                    <Cell width="6">
                        <Button color="primary"
                                @click="workStartUnProof(staffer.date,staffer.id)"
                                v-if="crewScheduling[staffer.id][staffer.date].work_start_proof"
                        >Обнулить</Button>
                        <Button color="primary" v-else   @click="workStartProof(staffer.date,staffer.id)">Подтвердить</Button>
                    </Cell>
                </Row>
                <Row :space="10">
                    <Cell width="6">Конец работы:</Cell>
                    <Cell width="6"><DatePicker
                            v-model="crewScheduling[staffer.id][staffer.date].work_finish" type="time"
                            @change="stafferSet(staffer.date,staffer.id)"
                            :disabled="crewScheduling[staffer.id][staffer.date].work_finish_proof === 1"
                    /></Cell>
                    <Cell width="6">
                        <Button color="primary"
                                v-if="crewScheduling[staffer.id][staffer.date].work_finish_proof"
                                @click="workFinishUnProof(staffer.date,staffer.id)"
                        >Обнулить</Button>
                        <Button color="primary" v-else  @click="workFinishProof(staffer.date,staffer.id)">Подтвердить</Button>
                    </Cell>
                    <Cell width="6"></Cell>
                </Row>
                <Row :space="10">
                    <Cell class="title" width="24">
                        <textarea class="w100" placeholder="Примечание"
                              v-model="crewScheduling[staffer.id][staffer.date].comment"
                              @focusout="stafferSet(staffer.date,staffer.id)"
                        >
                        </textarea>
                    </Cell>
                </Row>


                 <Row :space="10" v-if="h.users[staffer.id].role_id === 3" >
                     <br/>
                     <br/>
                     <Cell width="24" class="center"> <span class="h-tag h-tag-yellow">Расшифровка - {{staffer.date}}</span></Cell>

                     <Cell width="24" v-if="Object.keys(masterDecryption).length">
                         <h4></h4>
                         <table class="table">

                             <thead>
                             <tr>
                                 <th colspan="5">
                                     Процент: {{h.users[staffer.id].percent}} / Лимит: {{h.users[staffer.id].percent_limit}} (руб/час)
                                 </th>
                             </tr>
                             <tr>
                                 <th>Акт</th>
                                 <th>Завершен?</th>
                                 <th>Стоимость</th>
                                 <th>Часов</th>
                                 <th>Процент</th>
                                 <th v-for="rule in h.rules[3]">{{rule.name_short}}</th>

                             </tr>
                             </thead>

                             <tbody>
                             <tr v-for="i in masterDecryption">
                                 <td><a target="_blank" :href="'/layout/lid/'+i.lidID+'/edit?open_act='+i.actID">{{i.actID}}</a></td>
                                 <td v-html="i.finished? 'Да': 'Нет'"></td>
                                 <td>{{thousands(i.cost)}}</td>
                                 <td>{{hours(i.len)}} </td>
                                 <td>{{thousands(i.percent)}}</td>
                                 <td v-for="rule in h.rules[3]">
                                     <template v-if="crew.impRules[i.impID] && crew.impRules[i.impID][rule.id]">
                                         <span
                                                 :class="[showDiff(crew.impRules[i.impID][rule.id]) > 0 ? 'h-tag-green': 'h-tag-red']"
                                                 class="h-tag"
                                                 v-tooltip  placement="left"  :content="showDiffTip(crew.impRules[i.impID][rule.id])"
                                         >
                                             {{showDiff(crew.impRules[i.impID][rule.id])}} мин.
                                         </span>
<!--                                         <Tooltip  :content="showDiffTip(crew.impRules[i.impID][rule.id])" >-->

<!--                                         </Tooltip>-->
                                         <br/>
                                         {{crew.impRules[i.impID][rule.id].note}}
                                     </template>
                                 </td>
                             </tr>
                             </tbody>

                             <tfoot>
                             <tr>
                                 <th>Выполнение:</th>
                                 <th></th>
                                 <th v-html="thousands(sum(masterDecryption,'cost'))"></th>
                                 <th v-html="hours(sum(masterDecryption,'len'))"></th>
                                 <th v-html="thousands(sum(masterDecryption,'percent'))"></th>
                                 <th v-for="rule in h.rules[3]"></th>
                             </tr>
                             <tr>
                                 <th>Ставка:</th>
                                 <th></th>
                                 <th></th>
                                 <th></th>
                                 <th>{{thousands(stafferByDays[staffer.id][staffer.date].rate)}}</th>
                                 <th></th>
                             </tr>
                             <tr>
                                 <th>ИТОГ:</th>
                                 <th></th>
                                 <th></th>
                                 <th></th>
                                 <th>{{thousands(total(staffer.id,staffer.date))}}</th>
                                 <th></th>
                             </tr>
                             </tfoot>

                         </table>
                     </Cell>
                  </Row>




            </div>

            <!--        @TODO-uretral:  -> crew_member_days        -->


        </Modal>
    </div>
</template>

<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "StafferModal",
        data(){
            return {
                masterDecryption: {}

            }
        },
        computed: {
            open: {
                get(){return this.$store.getters.staffer.open;},
                set(value){this.$store.commit('STAFFER',{id:null, date:null, open:value})}
            },
            staffer: {
                get(){return this.$store.getters.staffer;},
                set(value){this.$store.commit('STAFFER',value)}
            },
            ...mapGetters(['crewScheduling','h','stafferStateList','crew','stafferByDays']),
        },
        watch: {
            staffer(val){
                if(val.open){
                    this.implementDecryption(val.date,val.id)
                } else {
                    this.masterDecryption = {}
                }
          }
        },
        methods: {
            total(id,date){
               return  Number(this.stafferByDays[id][date].rate) + Number(this.sum(this.masterDecryption,'percent'));
            },
            showDiff(obj){
                return moment(obj.date_expected).diff(obj.date_applied,'minutes')
            },
            showDiffTip(obj) {
              return `Ожидаемо: ${obj.date_expected}   Фактически:${obj.date_applied}`
            },
            hours(val){
                let arr = String(val).split('.');
                let min = '00';
                if(arr[1] && arr[1] == 25){min = '15';
                } else if(arr[1] && arr[1] == 5){min = '30';
                } else if(arr[1] && arr[1] == 75) {min = '45';}
                return `${arr[0]}:${min}`;
            },
            thousands(val) {
                val = Math.ceil(val);
                return String(val).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ')
            },
            sum (obj,key){
                let sum = 0;
                for(let o in obj){
                    sum = sum + Number( obj[o][key]);
                }
                return sum;
            },
            async stafferSet(date,user){
                await this.$store.dispatch('crewUpdate',this.crewScheduling[user][date]);
            },
            trueOrFalse(val){
                return val === 1;
            },
            workStartProof(date,id){
                if(this.crewScheduling[id][date].work_start && !this.crewScheduling[id][date].work_start_proof){
                    this.crewScheduling[id][date].work_start_proof = true;
                    this.stafferSet(date,id)
                } else {
                    return false;
                }
            },
            workFinishProof(date,id){
                if(this.crewScheduling[id][date].work_finish && !this.crewScheduling[id][date].work_finish_proof){
                    this.crewScheduling[id][date].work_finish_proof = true;
                    this.stafferSet(date,id)
                } else {
                    return false;
                }
            },
            workStartUnProof(date,id){
                if(this.crewScheduling[id][date].work_start && this.crewScheduling[id][date].work_start_proof){
                    this.crewScheduling[id][date].work_start_proof = false;
                    this.stafferSet(date,id)
                } else {
                    return false;
                }
            },
            workFinishUnProof(date,id){
                if(this.crewScheduling[id][date].work_finish && this.crewScheduling[id][date].work_finish_proof){
                    this.crewScheduling[id][date].work_finish_proof = false;
                    this.stafferSet(date,id)
                } else {
                    return false;
                }
            },
            implementDecryption(date,id){
                this.masterDecryption = {};
                // let impArr = this.crew.impByDateMaster[date][id] ? this.crew.impByDateMaster[date][id] : [];
                let impArr = Object.keys(this.crew.impByDateMaster).indexOf(date) !== -1 && this.crew.impByDateMaster[date][id] ? this.crew.impByDateMaster[date][id] : [];
                let obj = {};
                let user = this.h.users[id];
                // /layout/lid/685/edit?open_act=5380
                for(let i in impArr){
                    let curImp = impArr[i],
                        impID = curImp.id,
                        lidID = curImp.lid_id,
                        actID = curImp.parent,
                        finished = this.crew.acts[actID].finished,
                        cost = this.crew.actCost[actID],
                        len = this.crew.impLength[impID],
                        costMaster = cost/this.crew.actImplementsCount[actID],
                        byHour = costMaster/len,
                        unlimitedPercent = Number(costMaster/100 * user.percent),
                        percent = unlimitedPercent/len > user.percent_limit ? user.percent_limit * len : unlimitedPercent,
                        rules = this.crew.impRules[impID] ? this.crew.impRules[impID] : false;

                    obj[curImp.id] = {
                        lidID,
                        actID,
                        impID,
                        finished,
                        cost,
                        len,
                        costMaster,
                        byHour,
                        unlimitedPercent,
                        percent: finished?percent:0,
                        rules
                    }
                }
                this.masterDecryption = obj;
            }
        },
    }
</script>

<style lang="scss">
    .h-tooltip {
        z-index: 2147483647!important;
    }
</style>
