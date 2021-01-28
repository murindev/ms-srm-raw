import Axios from "axios";
import {bus} from "../../bus";

// const theTrigger = function(){
//     bus.$emit('re-load');
// }

let addressee = localStorage.getItem('addressee');





const messengerTable = {
    state: {
        messages: {},
        addressee: addressee ? addressee.split(',') : [],
        currentGroup: '',
        newMessagesCnt: [],
        newMessagesCntTotal: 0,
        conferencing: localStorage.getItem('conferencing') == 1,
    },
    getters: {
        messages(state) {
            return state.messages
        },
        addressee(state) {
            return state.addressee
        },
        currentGroup(state) {
            return state.currentGroup
        },
        conferencing(state) {
            return state.conferencing
        },
        newMessagesCnt(state) {
            return state.newMessagesCnt
        },
        newMessagesCntTotal(state) {
            return state.newMessagesCntTotal
        },
    },
    mutations: {
        FETCH_MESSAGES(state, payload) { state.messages = payload},
        UPDATE_MESSAGES(state, payload) {
            if(Object.keys(state.newMessagesCnt).indexOf(payload.row.group_name) !== -1){
                state.messages[payload.row.group_name].push(payload.row);
                if(payload.row.user_id !== payload.user){
                    state.newMessagesCnt[payload.row.group_name]++;
                }

                console.log('upd');
            } else {
                state.messages[payload.row.group_name] = [];
                state.messages[payload.row.group_name].push(payload.row);
                if(payload.row.user_id !== payload.user){
                    state.newMessagesCnt[payload.row.group_name] = 1;

                }

                console.log('upd2');
            }


            state.newMessagesCntTotal++

        },
        FETCH_ADDRESSEE(state, payload) {
            state.addressee = payload;
            state.currentGroup = payload.join('::');
        },
        // CURRENT_GROUP(state, payload) { state.currentGroup = payload},
        CONFERENCING(state, payload) { state.conferencing = payload},

        NEW_MESSAGES_CNT(state, payload) { state.newMessagesCnt = payload},
        NEW_MESSAGES_CNT_UPDATE(state, payload) {state.newMessagesCnt[state.currentGroup] = 0},

        NEW_MESSAGES_CNT_TOTAL(state, payload) { state.newMessagesCntTotal = payload},
        NEW_MESSAGES_CNT_TOTAL_UPDATE(state, payload) { state.newMessagesCntTotal = state.newMessagesCntTotal - payload},
    },
    actions: {
        // INIT ACTIONS
        async  fetchMessages({commit,getters,rootGetters},payload) {
            try {
                let response = await  Axios.post('/messenger/async',payload);
                if(response.data){
                    let notSeen = {};
                    let notSeenTotal = 0;
                    for(let i in response.data){
                        let cnt = 0;
                        for(let b of response.data[i]){
                            if(Object.keys(b.addressee).indexOf(String(payload.from)) !== -1){
                                cnt = cnt + b.addressee[payload.from].seen;
                            }
                        }
                        notSeenTotal = notSeenTotal + cnt;
                        notSeen[i] = cnt;

                    }


                    console.log('notSeen',notSeen);

                    commit('NEW_MESSAGES_CNT_TOTAL',notSeenTotal);
                    commit('NEW_MESSAGES_CNT',notSeen);
                    commit('FETCH_MESSAGES',response.data);
                }

            } catch (e) {
                console.log(e);
            }


/*            Axios.post('/messenger/async',payload).then(response => {
                console.log('response.data',response.data);
                let notSeen = {};
                let notSeenTotal = 0;
                for(let i in response.data){
                    let cnt = 0;
                    for(let b of response.data[i]){
                        if(Object.keys(b.addressee).indexOf(String(payload.from)) !== -1){
                            cnt = cnt + b.addressee[payload.from].seen;
                        }
                    }
                    notSeenTotal = notSeenTotal + cnt;
                    notSeen[i] = cnt;

                }


                console.log('notSeen',notSeen);

                commit('NEW_MESSAGES_CNT_TOTAL',notSeenTotal);
                commit('NEW_MESSAGES_CNT',notSeen);
                commit('FETCH_MESSAGES',response.data);
            });*/
        },


        async createMessage({dispatch, commit, getters}, payload){
            try {
                let response = await Axios.post('/message/async',{
                    func: 'create',
                    values: payload
                });
                if(await response.data){
                   await dispatch('createLog',{
                        log:{
                            id: response.data.id,
                            model: 'rule',
                            state: 'create',
                            user: payload.user_auditor,
                            lid_id: payload.lid_id,
                            act_id: payload.act_id,
                            implement_id: payload.subject_id,
                            field: '',
                            object: response.data,
                        },
                        mess: {
                            success: 'Правило зафиксировано',
                            error: 'Правило зафиксировать не удалось',
                        },
                    })
                }

            } catch (error) {
                console.log(error);
            }
        },

        async deleteMessage({dispatch, commit, getters}, payload){
            try {
                let response = await Axios.post('/message/async',{
                    func: 'delete',
                    id: payload.id,
                });
                console.log(response.data);
            } catch (error) {
                console.log(error);
            }
        },

        messageSeen({dispatch, commit, getters}, payload) {
            Axios.post('/messenger/async', {'func': 'messageSeen', id: payload})
                .then(response => {
                    commit('NEW_MESSAGES_CNT_UPDATE');
                    commit('NEW_MESSAGES_CNT_TOTAL_UPDATE', payload.length);

                })
        },






        // ADDRESSEE
        getAddressee({commit}){
            let as = localStorage.getItem('addressee');
            commit('FETCH_ADDRESSEE', as ? as.split(',').map(v => Number(v)) : [])
        },
        setAddressee({dispatch, commit, getters}, payload){
            localStorage.setItem('addressee', payload.join());
            dispatch('getAddressee')
        },

        // CONFERENCING
        getConferencing({commit},payload){
            let conferencing = localStorage.getItem('conferencing');
            commit('CONFERENCING', conferencing == 1 )
        },
        async setConferencing({dispatch, commit, getters}, payload){
           await localStorage.setItem('conferencing', payload ? '1' : '0');
           await dispatch('getConferencing')
        }

    }
};


export default messengerTable;
