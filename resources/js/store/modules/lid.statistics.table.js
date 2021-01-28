import Axios from "axios";
import {bus} from "../../bus";
import moment from "moment";


/*
const lidError = (mess, state = '') => {
    bus.$emit('error', {table: 'Лид', mess: mess, state: state});
};
const lidSuccess = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Лид', mess: mess});
};*/

const lidStatisticsTable = {
    state: {
        lidStatisticsPeriod: {
            start: moment().subtract(30, 'days').format("YYYY-MM-DD"),
            end: moment().endOf('month').add(2, 'days').format("YYYY-MM-DD"),
            type: 'customize'
        },
        lidStatistic: {},
        lidStatisticField: null,
        lidStatisticEditing: null,
        lidStatisticSearchCustomer: [],
        lidStatisticEditModal: false,
        lidStatisticEditClonedRow: {},
        lidStatisticMessage: {type:'', text:''},
    },
    getters: {
        lidStatistic(state) {
            return state.lidStatistic
        },
        lidStatisticsPeriod(state) {
            return state.lidStatisticsPeriod
        },
        lidStatisticField(state) {
            return state.lidStatisticField
        },
        lidStatisticEditing(state) {
            return state.lidStatisticEditing
        },
        lidStatisticSearchCustomer(state) {
            return state.lidStatisticSearchCustomer
        },
        lidStatisticEditModal(state) {
            return state.lidStatisticEditModal
        },
        lidStatisticEditClonedRow(state) {
            return state.lidStatisticEditClonedRow
        },
        lidStatisticMessage(state) {
            return state.lidStatisticMessage
        },
    },
    mutations: {
        LID_STATISTICS(state, payload) {
            state.lidStatistic = payload
        },
        LID_STATISTICS_PERIOD(state, payload) {
            state.lidStatisticsPeriod = payload
        },
        LID_STATISTICS_FIELD(state, payload) {
            state.lidStatisticField = payload
        },
        LID_STATISTICS_EDITING(state, payload) {
            state.lidStatisticEditing = payload
        },
        LID_STATISTICS_UPDATE(state, payload) {
            state.lidStatistic = state.lidStatistic.map(o => {
                if (o.id === payload.id) {return payload}
                return o;
            });
        },
        LID_STATISTICS_CREATE(state, payload) {
            let index = state.lidStatistic.filter(o => o.id === payload.id);
            if(index.length === 0){
                state.lidStatistic.unshift(payload)
            }
        },
        LID_STATISTICS_REORDER(state, payload) {
            state.lidStatistic = state.lidStatistic.sort((a,b) => {
                return new Date(b.date) - new Date(a.date);
            })
        },
        LID_STATISTICS_SEARCH_CUSTOMER(state, payload) {
            state.lidStatisticSearchCustomer = payload
        },

        LID_STATISTICS_EDIT_MODAL(state, payload) {
            state.lidStatisticEditModal = payload
        },
        LID_STATISTICS_EDIT_ROW(state, payload) {
            state.lidStatisticEditClonedRow = payload
        },
        LID_STATISTICS_MESSAGE(state, payload) {
            state.lidStatisticMessage = payload
        },

    },
    actions: {

        async createLidStatistics({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'create',
                    manager:payload
                });
                if(response.data){dispatch('readLidStatistics');}
            } catch (error) {
                console.log(error);
            }
        },
        async readLidStatistics({commit,getters}){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'read',
                    values: getters.lidStatisticsPeriod,
                });
                if(response.data){commit('LID_STATISTICS',response.data);}
            } catch (error) {
                console.log(error);
            }
        },
        async updateLidStatistics({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'update',
                    id: payload._id,
                    values: payload
                });
                // if(response.data){dispatch('readLidStatistics');}
            } catch (error) {
                console.log(error);
            }
        },
        async deleteLidStatistics({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'delete',
                    id: payload
                });
                if(response.data){dispatch('readLidStatistics');}
            } catch (error) {
                console.log(error);
            }
        },

        async creteLid({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'creteLid',
                    values: payload
                });
                if(response.data){
                    // commit('LID_STATISTICS_UPDATE',response.data);
                    // dispatch('readLidStatistics');
                    // console.log('broadcast',response.data);
                }
            } catch (error) {
                console.log(error);
            }
        },

        async lidStatisticEditing({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'checkEditing',
                    user: payload
                });
                if(response.data){commit('LID_STATISTICS_EDITING');}
            } catch (error) {
                console.log(error);
            }
        },

        async lidStatisticFieldCheck({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'checkValue',
                    id: payload.id,
                    field: payload.field,
                });
                if(response.data){dispatch('readLidStatistics');}
            } catch (error) {
                console.log(error);
            }
        },

        async lidStatisticSearchCustomer({state, dispatch, commit,rootGetters},payload){
            if(payload.length >= 2) {
                try {
                    let response = await Axios.post('/lid_statistic/async',{
                        func: 'client',
                        q: payload
                    });
                    if(response.data){commit('LID_STATISTICS_SEARCH_CUSTOMER',response.data);}
                } catch (error) {
                    console.log(error);
                }
            } else {
                commit('LID_STATISTICS_SEARCH_CUSTOMER',[]);
            }
        },

        async lidStatisticFieldUpdate({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'creteLid',
                    values: payload
                });
                if(response.data){dispatch('readLidStatistics');}
            } catch (error) {
                console.log(error);
            }
        },

        async lidStatisticEditRow({state, dispatch, commit,rootGetters},payload) {
            console.log(payload);
            try {
                let response = await axios.post('/lid_statistic/async', {
                    func: 'checkEditing',
                    id: payload.row.id,
                    flag: payload.flag,
                    manager:payload.manager
                });
                if (await response.data) {
                    if (response.data === Number(this.manager) && payload.flag === 'open') {
                        commit('LID_STATISTICS_EDIT_ROW',{...payload.row})
                        commit('LID_STATISTICS_EDIT_MODAL',true)
                    } else if (payload.flag === 'close') {
                        commit('LID_STATISTICS_EDIT_MODAL',false)
                        commit('LID_STATISTICS_EDIT_ROW',{})
                    } else {
                        let name = await rootGetters.h.users[response.data].name
                        commit('LID_STATISTICS_MESSAGE',{
                            type:'warn',
                            text: `Эта строка редактируется пользователем <b>${name}</b>`
                        })
                    }
                }
            } catch (error) {
                console.log(error);
            }
        }


    }
};

export default lidStatisticsTable;
