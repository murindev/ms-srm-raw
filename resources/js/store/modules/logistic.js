import Axios from "axios";
import {bus} from "../../bus";
const error = (mess, state = '') => {
    bus.$emit('error', {table: 'Логистика:', mess: mess, state: state});
};
const success = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Логистика:', mess: mess});
};
const logistic = {
    state: {
        openMap: false,
        m:{},
        floating:{},
        tempImplement:{
            id:'',
            lid_id:'',
            parent:'',
            date:'',
            lat: null,
            lon: null,
            start_date: null,
            end_date: null,
            master: null,
            status: null,
        },
        logisticDate:null,
        activeRect:'',
    },
    getters: {
        m(state) {
            return state.m
        },
        openMap(state) {
            return state.openMap
        },
        floating(state) {
            return state.floating
        },
        tempImplement(state) {
            return state.tempImplement
        },
        logisticDate(state) {
            return state.logisticDate
        },
        activeRect(state) {
            return state.activeRect
        },

    },
    mutations: {
        FETCH_M(state, payload) {
            state.m = payload
        },
        OPEN_MAP(state, payload) {
            state.openMap = payload
        },
        FETCH_FLOATING(state, payload) {
            state.floating = payload
        },
        TEMP_IMPLEMENT(state,payload) {
            state.tempImplement = payload
        },
        LOGISTIC_DATE(state,payload) {
            if(payload.indexOf("T") > 0) {
                let d = payload.split('T')
                state.logisticDate = d[0]
            } else {
                state.logisticDate = payload
            }

        },
        SET_MASTER(state,payload) {
            if(payload.key === 'rect'){
                state.activeRect = payload.value
            } else {
                state.tempImplement[payload.key]  = payload.value
            }
        },
        UNSET_TEMP_IMPLEMENT(state){
            return state.tempImplement = {
                id:'',
                lid_id:'',
                parent:'',
                date:'',
                lat: null,
                lon: null,
                start_date: null,
                end_date: null,
                master: null,
                status: null,
            };
        },
    },
    actions:{
        /**
         *
         * @param commit
         * @param payload
         * @returns {Promise<void>}
         */
        async fetchSchedule({commit},payload ){ // date
            let masterData,floatingData;
            try {
                let responseMaster = await Axios.post('/map/async',{func: 'schedule', date: payload});

                 masterData = responseMaster.data;

            } catch (error) {

                console.log(error);
            }
            try {
                let responseFloating = await Axios.post('/map/async',{func: 'floating', date: payload});
                 floatingData = responseFloating.data;

            } catch (error) {

                console.log(error);
            }
            await commit('FETCH_M',masterData);
            await commit('FETCH_FLOATING',floatingData);
        },
        async logisticGetAct(id){
            try {
                let response = await Axios.post('/act/async',{func:'getActByID',id:id});
                return  response.data;
                // if(response.data !== 0){return  response.data;}
            }
            catch (e) {console.log(e);}
        },
/*        async activeImplement({dispatch},payload){
            console.log('payload',payload);
            try {
                let response = await Axios.post('/implement/async',{func: 'get',id:payload.id});
                let act = await dispatch('logisticGetAct',payload.parent);
                await console.log('response',response.data);
                await console.log('act',act);
            } catch (error) {
                console.log(error);
            }
        },*/

/*
* Сформировать tempImplement
* Назначить date_start
* Назначить date_end
* Подтвердить/Назначить смену мастера
* перегрузить Schedule
*
*
* */


        activeImplement({commit,getters,rootGetters},payload){

            Axios.post('/implement/async',{func: 'get',id:payload.id})
                .then(response => {
                    commit('SET_MASTER',{ key:'lat' ,value:rootGetters.acts[payload.parent].lat});
                    commit('SET_MASTER',{ key:'lon' ,value:rootGetters.acts[payload.parent].lon});
                    commit('SET_MASTER',{ key:'id' ,value:payload.id});
                    commit('SET_MASTER',{ key:'lid_id' ,value:response.data.lid_id});
                    commit('SET_MASTER',{ key:'parent' ,value:response.data.parent});
                    commit('SET_MASTER',{ key:'date' ,value:getters.logisticDate});
                    commit('SET_MASTER',{ key:'old_date' ,value:response.data.date});
                    commit('SET_MASTER',{ key:'old_master' ,value:response.data.master});
                    commit('SET_MASTER',{ key:'old_start' ,value:response.data.start_date});
                    commit('SET_MASTER',{ key:'old_end' ,value:response.data.end_date});
                    if(payload.start_date && payload.end_date && response.data.date === payload.date ) {
                        commit('SET_MASTER',{ key:'status' ,value:'highlight'});// подсветить если в даты одинаковые
                    } else if (payload.start_date && payload.end_date && response.data.date !== payload.date){
                        commit('SET_MASTER',{ key:'status' ,value:'cleaned'});//очистить для апдейта
                    } else {
                        commit('SET_MASTER',{ key:'status' ,value:'new'});// Новая постановка мастера
                    }
                })
        },
        setMasterAction({state,commit,getters},payload){
            let p = payload.split('_');
            if(!getters.tempImplement.start_date) {
                commit('SET_MASTER',{ key:'start_date' ,value:state.logisticDate + ' ' + p[0]});
                commit('SET_MASTER',{ key:'master' ,value:p[1]});
                commit('SET_MASTER',{ key:'rect' ,value:payload});
            } else if (getters.tempImplement.start_date && getters.tempImplement.master === p[1]){

                let nextArr = p[0].split(':');
                let res = '';
                switch ( nextArr[1]) {
                    case '00':
                        res = nextArr[0]+ ':'+'15:00';
                        break;
                    case '15':
                        res = nextArr[0]+ ':'+'30:00';
                        break;
                    case '30':
                        res = nextArr[0]+ ':'+'45:00';
                        break;
                    case '45':
                        let d = Number(nextArr[0]) + 1;
                        res = d >= 10 ? d + ':'+'00:00': '0' + d + ':'+'00:00';
                }
                commit('SET_MASTER',{ key:'end_date' ,value:getters.logisticDate + ' ' + res});
                commit('SET_MASTER',{ key:'rect' ,value:null});
            } else {
                error(`Вы редактируете мастера с id ${getters.tempImplement.master} `);
            }
        },

        removeMasterAction({state,commit,getters}){
            commit('SET_MASTER',{ key:'start_date' ,value:null});
            commit('SET_MASTER',{ key:'end_date' ,value:null});
            commit('SET_MASTER',{ key:'master' ,value:null});
        },

        async assignMaster({state,commit,dispatch,getters}){
            let lid_id = getters.tempImplement.lid_id;
            try {
                let response = await Axios.post('/implement/async', {
                    func: 'update', id: getters.tempImplement.id, values: {
                        date:getters.tempImplement.date,
                        master:getters.tempImplement.master,
                        start_date:getters.tempImplement.start_date,
                        end_date:getters.tempImplement.end_date,
                    }
                });
                if( response.data !== 0){
                  await  dispatch('fetchSchedule', getters.logisticDate);
                  await  commit('UNSET_TEMP_IMPLEMENT');
                  await dispatch('fetchActs',lid_id)
                  bus.$emit('reinit-layers')
                    success('Мастер назначен')
                }
            } catch (er) {
                console.log(er);
               await error('Мастер HE назначен')
            }
        },



        // ACTS
        async updateMapAct({state,dispatch, commit}, payload) {
            return await Axios.post('/act/async', {func: 'update', id: payload.id, values: payload})
                .then(response => {
                    if(response.data !== 0) {
                        success();
                        dispatch('fetchSchedule',state.logisticDate)
                        // commit('FETCH_ACT', response.data);
                    }
                })
                .catch(er => {
                    console.log(er);
                    error('Ошибка обновления акта ' + payload.id)
                });
        },
        async createMapVolume({state,dispatch, commit}, payload){
            try {
                let response = await Axios.post('/volume/async',{func: 'create', values: payload});
                if(response.data){
                    await dispatch('fetchSchedule',state.logisticDate);
                    success();
                }
            } catch (er) {console.log(er);error();}
        },
        async removeMapVolume({state,dispatch, commit}, payload){
            try {
                let response = await Axios.post('/volume/async',{func: 'remove', id: payload.id});
                if(response.data){
                    await dispatch('fetchSchedule',state.logisticDate);
                    success();
                }
            } catch (er) {console.log(er);error();}
        },

        // MAP
        async createMapImplement({dispatch, commit,getters}, payload){
            try {
                let response = await Axios.post('/implement/async',{
                    func: 'create',
                    values: payload
                });
                if(response.data){
                    await dispatch('fetchSchedule',getters.logisticDate);
                    success();
                }
            } catch (er) {console.log(er);error();}
        },
        activeMapImplement({commit,getters,rootGetters},payload){
            console.log('payload',payload);
            Axios.post('/implement/async',{func: 'get',id:payload.id})
                .then(response => {
                    commit('SET_MASTER',{ key:'lat' ,value:rootGetters.acts[payload.parent].lat});
                    commit('SET_MASTER',{ key:'lon' ,value:rootGetters.acts[payload.parent].lon});
                    commit('SET_MASTER',{ key:'id' ,value:payload.id});
                    commit('SET_MASTER',{ key:'lid_id' ,value:response.data.lid_id});
                    commit('SET_MASTER',{ key:'parent' ,value:response.data.parent});
                    commit('SET_MASTER',{ key:'date' ,value:getters.logisticDate});
                    commit('SET_MASTER',{ key:'old_date' ,value:response.data.date});
                    commit('SET_MASTER',{ key:'old_master' ,value:response.data.master});
                    commit('SET_MASTER',{ key:'old_start' ,value:response.data.start_date});
                    commit('SET_MASTER',{ key:'old_end' ,value:response.data.end_date});
                    if(payload.start_date && payload.end_date && response.data.date === payload.date ) {
                        commit('SET_MASTER',{ key:'status' ,value:'highlight'});// подсветить если в даты одинаковые
                    } else if (payload.start_date && payload.end_date && response.data.date !== payload.date){
                        commit('SET_MASTER',{ key:'status' ,value:'cleaned'});//очистить для апдейта
                    } else {
                        commit('SET_MASTER',{ key:'status' ,value:'new'});// Новая постановка мастера
                    }
                })
        },



    }
};


export default logistic;
