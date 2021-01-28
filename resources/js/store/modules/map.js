import Axios from "axios";
import {bus} from "../../bus";
import store from  "../../store/index"


const error = (mess, state = '') => {
    bus.$emit('error', {table: 'Логистика', mess: mess, state: state});
};
const fixIt = (mess, state = '') => {
    bus.$emit('fix-it', {table: 'Логистика', mess: mess, state: state});
};
const success = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Логистика', mess: mess});
};

 bus.$on('the-trigger', function()  {
     store.dispatch('fetchLogisticDate',map.state.logisticDate)
});


function floatingIconAdjust(act) {
    let now = new Date();
    let start = new Date(act.floating_date_from);
    let end = new Date(act.floating_date_to);
    let state;

    now = Math.floor(now.getTime() / 1000);
    start = Math.floor(start.getTime() / 1000);
    end = Math.floor(end.getTime() / 1000);
    let diff = (end - start) / 3;

    if (now <= (start + diff)) {
        state = 'slow'
    } else if (now <= (start + diff * 2)) {
        state = 'normal';
    } else {
        state = 'hurry';
    }
    return state;
}

function checkDocs(act, lids) {
    let booking, implement, contract;
    if (act.booking_act_transferred) {
        booking = 'blue';
    } else if (act.booking_act_signed) {
        booking = 'green';
    } else if (act.booking_act_signed && act.booking_act_file) {
        booking = 'transparent';
    } else {
        booking = 'red';
    }

    if (act.implement_act_signed) {
        implement = 'blue';
    } else if (act.implement_act_file) {
        implement = 'transparent';
    } else {
        implement = 'red';
    }
    let lid = lids[act.parent];

    if (lid && lid.contract_transferred) {
        contract = 'blue';
    } else if (lid && lid.contract_signed) {
        contract = 'green';
    } else if (lid && lid.contract_signed && lid.contract_file) {
        contract = 'transparent';
    } else {
        contract = 'red';
    }

    return '<span class="contract ' + contract + '"></span><span class="account-act ' + booking + '"></span><span class="act ' + implement + '"></span>';
}

function checkState(s, e, finished) {
    let now = new Date();
    let start = new Date(s);
    let end = new Date(e);
    let state;
    if (finished) {
        state = 'finished';
    } else {
        if (now.getTime() >= start.getTime() && now.getTime() <= end.getTime())
            state = 'progress';
        else if (now.getTime() <= start.getTime())
            state = 'waiting';
        else if (now.getTime() > end.getTime())
            state = 'late';
        else
            state = 'late';
    }
    return state;
}

function setDate() {
    let now = new Date();
    now = now.toISOString();
    return now.split('T')[0];
}

function checkErrors(val, check, geo, text) {
    if (val !== check) {
        return false;
    } else {
        fixIt(text,geo)
        return true;
    }

}

function diffTime(ObjImplements) {
    let keys = Object.keys(ObjImplements); //получаем ключи объекта в виде массива
    let date = ObjImplements[keys[0]].start_date;
    let now = new Date();
    let required = new Date(date);
    return required.getTime() - now.getTime();
}




