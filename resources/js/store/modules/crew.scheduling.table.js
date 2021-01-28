import Axios from "axios";
import {bus} from "../../bus";


const lidError = (mess, state = '') => {
    bus.$emit('error', {table: 'Лид', mess: mess, state: state});
};
const lidSuccess = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Лид', mess: mess});
};

const crewSchedulingTable = {
    state: {
        crewScheduling: {},
    },
    getters: {
        crewScheduling(state) {
            return state.crewScheduling
        },
    },
    mutations: {
        FETCH_CREW_SCHEDULING(state, payload) {
            state.crewScheduling = payload
        },
    },
    actions: {
        async crewCreate({commit, dispatch, rootGetters}, payload) {
            console.log('create',payload);
            try {
                let response = await Axios.post('/crew_scheduling/async', {
                    func: 'create',
                    values: payload
                });
                if (await response.data) {
                    await dispatch('createLog', {
                        log: {
                            id: response.data.id,
                            model: 'crew',
                            state: 'create',
                            user: rootGetters.h.user.id,
                            object: response.data,
                        },
                        mess: {
                            success: 'Занятость: зафиксировано',
                            error: 'Занятость: зафиксировать не удалось 1',
                        },
                        trigger: 'fetch-crew',
                    });
                }
            } catch (error) {
                console.log(error);
            }
        },

        async crewUpdate({commit, dispatch, rootGetters}, payload){
            console.log('update',payload);
            try {
                let response = await Axios.post('/crew_scheduling/async',{
                    func: 'update',
                    id: payload.id,
                    values: payload
                });
                if(await response.data){
                    await dispatch('createLog',{
                        log:{
                            id: response.data.id,
                            model: 'crew',
                            state: 'update',
                            user: rootGetters.h.user.id,
                            field: '',
                            object: response.data,
                        },
                        mess: {
                            success: 'Занятость: зафиксировано',
                            error: 'Занятость: зафиксировать не удалось 2',
                        },
                        trigger: 'fetch-crew',
                    });
                    // await commit('FETCH_CREW_SCHEDULING',response.data);
                }
            } catch (error) {
                console.log(error);
            }
        },
    }
};



export default crewSchedulingTable;
