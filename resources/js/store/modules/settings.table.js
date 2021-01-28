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

const settingsTable = {
    state: {
        settings: {},
    },
    getters: {
        settings(state) {
            return state.settings
        },
    },
    mutations: {
        SETTINGS(state, payload) {
            state.settings = payload
        }
    },
    actions: {


        async readSettings({commit,getters},payload){
            try {
                let response = await Axios.post('/settings/async',{
                    func: 'read',
                    manager: payload,
                });
                if(response.data) {
                    commit('SETTINGS',response.data);
                }
            } catch (error) {
                console.log(error);
            }
        },
        async updateSettings({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/settings/async',{
                    func: 'update',
                    manager: payload.manager,
                    type: payload.type,
                    settings: payload.settings,
                });
                if(response.data) {
                    commit('SETTINGS',response.data);
                }
            } catch (error) {
                console.log(error);
            }
        },
        async deleteSettings({state, dispatch, commit,rootGetters},payload){
            try {
                let response = await Axios.post('/lid_statistic/async',{
                    func: 'sync',
                    id: state.lid.id,
                    object: lid
                });
                if(response.data){dispatch('readSettings');}
            } catch (error) {
                console.log(error);
            }
        },




    }
};

export default settingsTable;
