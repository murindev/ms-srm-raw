<template>

    <div>
        <Modal class="actModal" v-model="actModal" :fullScreen="true" :hasCloseIcon="true" v-if="actsInMap">
            <table class="h-table acts-tbl">
                <tr class="head">
                    <th>#</th>
                    <th>ఋ</th>
                    <th>Срок с</th>
                    <th>Срок до</th>
                    <th>Работы завершены?</th>
                    <th>Адрес</th>
                    <th>Бух. акт передан?</th>
                    <th>Бух. акт подписан?</th>
                    <th>Бух. акт скан</th>
                    <th>Исп. акт подписан?</th>
                    <th>Исп. акт скан</th>
                    <th></th>
                </tr>
                    <act-map :geoKey="geoKey" :type="actType"></act-map>
            </table>


        </Modal>
        <Modal v-model="openMap" type="drawer-left" :fullScreen="true" :hasCloseIcon="hasCloseIcon">
            <table class="h-table w100-vt " style="height: calc(100vh - 20px);">

                <thead>
                <tr>
                    <th colspan="2" class="spread">
                        <template v-if="this.case === 'standAlone'">

                            {{logisticDate}} <Button><DatePicker v-model="lDate" placeholder="Дата" :no-border="true" @change="setDate()" ></DatePicker></Button>


                        </template>
                        <template v-else><a>{{logisticDate}}</a></template>

                        <Button type="info" v-if="tempImplement.end_date" @click="assignMaster">Назначить мастера</Button>
                    </th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>
                        <div id="map" style="width:100%; height:100%"></div>
                    </td>
                    <td style="width: 520px" v-if="mas">
                        <template v-for="(master,masterID) in m.masters" v-if="master.active">
                            <div>
                                <a href="javascript:" @click="showRoute(masterID)" :style="'user-select: none; color:'+ master.color">{{master.name}}({{master.id}})</a>
                            </div>
                            <implement-day :points="m.byMasters[master.id]" :master="master.id"></implement-day>
                        </template>


                    </td>
                </tr>
                </tbody>

            </table>
        </Modal>
    </div>



</template>

