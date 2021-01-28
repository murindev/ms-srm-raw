<template>
    <div>

        <template v-for="masterRule in h.rules[3]">
            <Cell width="3">
                <table class="table rules-tbl">
                    <tbody>
                    <template v-if="isSetRule(masterRule) !== undefined">
                        <tr>
                            <td v-html="masterRuleDate(masterRule,implement)"/>
                            <td><span>{{implement.rules[isSetRule(masterRule)].date_applied.slice(5).slice(0,-3)}}</span></td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <textarea v-model="implement.rules[isSetRule(masterRule)].note"   @focusout="updateRule(implement.rules[isSetRule(masterRule)],'note')"/>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td v-html="masterRuleDate(masterRule,implement)"/>
                            <td><a href="javascript:" @click="createRule(masterRule,implement)" class="h-icon-inbox master-rule"/></td>
                        </tr>
                    </template>

                    </tbody>

                </table>
            </Cell>
        </template>


    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import {bus} from "../../bus";


    export default {
        name: "RulesMaster",
        props:['implement'],
        data(){
            return {
                appliedRules:this.rulesArray(),
                table: {
                    user_in_charge: this.implement.master, // Ответственный
                    user_auditor: '', // Куратор
                    rule_id: '', // Правило
                    subject: 'implement', // Сущность
                    subject_id: this.implement.id, // id сущности
                    date_expected: '', // Ожидаемая дата
                    date_applied: '', // Дата совершенного события
                    dates_diff: '', // Разница в датах, если нужно
                    note: '', // Пояснение
                    lid_id: this.implement.lid_id,
                    act_id: this.implement.parent,
                    implement_id: this.implement.id,
                },

            }
        },
        computed:{
            ...mapGetters(['h','mapActs']),
        },
        methods:{
             rulesArray(){
                let obj = {}
                for(let rule of this.implement.rules) {
                    obj[rule.rule_id] = rule;
                }
                return obj;
            },
            isSetRule(masterRule){
                 let key = undefined;
                for(let i in this.implement.rules) if(this.implement.rules[i].rule_id === masterRule.id) {
                    key = i;
                    break;
                }
                return key;
            },
            datesDiff(start,end){

            },
            dateNormalize(datetime){
                let t = (new Date(datetime - (new Date()).getTimezoneOffset() * 60000)).toISOString();
                return t.replace(/T/,' ').slice(0, -5);
            },
            async createRule(masterRule,implement){
               await this.$set(this.table,'rule_id',masterRule.id);
               await this.$set(this.table,'user_auditor',this.h.user.id);
               await this.$set(this.table,'date_applied',this.dateNormalize(Date.now()));
               await this.$set(this.table,'date_expected',this.masterRuleDate(masterRule,implement,true));
               await this.$store.dispatch('createRule',this.table);
            },
            async updateRule(data,field){
                 await this.$store.dispatch('updateRule',{data,field})
            },

            masterRuleDateString(date,minutes,limit,short = false){
                let t = new Date(date).getTime();
                let n = t+ Number(minutes) * 60000;
                let norm = this.dateNormalize(n);
                return short ? norm : norm.slice(5).slice(0,-3);
            },

            masterRuleDate(masterRule,implement,short = false){
                if(masterRule.strict && masterRule.start_end){
                    return this.masterRuleDateString(implement.start_date,masterRule.value,masterRule.gap,short);
                }else if(masterRule.strict && !masterRule.start_end){
                    return this.masterRuleDateString(implement.end_date,masterRule.value,masterRule.gap,short);
                } else {
                    return '';
                }
            },
        }
    }
</script>

<style scoped lang="scss">
    textarea {
        height: 30px;
        width: 100%;
        resize: vertical;
        text-align: left;
    }
</style>
