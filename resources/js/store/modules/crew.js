import Axios from "axios";
import {bus} from "../../bus";
import store from "../../store";
import moment from "moment";
require('moment-weekday-calc');

const lidError = (mess, state = '') => {
    bus.$emit('error', {table: 'Лид', mess: mess, state: state});
};
const lidSuccess = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Лид', mess: mess});
};
bus.$on('fetch-crew', function()  {
    store.dispatch('fetchCrewByImplement')
});
Date.prototype.daysInMonth = function() {
    return 32 - new Date(this.getFullYear(), this.getMonth(), 32).getDate();
};
Date.prototype.firstDayMonth = function() {
    return new Date(this.getFullYear(), this.getMonth(), 1).getDate();
};
Date.prototype.lastDayMonth = function() {
    return new Date(this.getFullYear(), this.getMonth() + 1, 0).getDate();
};

const workingDaysInMonth = (date,timeline) => {
    let start = moment(date).startOf('month').format("YYYY-MM-DD");
    let end = moment(date).endOf('month').format("YYYY-MM-DD");
    let arTimeline = Array.from({length: timeline}, (v, k) => k+1);
    return moment().isoWeekdayCalc(start,end,arTimeline);
};

const scheduleItem = (date,staffer) => {
    let arTimeline = Array.from({length: staffer.timeline}, (v, k) => k+1);
    let dayNr = moment(date).day() === 0 ? 7 : moment(date).day();
    let stafferDay = getScheduleItem(date,staffer);
    let isHoliday;
    let isPayable = true;
    if(stafferDay && ['work','sick','compensatory'].indexOf(stafferDay.state) !== -1 )
        isHoliday = false;
    // else if(stafferDay && !stafferDay.work_start_proof && !stafferDay.work_finish_proof)
    //     isPayable = false;
    else if(!stafferDay)
        isPayable = false;
    else
        isHoliday = arTimeline.indexOf(dayNr) === -1;

    if(stafferDay && ['unspecified','hooky','unpaid'].indexOf(stafferDay.state) !== -1 ){
        isPayable = false
    } else if(!stafferDay) {
        isPayable = false
    } else if(moment(date).isAfter(moment(), 'day')){
        isPayable = false
    }

    return {
        isHoliday,
        isPayable
    }
};
const calcTime = (firstDate,secondDate) => {
    let getDate = (string) => new Date(0, 0,0, string.split(':')[0], string.split(':')[1]);
    let different = (getDate(secondDate) - getDate(firstDate));
    let hours = Math.floor((different % 86400000) / 3600000);
    let minutes = Math.round(((different % 86400000) % 3600000) / 60000);
    return  hours + ':' + minutes;
};
const getScheduleItem = (date,staffer) => {
    if(staffer.id in store.getters.crewScheduling && store.getters.crewScheduling[staffer.id][date]){
        return store.getters.crewScheduling[staffer.id][date]
    } else {
        return false
    }
};
const dayGain = (date,staffer,crew) => {
    let gain = 0;
    if(Object.keys(crew.byDate).indexOf(date) !== -1){
        for(let act of crew.byDate[date]) if(crew.acts[act.parent].finished === 1){
            gain = gain + crew.actCost[act.parent];
        }
    }
    return gain;
};
const masterGains = (date,staffer,crew) => { // date,id
    let impArr = crew.impByDateMaster[date] !== undefined && Object.keys(crew.impByDateMaster[date]).indexOf([staffer.id]) ? crew.impByDateMaster[date][staffer.id] : [];
    let obj = {};
    // /layout/lid/685/edit?open_act=5380
    let gain = 0;
    for(let i in impArr){
        let curImp = impArr[i],

            impID = curImp.id,
            lidID = curImp.lid_id,
            actID = curImp.parent,
            finished = crew.acts[actID].finished,
            cost = crew.actCost[actID],
            len = crew.impLength[impID],
            costMaster = cost/crew.actImplementsCount[actID],
            byHour = costMaster/len,
            unlimitedPercent = Number(costMaster/100 * staffer.percent),
            percent = unlimitedPercent/len > staffer.percent_limit ? staffer.percent_limit * len : unlimitedPercent,
            rules = crew.impRules[impID] ? crew.impRules[impID] : false;

        if(finished){
            gain = gain + Math.ceil(percent);
        }



    }
    return gain;
}

