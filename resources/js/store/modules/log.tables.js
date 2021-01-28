import Axios from "axios";
import {bus} from "../../bus";

const logTables = {
    state: {
        lidActions:{},
        lidStatuses:{},
        actActions:{},
        actStatuses:{},
    },
    getters: {
        lidActions(state){
          return state.lidActions
        },
        lidStatuses(state){
            return state.lidStatuses
        },
        actActions(state){
            return state.actActions
        },
        actStatuses(state){
            return state.actStatuses
        }
    },
    mutations: {
        FETCH_LID_ACTIONS(state, payload) { state.lidActions = payload},
        FETCH_LID_STATUSES(state, payload) { state.lidStatuses = payload },
        FETCH_ACT_ACTIONS(state, payload) { state.actActions = payload },
        FETCH_ACT_STATUSES(state, payload) { state.actStatuses = payload },
    },
    actions: {
         fetchLidActions({commit},id) {
            return  Axios.post('/log/async', {func: 'getLidActions',id})
                .then(response => commit('FETCH_LID_ACTIONS', response.data))
                .catch(error => console.log(error));
        },
         fetchLidStatuses({commit},id) {
            return  Axios.post('/log/async', {func: 'getLidStatuses',id})
                .then(response => commit('FETCH_LID_STATUSES', response.data))
                .catch(error => console.log(error));
        },
         fetchActActions({commit},id) {
            return  Axios.post('/log/async', {func: 'getActActions',id})
                .then(response => commit('FETCH_ACT_ACTIONS', response.data))
                .catch(error => console.log(error));
        },
         fetchActStatuses({commit},id) {
            return  Axios.post('/log/async', {func: 'getActStatuses',id})
                .then(response => commit('FETCH_ACT_STATUSES', response.data))
                .catch(error => console.log(error));
        },
    }
};


export default logTables;
