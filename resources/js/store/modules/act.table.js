import Axios from "axios";
import {bus} from "../../bus";

const error = (mess, state = '') => {
    bus.$emit('error', {table: 'Акт', mess: mess, state: state});
};
const success = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Акт', mess: mess});
};
const conFirm = (mess, method, payload) => {
    bus.$emit('con-firm', {table: 'Акт', mess, method, payload});
};
const actsTable = {
    state: {
        acts: {},
        actsCopy: [],
        actsCopyState: false,
    },
    getters: {
        acts(state) {
            return state.acts
        },
        act(state, id) {
            return state.acts[id]
        },
        actsCopy(state) {
            return state.actsCopy
        },
        actsCopyState(state) {
            return state.actsCopyState
        },
/*
        entity:2
        id:1489
        kpi:null
        lid_id:407
        method:Array[1]
        note:null
        parent:1166
        pest:9
        price_fact:5000
        price_standard:null
        square:40
        square_fact:33
        */
    },
    mutations: {
        FETCH_ACTS(state, payload) {
            state.acts = payload
        },
        FETCH_ACT(state, payload) {
            state.acts[payload.id] = payload
        },
        // copying
        ACTS_COPY(state, payload){
            state.actsCopy = payload;
        },
        ACT_CHECK_ALL(state, payload) {
            state.actsCopy = [];
            for(let i in state.acts ) if(!state.acts[i].subact && payload){
                 state.actsCopy.push(state.acts[i].id)
            }
        },
        ACT_COPY_MANAGE(state, payload) {
            console.log(payload);
            if(payload.val){
                state.actsCopy.push(payload.id);
            } else {
                let index = state.actsCopy.indexOf(payload.id);
                if (index !== -1) {
                    state.actsCopy.splice(index, 1);
                }
            }
            state.actsCopyState = !!state.actsCopy.length
        },
    },
    actions: {
        // ACT
        async fetchActs({commit}, parent) {
            try {
                let response = await Axios.post('/act/async', {func: 'getActs', value: parent});
                if (response.data) {
                    await commit('FETCH_ACTS', response.data);
                }
            } catch (e) {
                console.log(e);
            }
        },
        async updateAct({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/act/async', {func: 'update', id: payload.id, values: payload});
                if (response.data !== 0) {
                    await dispatch('fetchActs', response.data.parent);
                    success();
                }
            } catch (e) {
                console.log(e);
                error('Ошибка обновления акта ' + payload.id)
            }
        },

        async removeConfirmAct({dispatch, commit}, payload) {
            conFirm('Удалить Акт', 'removeAct', payload);
        },

        async removeAct({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/act/async', {func: 'remove', id: payload.id});
                if (response.data !== 0) {
                    dispatch('fetchLid', payload.parent)
                    success('Удалено');
                }
            } catch (e) {
                console.log(e);
                error('Ошибка удалениия акта ' + payload.id)
            }
        },
        async createAct({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/act/async', {
                    func: 'create',
                    values: payload
                });
                if (response.data) {
                    dispatch('fetchLid', payload.parent)
                }
            } catch (e) {
                error();
                console.log(e);
            }
        },

        fetchAct({commit}, payload) {
            return Axios.post('/act/async', {func: 'getActByID', id: payload})
                .then(response => commit('FETCH_ACT', response.data))
                .catch(error => console.log(error));
        },

        updateActField({commit}, payload) {
            return Axios.post('/act/async', {
                func: 'updateField',
                id: payload.id,
                field: payload.field,
                value: payload.value
            })
                .then(response => {
                    commit('FETCH_ACT', response.data);
                    success()
                })
                .catch(error => {
                    console.log(error);
                    error()
                });
        },
        async collectSubActs({state,commit,getters,dispatch}, actID){
            let subActs = {};
            for(let a in state.acts) if(state.acts[a].subact == actID){
                subActs[state.acts[a].id] = state.acts[a];
            }
            return await subActs;
        },
        async setSubActNr({commit,getters,dispatch}, actID){
            let obj = await dispatch('collectSubActs',actID);
            return await Object.keys(obj).length + 1;
        },
        async createSubAct({commit,getters,dispatch,rootGetters}, payload){
            let volumes = await payload.volumes;
            let  currentAct = {
                act_nr: payload.act_nr + '/' + await dispatch('setSubActNr',payload.id),
                subact: payload.id,
                creator: rootGetters.h.user.id,
                address: payload.address,
                contact: payload.contact,
                contact_name: payload.contact_name,
                destination: payload.destination,
                lat: payload.lat,
                lon: payload.lon,
                parent: payload.parent,
                phone: payload.phone,
                phone_ext: payload.phone_ext,
                phone_ext_sub: payload.phone_ext_sub,
                phone_sub: payload.phone_sub,
                prefer_time_from: payload.prefer_time_from,
                prefer_time_to: payload.prefer_time_to,
                region: payload.region
            }
            console.log('currentAct',currentAct);
            try {
                let response = await Axios.post('/act/async',{
                    func: 'create',
                    values: currentAct
                });
                if(await response.data){
                    success(`Создан гарантийный акт ${response.data}`);
                    for (let v in volumes){
                        volumes[v].method ? volumes[v].method.join(',') : null;
                        volumes[v].parent = response.data;
                       await dispatch('createSubVolume',volumes[v]);
                    }
                    await dispatch('fetchActs',payload.parent);
                }
            } catch (e) {
                console.log(e);
                error('Гарантийный акт не создан')

            }
        },
        async createSubVolume({commit,getters,dispatch}, payload){
            try {
                let response = await Axios.post('/volume/async', {
                    func: 'create',
                    values: payload
                });
                if (response.data) {
                    success(`Создана услуга id ${response.data}`);
                }
            } catch (er) {
                console.log(er);
                error('Ошибка создания услуги');
            }
        },
        async copyActs({commit,getters,dispatch}, payload){
            try {
                let response = await Axios.post('/act/async',{
                    func: 'copyMany',
                    value: getters.actsCopy,
                });
                if(response.data){
                    dispatch('fetchLid',response.data);
                    commit('ACTS_COPY',[]);
                }
            } catch (e) {
                console.log(e);
            }
        },






        //VOLUME
        async updateVolume({dispatch, commit}, payload) {
            let str = payload.method ? payload.method.join(',') : null;
            let newPayload = {}
            for (let key in payload) {
                newPayload[key] = key === 'method' ? str : payload[key]
            }
            try {
                let response = await Axios.post('/volume/async', {
                    func: 'update',
                    id: newPayload.id,
                    values: newPayload
                });
                if (response.data) {
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        async removeConfirmVolume({dispatch, commit}, payload) {
            conFirm('Удалить услугу?', 'removeVolume', payload);
        },
        async removeVolume({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/volume/async', {func: 'remove', id: payload.id});
                if (response.data) {
                    await dispatch('fetchAct', payload.act);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        async createVolume({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/volume/async', {func: 'create', values: payload});
                if (response.data) {
                    await dispatch('fetchAct', payload.parent);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },

        //IMPLEMENT
        async createImplement({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/implement/async', {
                    func: 'create',
                    values: payload
                });
                if (response.data) {
                    await dispatch('fetchActs', payload.lid_id);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        /**
         * @function removeImplement
         * @param dispatch
         * @param commit
         * @param payload
         * @returns {Promise<void>}
         */
        async removeImplement({dispatch, commit}, payload) {

            console.log(payload);
            try {
                let response = await Axios.post('/implement/async', {func: 'remove', id: payload.id});
                if (response.data) {
                    await dispatch('fetchActs', payload.lid_id);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        /**
         *  @function updateImplement
         * @returns {Promise<void>}
         */
        async updateImplement({dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/implement/async', {func: 'update', id: payload.id, values: payload});
                if (response.data) {
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },

    }
};


export default actsTable;
