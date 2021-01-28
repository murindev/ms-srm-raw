import Axios from "axios";
import {bus} from "../../bus";
import Message from 'heyui/lib/plugins/message'
Message.config({
    timeout: 3000
});

// heyui.$Message('This is a normal reminder');

const error = (mess, state = '') => {
    bus.$emit('error', {table: 'Логистика', mess: mess, state: state});
};
const fixIt = (mess, state = '') => {
    bus.$emit('fix-it', {table: 'Логистика', mess: mess, state: state});
};
const success = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Логистика', mess: mess});
};


const logs = {
    state: {
        logs: {}
    },
    getters: {
        logs(state) {
            return state.logs
        }
    },
    mutations: {
        FETCH_LOGS(state, payload) { state.logs = payload},
    },

    actions: {
        // INIT ACTIONS
        fetchLogs({dispatch, commit, getters}, payload) {
            return Axios.post('/logs/async', {
                func: 'helpers'
            })
                .then(response => commit('FETCH_HELPERS', response.data))
                .catch(error => console.log(error));
        },
        async createLog({dispatch, commit, getters}, pld){

              pld.log.date = + new Date();

            try {
                let response = await Axios.post('/mongo_log/async',{
                    func: 'create',
                    values: pld.log
                });
                if(await response.data){
                    if('trigger' in pld){
                        await bus.$emit(pld.trigger);
                    }
                    await Message['success'](pld.mess.success);
                } else {
                    await Message['error'](pld.mess.error);
                }
            } catch (e) {
                await Message['error'](pld.error);
                console.log(e);
            }
        },




    }
};


export default logs;
