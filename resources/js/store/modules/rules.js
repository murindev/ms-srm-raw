import Axios from "axios";
import {bus} from "../../bus";

const theTrigger = function(){
    bus.$emit('re-load');
}




const rules = {
    state: {
        rules: {}
    },
    getters: {
        rules(state) {
            return state.rules
        }
    },
    mutations: {
        FETCH_RULES(state, payload) { state.h = payload},
    },
    actions: {
        // INIT ACTIONS
        fetchRules({commit}) {
            return Axios.post('/helper/async', {
                func: 'helpers'
            })
                .then(response => commit('FETCH_HELPERS', response.data))
                .catch(error => console.log(error));
        },

        async createRule({dispatch, commit, getters}, payload){
            try {
                let response = await Axios.post('/rules/async',{
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
        async updateRule({dispatch, commit, getters}, payload){
            try {
                let response = await Axios.post('/rules/async',{
                    func: 'update',
                    id: payload.data.id,
                    values: payload.data,
                });
                if(await response.data){
                    await dispatch('createLog',{
                        log:{
                            id: payload.data.id,
                            model: 'rule',
                            state: 'create',
                            user: payload.data.user_auditor,
                            lid_id: payload.data.lid_id,
                            act_id: payload.data.act_id,
                            implement_id: payload.data.subject_id,
                            field: payload.field,
                            object: response.data,
                        },
                        mess: {
                            success: 'Правило обновлено',
                            error: 'Правило обновить не удалось',
                        },
                    })
                }
            } catch (error) {
                console.log(error);
            }
        },
        async deleteRule({dispatch, commit, getters}, payload){
            try {
                let response = await Axios.post('/rules/async',{
                    func: 'delete',
                    id: payload.id,
                });
                console.log(response.data);
            } catch (error) {
                console.log(error);
            }
        },

    }
};


export default rules;
