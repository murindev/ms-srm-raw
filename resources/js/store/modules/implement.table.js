import Axios from "axios";
import {bus} from "../../bus";

const implementTable = {
    state: {
        implementDeleteReasons: [
            //['По вине клиента','По вине мастера','Технический перенос']
            {id:1,name:'По вине клиента'},
            {id:2,name:'По вине мастера'},
            {id:3,name:'Технический перенос'},
        ],
        implementChangeReasons: [
            //['По вине клиента','По вине мастера','Технический перенос']
            {id:1,name:'По вине клиента'},
            {id:2,name:'По вине мастера'},
            {id:3,name:'Технический перенос'},
        ],
    },
    getters: {
        implementDeleteReasons(state) {
            return state.implementDeleteReasons
        },
        implementChangeReasons(state) {
            return state.implementChangeReasons
        },
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

        async createImplement({dispatch, commit, getters,rootGetters}, payload){
            try {
                let response = await Axios.post('/rules/async',{
                    func: 'create',
                    values: payload
                });
                if(await response.data){
                   await dispatch('createLog',{
                        log:{
                            id: response.data.id,
                            model: 'implement',
                            state: 'create',
                            user: rootGetters.h.user.id,
                            lid_id: payload.lid_id,
                            act_id: payload.act_id,
                            implement_id: payload.subject_id,
                            field: '',
                            object: payload,
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
        async updateImplement({dispatch, commit, getters, rootGetters}, payload){
            const cloned = JSON.parse(JSON.stringify(payload));
            delete payload.rules;
            try {
                await dispatch('setMaster',payload);
                await bus.$on('implement_changed', () => {
                     dispatch('createLog',{
                        log:{
                            id: payload.id,
                            model: 'implement',
                            state: 'update',
                            user: rootGetters.h.user.id,
                            lid_id: payload.lid_id,
                            act_id: payload.parent,
                            implement_id: payload.id,
                            field: '',
                            object: cloned,
                        },
                        mess: {
                            success: 'Выполнение работ обновлено',
                            error: 'Выполнение работ обновить не удалось',
                        },
                    })
                });

            } catch (error) {
                console.log(error);
            }
        },
        async deleteImplement({dispatch, commit, getters,rootGetters}, payload){
            console.log('deleteImplement');
            try {
                let response = await Axios.post('/implement/async',{
                    func: 'delete',
                    id: payload.id,
                });
                if(await response.data){
                    await dispatch('createLog',{
                        log:{
                            id: payload.id,
                            model: 'implement',
                            state: 'delete',
                            user: rootGetters.h.user.id,
                            lid_id: payload.lid_id,
                            act_id: payload.parent,
                            implement_id: payload.id,
                            field: '',
                            object: payload,
                        },
                        mess: {
                            success: 'Выполнение работ удалено',
                            error: 'Выполнение работ удалить не удалось',
                        },
                    })
                }
                console.log(response.data);
            } catch (error) {
                console.log(error);
            }
        },

    }
};


export default implementTable;