<script>

    import ImplementDay from "./ImplementDay";
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    import Axios from 'axios';
    import ActMap from "./ActMap";

    export default {
        name: "LogisticMap",
        props: ['case','init_date','act_id','action'],
        data() {
            return {
                actModal: false,
                actsInMap: [],
                actType: '',
                geoKey: '',
                hasCloseIcon: this.case !== 'standAlone',

                date:'',
                setImplement: false,
                actToHighlight: null,
                mas: false,
                map: null,
                markers: {},
                floatingMarkers: {},
                popups: {},
                polyline: {},
                routes: [],
                tempPoints: {}
            }
        },
        components: {
            ActMap,
            ImplementDay
        },
        computed: {
            lDate: {
                get(){ return this.$store.logisticDate} ,
                set(value) {this.$store.commit('LOGISTIC_DATE', value )}
            },
            ...mapGetters(['h', 'm', 'floating','logisticDate','acts','tempImplement','openMap']),
        },
        methods: {
            ...mapMutations(['LOGISTIC_DATE','OPEN_MAP']),
            ...mapActions(['fetchSchedule','assignMaster','fetchHelpers']),
            checkState(s, e, finished) {
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

            },
            checkDocs(act) {
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
                let lid = this.m.lids[act.parent];

                if (lid.contract_transferred) {
                    contract = 'blue';
                } else if (lid.contract_signed) {
                    contract = 'green';
                } else if (lid.contract_signed && lid.contract_file) {
                    contract = 'transparent';
                } else {
                    contract = 'red';
                }

                return '<span class="contract ' + contract + '"></span><span class="account-act ' + booking + '"></span><span class="act ' + implement + '"></span>';


                /*            <span class="contract"></span><span class="account-act"></span><span class="act"></span*/
            },
            floatingIconAdjust(act) {
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
            },
            toggleClass: function (className) {
                event.target.classList.toggle(className)
            },
            showRoute(id) {
                let lines = document.getElementsByClassName('route_' + id);
                for (let i = 0, len = lines.length; i < len; i++) {
                    lines[i].style["stroke-width"] = window.getComputedStyle(lines[i], null)["stroke-width"] === '8px' ? '3px' : '8px';
                }
            },
            initMarkers() {
                this.floatingMarkers = null;
                this.mas = true;
                DG.then(() => {

                    this.floatingMarkers = DG.featureGroup();

                    for (let actKey in this.floating)  {
                        if (this.floating.hasOwnProperty(actKey)){
                            for (let act of this.floating[actKey]) {
                                let divIconFloating = DG.divIcon({className: 'divIcon-float ' + this.floatingIconAdjust(act)});
                                this.floatingMarkers[actKey] = DG.marker([act.lat, act.lon], {icon: divIconFloating}).addTo(this.floatingMarkers);
                                this.floatingMarkers[actKey].on('click', (a) => {
                                    this.geoKey = actKey;
                                    this.actType = 'floating';
                                    this.actModal = true;
                                    // this.openAct(actKey, 'floating')
                                })
                            }
                        } else {
                            console.log('floating.hasOwnProperty(actKey) is missing', actKey);
                        }

                    }
                    this.floatingMarkers.addTo(this.map);
                    this.map.fitBounds(this.floatingMarkers.getBounds());

                });
                DG.then(() => {
                    this.markers = null;
                    this.markers = DG.featureGroup();

                    for (let geoKey in this.m.geo) {
                        if(this.m.geo.hasOwnProperty(geoKey)) {
                            let masterDiv = '';
                            let tipsAct = [];
                            let tipsLid = [];
                            let actDocs = '';
                            // let actsArr = [];
                            for (let act of this.m.geo[geoKey]) {

                                // actsArr.push(act);
                                let implement = this.m.byAct[act.id];
                                tipsAct.push(act.act_nr);
                                tipsLid.push(this.m.lids[act.parent].contract);
                                actDocs = this.checkDocs(act);

                                for (let master in implement) {
                                    if(implement.hasOwnProperty(master)){
                                        let implementState = this.checkState(master.start_date, master.end_date, act.finished);

                                        masterDiv += `<div class="inner ` + implementState + `"   style="background: ` + this.m.masters[implement[master].master].color + `;color:`+this.m.masters[implement[master].master].text_color+` ">` + this.m.masters[implement[master].master].id + `</div>`
                                    } else {
                                        console.log(`implement.hasOwnProperty(master) ${master}`);
                                    }

                                }
                            }

                            let divIcon = DG.divIcon({className: 'divIcon-master', html: masterDiv + actDocs});

                            let coordinates = geoKey.split(':');
                            this.popups[geoKey] = DG.popup().setLatLng(coordinates).setContent('');

                            this.markers[geoKey] = DG.marker(coordinates, {icon: divIcon})
                                .addTo(this.markers)
                                .bindLabel('<b style="color: white">Лид:' + tipsLid.join(',') + '; Акт: ' + tipsAct.join(',') + '</b>');

                            this.markers[geoKey].on('click', (a) => {
                                this.geoKey = geoKey;
                                this.actType = 'master';
                                this.actModal = true;
                                // this.openAct(geoKey,'master')
                            })
                        } else {
                            console.log(`m.geo.hasOwnProperty(geoKey) ${geoKey}`);
                        }

                    }

                    this.markers.addTo(this.map);
                    this.map.fitBounds(this.markers.getBounds());

                    this.initRoutes();


                })

            },

            openAct(key, type) {
                this.actsInMap = type === 'floating' ? this.floating[key] : this.m.geo[key];
                this.actType = type;
                this.actModal = true;
            },
            setDate(){
               location.search = `date=${this.logisticDate}`;
            },
            async initRoutes() {

                let coordinates = [];

                for (let masterID in this.m.byMasters) {
                    let tlat = '55.811432';
                    let tlon = '37.772325';
                    for (let implement of this.m.byMasters[masterID]) {

                        Axios.post('/dadata/async', {
                            func: 'osmRoutes',
                            flat: this.m.acts[implement.parent].lat,
                            flon: this.m.acts[implement.parent].lon,
                            tlat: tlat,
                            tlon: tlon,
                            // region: 'Москва',
                        })
                            .then(response => {
                                let revert = [];
                                for (let c in response.data.coordinates) {
                                    revert[c] = [response.data.coordinates[c][1], response.data.coordinates[c][0]]
                                }
                                this.polyline[this.m.acts[implement.parent].lat + ':' + this.m.acts[implement.parent].lon] = DG.polyline(revert, {
                                    color: this.m.masters[masterID].color,
                                    className: 'route_' + masterID,
                                    weight: 3
                                }).addTo(this.map)
                            })
                            .catch(er => {
                                console.log(er);
                            });
                        tlat = this.m.acts[implement.parent].lat;
                        tlon = this.m.acts[implement.parent].lon;
                    }
                }
            },
            openPopup(act) {
                let a = this.acts[act]
                DG.then(() => {
                    this.popups[a.lat + ':' + a.lon].addTo(this.map);
                })
            },

            async stAlone() {
                await this.LOGISTIC_DATE(this.init_date);
                await this.fetchSchedule(this.init_date);
                await this.fetchHelpers();
                await this.OPEN_MAP(true);
                this.markers = {};
                this.floatingMarkers = {};
                this.map = null;

                await DG.then(() => {
                    this.map = DG.map('map', {
                        'center': [55.748696, 37.622275],
                        'zoom': 12
                    });
                });
                await this.initMarkers();
            },

        },
        created() {
            bus.$on('open-popup', (act) => {
                let a = this.m.acts[act];
                DG.then(() => {
                    this.popups[a.lat + ':' + a.lon].addTo(this.map)
                })
            });
            bus.$on('init-map', async (implement) => {
                await this.LOGISTIC_DATE(implement.date);
                await this.$store.dispatch('activeImplement', implement);
                await this.fetchSchedule(implement.date);
                // this.openMap = true;
                await this.OPEN_MAP(true);
                this.map = null;
                await DG.then(() => {
                    this.map = DG.map('map', {
                        'center': [55.748696, 37.622275],
                        'zoom': 12
                    });
                });
                await this.initMarkers();
            });
            bus.$on('reinit-layers', () => {
                this.initMarkers();
                this.initRoutes();
            });
            bus.$on('success', function(a){ this.$Notice({type:'success',  title: a.table+': '+a.mess,  content:''}) });
            bus.$on('error', function(a){ this.$Notice({type:'error',  title: a.table+': '+a.mess,  content: a.state}) });
            if (this.case === 'standAlone') {
                this.stAlone();
            }
        },
        mounted() {


        }
    }
</script>

<style lang="scss">
    .stroke {
        stroke-width: 8;
    }
    .h-notify .h-notify-body {
        z-index: 2000;
    }
    .h-dropdown {
        z-index: 2200;
    }

    .h-notice-container {
        z-index: 4000;
    }
    .h-notify-close.h-icon-close {
        right: 45px;
        top: 24px;
        color: white;
/*        width: 30px;
        height: 30px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;*/
    }
    input[type="text"] {
        width: 100%;
    }
    .actModal {
        z-index: 3000;
    }
</style>