const map = {
    state: {
        actModal: false,
        docModal: false,
        mapSwitch: false,
        foreignImplement:null,
        DGMap: {},
        logisticDate: setDate(),//'2019-10-05'
        points: {},
        schedule: {},
        markers: {},
        popups: {},
        polylines: {},
        mapLids: {},
        mapActs: {},
        masters: {},
        newImplement: {
            id: '',
            lid_id: '',
            parent: '',
            date: '',
            lat: null,
            lon: null,
            start_date: null,
            end_date: null,
            master: null,
            status: null,
            user: null,
            activeRect: null,
        },
        masterSchedule: null,
    },
    getters: {
        actModal(state) {
            return state.actModal
        },
        docModal(state) {
            return state.docModal
        },
        mapSwitch(state) {
            return state.mapSwitch
        },
        foreignImplement(state) {
            return state.foreignImplement
        },
        DGMap(state) {
            return state.DGMap
        },
        points(state) {
            return state.points
        },
        logisticDate(state) {
            return state.logisticDate
        },
        schedule(state) {
            return state.schedule
        },
        markers(state) {
            return state.markers
        },
        popups(state) {
            return state.popups
        },
        polylines(state) {
            return state.polylines
        },
        mapLids(state) {
            return state.mapLids
        },
        mapActs(state) {
            return state.mapActs
        },
        masters(state) {
            return state.masters
        },
        newImplement(state) {
            return state.newImplement
        },
        masterSchedule(state) {
            return state.masterSchedule
        },
    },
    mutations: {
        DGMAP(state, payload) {
            state.DGMap = payload
        },
        ACT_MODAL(state, payload) {
            state.actModal = payload
        },
        DOC_MODAL(state, payload) {
            state.docModal = payload
        },
        MAP_SWITCH: (state, payload) => {
            state.mapSwitch = payload
        },
        FOREIGN_IMPLEMENT: (state, payload) => {
            state.foreignImplement = payload
        },
        POINTS(state, payload) {
            state.points = payload
        },
        LOGISTIC_DATE(state, payload) {
            state.logisticDate = payload
        },
        SCHEDULE(state, payload) {
            state.schedule = payload
        },
        MAP_LIDS(state, payload) {
            state.mapLids = payload
        },
        MAP_ACTS(state, payload) {
            state.mapActs = payload
        },
        MASTERS(state, payload) {
            state.masters = payload
        },
        MARKERS(state, payload) {
            state.markers = payload
        },
        POPUPS(state, payload) {
            state.popups = payload
        },
        POLYLINES(state, payload) {
            state.polylines = payload
        },
        MASTER_SCHEDULE(state, payload) {
            state.masterSchedule = payload
        },
        NEW_IMPLEMENT(state, payload) {
            if (payload.hasOwnProperty('key')) {
                state.newImplement[payload.key] = payload.value
            } else {
                state.newImplement = payload
            }

        },
    },
    actions: {
        tFoo({dispatch}){
            console.log('tFoo');
            dispatch('fetchMap')
            // console.log(this);
            },
        // MAP INTERACTIONS
        constructMarkers({state, dispatch, commit, getters}, payload) {

            let m = DG.featureGroup();
            let p = DG.featureGroup();
            for (let geo in payload) {
                for (let act of payload[geo]) {
                    if (act.hasOwnProperty('scheduled')) {
// scheduled
                        let masterDiv = '';
                        for (let implement in act.implements) {
                            let i = act.implements[implement];
                            if (checkErrors(i.start_date, null, geo, 'Не заполнена дата начала обработки')) {
                                continue
                            }
                            if (checkErrors(i.end_date, null, geo, 'Не заполнена дата окончания обработки')) {
                                continue
                            }
                            if (checkErrors(i.master, null, geo, 'Не установлен мастер')) {
                                continue
                            }

                            let implementState = checkState(i.start_date, i.end_date, act.finished);

                            masterDiv += `<div class="inner ${implementState}" >${state.masters[i.master].id}</div>`;

                            /*style="background: ${state.masters[i.master].color}; color: ${state.masters[i.master].text_color} " */
                        }

                        let divIcon = DG.divIcon({
                            className: 'divIcon-master',
                            html: masterDiv + checkDocs(act, state.mapLids)
                        });
                        p[geo] = DG.popup().setLatLng(geo.split(':')).setContent('').addTo(p);
                        m[geo] = DG.marker(geo.split(':'), {icon: divIcon}).addTo(m);

                    } else {
// floating
                        let html = '';
                        // console.log('4444',state.mapLids[act.parent].id);
                        if(state.mapLids[act.parent] !== undefined){
                             html = state.mapLids[act.parent].contract.slice(0,5);
                        } else {
                            break;
                        }

                        if(act.hasOwnProperty('implements') && Object.keys(act.implements).length){
                            let leftOrWait = diffTime(act.implements) > 0 ? 'divIcon-float-wait':'divIcon-float-left';

                            let divIconFloating = DG.divIcon({
                                className: leftOrWait + ' ' + floatingIconAdjust(act),
                                html:html
                            });
                            m[geo] = DG.marker(geo.split(':'), {icon: divIconFloating}).addTo(m);
                        } else {
                            let divIconFloating = DG.divIcon({
                                className: 'divIcon-float ' + floatingIconAdjust(act),
                                html:html
                            });
                            m[geo] = DG.marker(geo.split(':'), {icon: divIconFloating}).addTo(m);
                        }
                    }
                }
                commit('POPUPS', p);
                m[geo].on('click', (a) => {
                    bus.$emit('act-map', geo);
                    commit('ACT_MODAL', true);

                });
            }

            return m;
        },
        openPopup({state, dispatch, commit, getters}, payload) {
            DG.then(() => {
                state.popups[getters.mapActs[payload].geo].addTo(state.DGMap)
            })
        },
        async aMapSwitch({state, dispatch, commit, getters}, payload) {
            if(state.foreignImplement){
              await  dispatch('fetchLid',state.foreignImplement.lid_id);
              await commit('FOREIGN_IMPLEMENT',null);
            }
           await commit('MAP_SWITCH',payload)
        },

        async fetchRoutes({state, dispatch, commit, getters}, schedule) {

            let coordinates = [];
            let polylines = DG.featureGroup()
            for (let masterID in schedule) {
                let tlat = '55.77321337302965';
                let tlon = '37.50043094158173';

                for (let implement of schedule[masterID]) {
                    let act = getters.mapActs[implement.parent];

                    Axios.post('/dadata/async', {
                        func: 'osmRoutes',
                        flat: act.lat,
                        flon: act.lon,
                        tlat: tlat,
                        tlon: tlon,
                        // region: 'Москва',
                    })
                        .then(response => {

                            let revert = [];
                            for (let c in response.data.coordinates) {
                                revert[c] = [response.data.coordinates[c][1], response.data.coordinates[c][0]]
                            }
                            polylines[act.lat + ':' + act.lon] = DG.polyline(revert, {
                                color: getters.masters[masterID].color,
                                className: 'route_' + masterID,
                                weight: 3
                            }).addTo(polylines)
                        })
                        .catch(er => {
                            // console.log(er);
                        });
                    tlat = act.lat;
                    tlon = act.lon;
                }
            }
            // polylines.addTo(state.DGMap)
            return polylines;
            // await commit('POLYLINE',polyline);

        },


        async fetchLogisticDate({state, dispatch, commit, getters}, paiload) {
            await commit('LOGISTIC_DATE', paiload)

            await state.markers.removeFrom(state.DGMap);
            await state.polylines.removeFrom(state.DGMap);

            let response = await Axios.post('/map/async', {
                func: 'setMap',
                date: getters.logisticDate,
                lid_act: state.foreignImplement ? state.foreignImplement.parent : false
            });
            await commit('MASTERS', response.data.masters);
            await commit('MAP_ACTS', response.data.points);
            await commit('POINTS', response.data.markers);
            await commit('MAP_LIDS', response.data.lids);
            await commit('SCHEDULE', response.data.schedule);
            await commit('MARKERS', await dispatch('constructMarkers', response.data.markers));
            await commit('POLYLINES', await dispatch('fetchRoutes', response.data.schedule));
            await state.markers.addTo(state.DGMap);
            await state.polylines.addTo(state.DGMap);
            await state.DGMap.fitBounds(state.markers.getBounds());
            // await dispatch('fetchRoutes');
            console.log('fetchLogisticDate');

        },

        async fetchMap({state, dispatch, commit, getters}) {
            console.log('fetched');
            try {
                let response = await Axios.post('/map/async', {
                    func: 'setMap',
                    date: state.logisticDate,
                    lid_act: state.foreignImplement ? state.foreignImplement.parent : false
                });
                await commit('MASTERS', response.data.masters);
                await commit('MAP_ACTS', response.data.points);
                await commit('POINTS', response.data.markers);
                await commit('MAP_LIDS', response.data.lids);
                await commit('SCHEDULE', response.data.schedule);
                await commit('MARKERS', await dispatch('constructMarkers', response.data.markers));
                await commit('POLYLINES', await dispatch('fetchRoutes', response.data.schedule));
                await commit('DGMAP', DG.map('map', {'center': [55.748696, 37.622275], 'zoom': 12}));
                await state.markers.addTo(state.DGMap);
                await state.polylines.addTo(state.DGMap);
                await state.DGMap.fitBounds(state.markers.getBounds());

                // await dispatch('fetchRoutes');
            } catch (error) {
                console.log(error);
            }
        },


        showRoute({state, dispatch, commit, getters}, payload) {
            let lines = document.getElementsByClassName('route_' + payload);
            // commit('MASTER_SCHEDULE',`/schedule/master?date=${state.logisticDate}&master=${payload}`);
            dispatch('printMasterSchedule',payload);
            for (let i = 0, len = lines.length; i < len; i++) {
                lines[i].style["stroke-width"] = window.getComputedStyle(lines[i], null)["stroke-width"] === '8px' ? '3px' : '8px';
            }
        },


        // ACT
        async updateMapAct({state, dispatch, commit}, payload) {
            return await Axios.post('/act/async', {func: 'update', id: payload.id, values: payload})
                .then(response => {
                    if (response.data !== 0) {

                        dispatch('fetchMap', state.logisticDate)
                        success();
                        // commit('FETCH_ACT', response.data);
                    }
                })
                .catch(er => {
                    console.log(er);
                    error('Ошибка обновления акта ' + payload.id)
                });
        },

        // VOLUMES
        async createMapVolume({state, dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/volume/async', {func: 'create', values: payload});
                if (response.data) {
                    await dispatch('fetchMap', state.logisticDate);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        async removeMapVolume({state, dispatch, commit}, payload) {
            try {
                let response = await Axios.post('/volume/async', {func: 'remove', id: payload.id});
                if (response.data) {
                    await dispatch('fetchMap', state.logisticDate);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },
        // IMPLEMENTS
        async createMapImplement({dispatch, commit, getters}, payload) {
            try {
                let response = await Axios.post('/implement/async', {
                    func: 'create',
                    values: payload
                });
                if (response.data) {

                    await dispatch('fetchLogisticDate', getters.logisticDate);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },

        async removeMapImplement({dispatch, commit, getters}, payload) {
            try {
                let response = await Axios.post('/implement/async', {
                    func: 'remove',
                    id: payload.id
                });
                if (response.data) {
                    await dispatch('fetchLogisticDate', getters.logisticDate);
                    success();
                }
            } catch (er) {
                console.log(er);
                error();
            }
        },

        // -> implement rules



        // MASTER


        async setMaster({dispatch, commit, getters}, payload) {
            let I = {
                id: payload.id,
                lid_id: payload.lid_id,
                parent: payload.parent,
                date: payload.date,
                lat: getters.mapActs[payload.parent].lat,
                lon: getters.mapActs[payload.parent].lon,
                start_date: null,
                end_date: null,
                master: null,
                status: null,
                user: payload.user,
                activeRect: null,
                old_master: payload.master,
                old_start: payload.start_date,
                old_end: payload.end_date,
            }
            await commit('NEW_IMPLEMENT', I);
            if (payload.date === getters.logisticDate) {
                commit('NEW_IMPLEMENT', {key: 'status', value: 'highlight'});
            } else {
                await dispatch('fetchLogisticDate', payload.date)
            }
            await commit('ACT_MODAL', false);

        },
        async setMasterField({commit}, payload) {
            commit('NEW_IMPLEMENT', payload)
        },

        setMasterAction({state, commit, getters}, payload) {
            let p = payload.split('_');
            if (!getters.newImplement.start_date) {
                commit('NEW_IMPLEMENT', {key: 'start_date', value: state.logisticDate + ' ' + p[0]});
                commit('NEW_IMPLEMENT', {key: 'master', value: p[1]});
                // commit('NEW_IMPLEMENT',{ key:'status' ,value:'start'});
                commit('NEW_IMPLEMENT', {key: 'activeRect', value: payload});
            } else if (getters.newImplement.start_date && getters.newImplement.master === p[1]) {
                let nextArr = p[0].split(':');
                let res = '';
                switch (nextArr[1]) {
                    case '00':
                        res = nextArr[0] + ':' + '15:00';
                        break;
                    case '15':
                        res = nextArr[0] + ':' + '30:00';
                        break;
                    case '30':
                        res = nextArr[0] + ':' + '45:00';
                        break;
                    case '45':
                        let d = Number(nextArr[0]) + 1;
                        res = d >= 10 ? d + ':' + '00:00' : '0' + d + ':' + '00:00';
                }
                commit('NEW_IMPLEMENT', {key: 'end_date', value: getters.logisticDate + ' ' + res});
                commit('NEW_IMPLEMENT', {key: 'activeRect', value: null});
            } else {
                error(`Вы редактируете мастера с id ${getters.newImplement.master} `);
            }
        },
        removeMasterAction({state, commit, getters,dispatch}) {
            commit('NEW_IMPLEMENT', {key: 'start_date', value: null});
            commit('NEW_IMPLEMENT', {key: 'end_date', value: null});
            commit('NEW_IMPLEMENT', {key: 'master', value: null});
            // commit('NEW_IMPLEMENT',{ key:'activeRect' ,value:null});
        },

        async confirmSetMaster({state, commit, getters, dispatch}) {
            let I =  {
                id: getters.newImplement.id,
                parent: getters.newImplement.parent,
                lid_id: getters.newImplement.lid_id,
                date: getters.newImplement.date,
                master: getters.newImplement.master,
                start_date: getters.newImplement.start_date,
                end_date: getters.newImplement.end_date,
                user: getters.h.user.id,
            }
            try {
                let response = await Axios.post('/implement/async', {
                    func: 'update',
                    id: I.id,
                    values: I
                });
                if(response.data){
                  await commit('NEW_IMPLEMENT',{
                        id: '',
                        lid_id: '',
                        parent: '',
                        date: '',
                        lat: null,
                        lon: null,
                        start_date: null,
                        end_date: null,
                        master: null,
                        status: null,
                        user: null,
                        activeRect: null,
                    });
                  await dispatch('fetchLogisticDate',getters.logisticDate);
                  bus.$emit('implement_changed');
                  success('Назначено');
                }
            } catch (e) {
                console.log(e);
                error('Мастер не назначен')
            }
        },

        // SEARCH
        showMapObject({state, commit, getters, dispatch},paiload){
          let  paiload2 = {
                "place_id": 94212264,
                "licence": "Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright",
                "osm_type": "way",
                "osm_id": 83959853,
                "boundingbox": ["55.6034359", "55.6160099", "37.2017939", "37.2175466"],
                "lat": "55.6104798",
                "lon": "37.2100471",
                "display_name": "улица Пушкина, Толстопальцево, Новомосковский административный округ, Москва, Центральный федеральный округ, 143350, Россия",
                "class": "highway",
                "type": "residential",
                "importance": 0.1
            }
            DG.popup({autoClose:false})
                .setLatLng([paiload.lat,paiload.lon])
                .setContent(paiload.display_name)
                .addTo(state.DGMap)
        },

        // DADATA Московская обл, Сергиево-Посадский р-н, деревня Пальчино, д 5

        async getDistance(payload) {
            try {
                if (payload.region &&payload.lat && payload.lon) {
                    let response = await Axios.post('/dadata/async', {
                        func: 'osm',
                        region: payload.region,
                        flat: payload.lat,
                        flon: payload.lon
                    });
                    if ('distance' in response.data.properties) {
                      await  success(`Расстояние ${response.data.properties.distance}  км от офиса`)
                        return await response.data.properties.distance
                    } else {
                        error(`Расстояние не определено`)
                        return 0
                    }
                } else {
                   await error('нехватает данных')
                }
            } catch (err) {
                console.log(err);
            }
        },

        // MAP DOCS

        aDocModal({commit},payload) {
            commit('DOC_MODAL',payload)
        },
        printMasterSchedule({state, commit, getters, dispatch},masterID){
            if(!getters.schedule.hasOwnProperty(masterID)){
                error('У этого мастера нет заданий');
                return false;
            }
            commit('MASTER_SCHEDULE','/print/schedule?date='+getters.logisticDate+'&master='+masterID+'&schedule='+JSON.stringify(getters.schedule[masterID]));


           /* window.open('/print/schedule?date='+getters.logisticDate+'&master='+masterID+'&schedule='+JSON.stringify(getters.schedule[masterID]),'_blank');*/

        },

    },
}


export default map;