const masterGain = (date,staffer,crew) => {

    let perCent = 0;
    let byHour = 0;
    let gain = 0;
    let impLength = 0;
    if(crew.byMasterAkt[staffer.id] !== undefined && crew.byMasterAkt[staffer.id][date] !== undefined) {
        for(let act of crew.byMasterAkt[staffer.id][date]) if(crew.acts[act].finished === 1) {
            perCent = crew.actCost[act]/Number(crew.actImplementsCount[act])/100 * staffer.percent;
            byHour = perCent/crew.impLength[act];
            if(date == '2020-01-27' && staffer.id == 34){
                console.log(`byHour ${act}`,Math.ceil(byHour));
            }
             let earn = perCent/crew.impLength[act] <= staffer.percent_limit ? perCent/crew.impLength[act] : staffer.percent_limit;
            if(date == '2020-01-27' && staffer.id == 34){
                console.log(`earn ${act}`,Math.ceil(earn));
            }
            gain = gain + earn;
        }
    }
    if(date == '2020-01-27' && staffer.id == 34){
        console.log('gain',gain);
    }

    return gain;
};



const percent = (date,staffer,crew) => {
    let perCent = 0;
    if(staffer.percent !== 0 && staffer.role_id === 3){
        perCent = masterGains(date,staffer,crew);

    } else if (staffer.percent !== 0 && staffer.role_id !== 3) {
         perCent = dayGain(date,staffer,crew)/100 * Number(staffer.percent);
    }

    return perCent;
};
const getHours = (schedule) => {
    let obj = {
        hours: 0,
        approved: false,
        work_finish:schedule ? schedule.work_finish : false,
        work_finish_proof:schedule ? schedule.work_finish_proof : false,
        work_start:schedule ? schedule.work_start : false,
        work_start_proof: schedule ? schedule.work_start_proof : false,
    };

    if(schedule && schedule.work_start && schedule.work_finish ) {
        obj.hours = calcTime(schedule.work_start , schedule.work_finish);
        obj.approved = true;
    }
    return obj;
};
// <<<<<<<
const stafferDay = (date,staffer,crew) => {
    let schedule = getScheduleItem(date,staffer);
    let rate = staffer.salary / workingDaysInMonth(date,staffer.timeline);
    let item = scheduleItem(date,staffer);
    let hours = getHours(schedule);
    return {
        rate: item.isHoliday || !item.isPayable ? 0 : Math.floor(rate),
        work_finish:hours.work_finish,
        work_finish_proof:hours.work_finish_proof,
        work_start:hours.work_start,
        work_start_proof:hours.work_start_proof,
        workHours:hours.hours,
        approved:hours.approved,
        isHoliday: item.isHoliday,
        perCent:  Math.floor(percent(date,staffer,crew)),
    };
};
// >>>>>>>>>
const crew = {
    state: {
        // by default
        crewPeriod: {
            start: moment().startOf('week').add(1, 'days').format("YYYY-MM-DD"),
            end: moment().endOf('week').add(2, 'days').format("YYYY-MM-DD"),
            type: 'week'
        },
        staffers:{},
        stafferStateList: [
            {
                color: '#ffffff',
                title: 'Рабочий день',
                key: 'work',
                isPaying: true,
                active: true
            }, {
                color: '#7aff93',
                title: 'Выходной день',
                key: 'weekend',
                isPaying: false,
                active: true
            }, {
                color: '#a4ffe9',
                title: 'Отгул(оплачиваемый)',
                key: 'compensatory',
                isPaying: true,
                active: true
            },{
                color: '#ff24c1',
                title: 'Больничный',
                key: 'sick',
                isPaying: true,
                active: true
            }, {
                color: '#ff8e0c',
                title: 'Неявка по невыясненным причинам',
                key: 'unspecified',
                isPaying: false,
                active: true
            }, {
                color: '#ff0000',
                title: 'Прогул',
                key: 'hooky',
                isPaying: false,
                active: true
            }, {
                color: '#e4e037',
                title: 'Отпуск без оплаты',
                key: 'unpaid',
                isPaying: false,
                active: true
            },{
                color: '#3c8dbc',
                title: 'Обучение',
                key: 'study',
                isPaying: false,
                active: true
            },
        ],
        // eof by default
        // output
        crew: {},
        roles:[1,2,3,4,5,6,7,8,9],
        // eof output





        crewPeriodArr: [],

        staffer:{
            id:null,
            date:null,
            open:false
        },

        crew_by_implement:{},

        // calendar manipulations
        daysInMonth: new Date().daysInMonth(),
        //
        stafferDay:{}, // |
        dayByStaffers:{}, // --
        stafferByDays:{}, // --
    },
    getters: {
        staffers(state) {
            return state.staffers
        },
        crew(state) {
            return state.crew
        },
        roles(state) {
            return state.roles
        },
        crewPeriod(state) {
            return state.crewPeriod
        },
        crewPeriodArr(state) {
            return state.crewPeriodArr
        },
        daysInMonth(state) {
            return state.daysInMonth
        },
        staffer(state) {
            return state.staffer
        },
        stafferStateList(state) {
            return state.stafferStateList
        },
        workDaysInMonth(state) {
            return state.workDaysInMonth
        },
        stafferDay(state) {
            return state.stafferDay
        },
        dayByStaffers(state) {
            return state.dayByStaffers
        },
        stafferByDays(state) {
            return state.stafferByDays
        },
    },
    mutations: {
        ROLES(state, payload) {
            state.roles = payload
        },
        STAFFERS(state, payload) {
            state.staffers = payload
        },
        FETCH_CREW(state, payload) {
            state.crew = payload
        },
        FETCH_CREW_BY_IMPLEMENT(state, payload) {
            state.crew = payload
        },
        CREW_PERIOD(state, payload){
            state.crewPeriod = payload;
        },
        CREW_PERIOD_ARR(state, payload){
            state.crewPeriodArr = payload;
        },
        STAFFER(state, payload){
            state.staffer = payload;
        },
        STAFFER_CLOSE(state, payload){
            state.staffer.open = payload;
        },
        STAFFER_BY_DAYS(state, payload){
            state.stafferByDays = payload;
        },
        DAY_BY_STAFFERS(state, payload){
            state.dayByStaffers = payload;
        },
        STAFFERS_DAYS(state, payload) {

        }
    },
    actions: {

        async fetchCrewByImplement({commit, dispatch, getters,rootGetters}) {
            let   RG = await rootGetters;

            try {
                let response = await Axios.post('/crew_scheduling/async', {
                    func: 'fetch',
                    values:getters.crewPeriod
                });
                await commit('FETCH_CREW_SCHEDULING',response.data.crewScheduling);
                await commit('FETCH_CREW_BY_IMPLEMENT', response.data);
                bus.$emit('crewSchedulingReady');
                // getters.crewPeriod.staffers = RG.h.users;
                await dispatch('crewPeriodArray',getters.crewPeriod);
            } catch (e) {
                console.log(e)
            }
        },
        async crewPeriodArray({commit, dispatch, getters, rootGetters},payload){
            let staff = await getters.staffers;
            let crew = await getters.crew;
            let   byDays = {};
            let   byStaffers = {};
            let   daySummary = {};
            let
                start = new Date(payload.start),
                end = new Date(payload.end),
                arr = new Array(),
                dt = new Date(start);

            while (dt < end) {
                let day = await new Date(dt).toISOString().slice(0,10);
                arr.push(day);
                dt.setDate(dt.getDate() + 1);
            }

            for( let d of arr){
                byDays[d] = {};
                for(let u in await staff) if(staff[u].active){ //
                    let info = stafferDay(d,staff[u],crew);
                    byDays[d][staff[u].id] = {...staff[u], ...info}
                }
            }

            for(let s in await staff) if(staff[s].active){ //
                byStaffers[staff[s].id] = {};
                for( let d of arr) {
                    let dayInfo = stafferDay(d, staff[s],crew);
                    byStaffers[staff[s].id][d] = {...staff[s], ...dayInfo}
                }
            }

            for(let d in await byDays){

            }




            commit('DAY_BY_STAFFERS',byDays);
            commit('STAFFER_BY_DAYS',byStaffers);
            commit('CREW_PERIOD_ARR',arr);
        },
        async staffersDays({commit, dispatch, getters, rootGetters}){

        }
    }
};



export default crew;
