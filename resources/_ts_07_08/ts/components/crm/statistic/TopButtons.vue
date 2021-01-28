<template>
    <div>
        <Button class="settings roof" style="left: 54px" color="yellow" icon="h-icon-setting"
                @click="settings.modal = !settings.modal"/>

        <Button class="settings roof" style="left: 107px" color="red"
                @click="createNewRow()">Новая запись</Button>

        <DateFullRangePicker class="roof" style="left: 235px" v-model="period" :layout="datePicker.layout"
                             :startWeek="datePicker.startWeek" @input="fetchTable"/>

        <Button v-if="filterBtn" class="settings roof" style="left: 485px" color="white" @click="resetTableFilter()">Сбросить фильтр</Button>

        <settings :settings="settings"/>


<!--        <Button class="settings roof" style="left: 700px" color="red" @click="globalSearch = !globalSearch" v-text="globalSearch ? 'Глобальный поиск' : 'Локальный поиск'"/>-->


    </div>
</template>

<script lang="ts">

    import {Component, Prop, Vue} from 'vue-property-decorator'
    import {State, Getter, Action, Mutation, namespace} from 'vuex-class'
    import Settings from "./Settings.vue";
    import {ICrmStatistic} from "../../../types/crm.statistic";
    import * as moment from "moment";

    const CrnStatistic = namespace('CrmStatistic')

    import { bus } from "../../../bus";

    @Component({
        components: {Settings}
    })
    export default class TopButtons extends Vue {
// PROPS
        @Prop() manager!: number

        @CrnStatistic.Getter('getPeriod') getPeriod: any

// PERIOD
        get period() {
            // return this.$store.state.CrmStatistic.period
            return this.getPeriod
        }

        set period(value) {
            this.setPeriod(value)
        }
        // @CrnStatistic.State('globalSearch') public globalSearch:boolean

        @CrnStatistic.Mutation public setPeriod!: (payload: object) => void
        @CrnStatistic.Action('initTable') public fetchTable!: () => void
        // changePeriod(event: object): void {
        //     console.log('event.target.value', event);
        //     this.setPeriod(event)
        // }
// SETTINGS
        get settings() {
            return this.$store.state.CrmStatistic.settings
        }

        set settings(value) {
            this.setSettings(value)
        }

        @CrnStatistic.Mutation public setSettings!: (payload: object) => void

//CREATE ROW
        @CrnStatistic.Action('eCreateRow') public eCreateRowStatistic!: (payload: ICrmStatistic) => void
        createNewRow(){
            this.eCreateRowStatistic({
                date: moment().format('YYYY-MM-DD'),
                time: moment().format('HH-mm'),
                manager: this.manager,
                call_status: 9,
                app_status: 1
            })
        }


        public datePicker: any = {
            startWeek: 1,
            layout   : ['year', 'quarter', 'month', 'week', 'date', 'customize'],
        }

// STATISTIC
        @CrnStatistic.State
        public statistics!: Array<any>

// FILTER BTN
        public filterBtn: boolean = false
        resetTableFilter() {bus.$emit('resetTableFilter', true)}

// test
        @CrnStatistic.State private test!: string
        @CrnStatistic.Mutation private setTest!: (payload: string) => void

        newTest(event: any): void {
            this.setTest(event.target.value)
        }
        loading = false
        done = false



        created() {
            // console.log('created');
            // console.log('$store', this.$store.state.CrmStatistic.period);

            bus.$on('showTableFilterDetachBtn', (val: boolean) => {
                this.filterBtn = val
            })



        }


    }
</script>
