<template>
    <div class="dayLine">
        <svg height="20" width="510">
            <template v-if="newImplement.status === 'highlight' && newImplement.old_master === master">
                <polyline
                    class="line highlight"
                    :points="makePolyline(newImplement.old_start,newImplement.old_end)"
                />
            </template>
            <g>

                <template v-for="k in day">





                    <rect width="20" height="20" :fill="k%2 ? 'rgba(0,0,0,.03)':'rgba(0,0,0,.01)'" :x="Number(k)*21" y="0" />
                    <text class="tx" :x="k*21+3" y="14"  fill="rgba(0,0,0,.5)" font-size="12" >{{k}}</text>
                    <rect class="q" :style="setActiveRect(k+':00:00_'+master)" width="10" height="10" :x="Number(k)*21" y="0" :id="k+':00:00_'+master" @click="setMasterAction(k+':00:00_'+master)" />
                    <rect class="q" :style="setActiveRect(k+':15:00_'+master)" width="10" height="10" :x="Number(k)*21" y="10" :id="k+':15:00_'+master"  @click="setMasterAction(k+':15:00_'+master)"/>
                    <rect class="q" :style="setActiveRect(k+':30:00_'+master)" width="10" height="10" :x="Number(k)*21+10" y="10" :id="k+':30:00_'+master"  @click="setMasterAction(k+':30:00_'+master)"/>
                    <rect class="q" :style="setActiveRect(k+':45:00_'+master)" width="10" height="10" :x="Number(k)*21+10" y="0" :id="k+':45:00_'+master"  @click="setMasterAction(k+':45:00_'+master)"/>


                    <!--:class="{active: activeRect == k+':15:00_'+master}"-->
                    <template v-if="schedule[master]">
                        <template v-for="obj in schedule[master]">
                            <template v-if="newImplement.status === 'highlight'">

                                    <polyline
                                        v-if="newImplement.id !== obj.id"
                                        class="line"
                                        :points="makePolyline(obj.start_date,obj.end_date)"
                                        @click="openPopup(obj.parent)"
                                        @dblclick="changeMaster(obj)"
                                    />

                            </template>


                            <template v-else>
                                <polyline
                                    :class="implementColor(obj.user)"

                                    :points="makePolyline(obj.start_date,obj.end_date)"
                                    @click="openPopup(obj.parent)"
                                    @dblclick="changeMaster(obj)"
                                />
                            </template>

                        </template>
                    </template>

                    <template v-if="newImplement && newImplement.start_date && newImplement.end_date && Number(newImplement.master) === Number(master)">


                        <polyline
                            class="line preview"
                            :points="makePolyline(newImplement && newImplement.start_date,newImplement.end_date)"
                            @dblclick="removeMasterAction"

                        />
                    </template>


                </template>
            </g>
        </svg>
    </div>

</template>

<script>
    import {bus} from "../bus";
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "ImplementDay",
        props: ['master'],
        data() {
            return {
                tempImplement: false,
                oClock: {
                    "00:00:00": {"x": 5, "y": 5},
                    "00:15:00": {"x": 5, "y": 15},
                    "00:30:00": {"x": 15, "y": 15},
                    "00:45:00": {"x": 15, "y": 5},
                    "01:00:00": {"x": 26, "y": 5},
                    "01:15:00": {"x": 26, "y": 15},
                    "01:30:00": {"x": 36, "y": 15},
                    "01:45:00": {"x": 36, "y": 5},
                    "02:00:00": {"x": 47, "y": 5},
                    "02:15:00": {"x": 47, "y": 15},
                    "02:30:00": {"x": 57, "y": 15},
                    "02:45:00": {"x": 57, "y": 5},
                    "03:00:00": {"x": 68, "y": 5},
                    "03:15:00": {"x": 68, "y": 15},
                    "03:30:00": {"x": 78, "y": 15},
                    "03:45:00": {"x": 78, "y": 5},
                    "04:00:00": {"x": 89, "y": 5},
                    "04:15:00": {"x": 89, "y": 15},
                    "04:30:00": {"x": 99, "y": 15},
                    "04:45:00": {"x": 99, "y": 5},
                    "05:00:00": {"x": 110, "y": 5},
                    "05:15:00": {"x": 110, "y": 15},
                    "05:30:00": {"x": 120, "y": 15},
                    "05:45:00": {"x": 120, "y": 5},
                    "06:00:00": {"x": 131, "y": 5},
                    "06:15:00": {"x": 131, "y": 15},
                    "06:30:00": {"x": 141, "y": 15},
                    "06:45:00": {"x": 141, "y": 5},
                    "07:00:00": {"x": 152, "y": 5},
                    "07:15:00": {"x": 152, "y": 15},
                    "07:30:00": {"x": 162, "y": 15},
                    "07:45:00": {"x": 162, "y": 5},
                    "08:00:00": {"x": 173, "y": 5},
                    "08:15:00": {"x": 173, "y": 15},
                    "08:30:00": {"x": 183, "y": 15},
                    "08:45:00": {"x": 183, "y": 5},
                    "09:00:00": {"x": 194, "y": 5},
                    "09:15:00": {"x": 194, "y": 15},
                    "09:30:00": {"x": 204, "y": 15},
                    "09:45:00": {"x": 204, "y": 5},
                    "10:00:00": {"x": 215, "y": 5},
                    "10:15:00": {"x": 215, "y": 15},
                    "10:30:00": {"x": 225, "y": 15},
                    "10:45:00": {"x": 225, "y": 5},
                    "11:00:00": {"x": 236, "y": 5},
                    "11:15:00": {"x": 236, "y": 15},
                    "11:30:00": {"x": 246, "y": 15},
                    "11:45:00": {"x": 246, "y": 5},
                    "12:00:00": {"x": 257, "y": 5},
                    "12:15:00": {"x": 257, "y": 15},
                    "12:30:00": {"x": 267, "y": 15},
                    "12:45:00": {"x": 267, "y": 5},
                    "13:00:00": {"x": 278, "y": 5},
                    "13:15:00": {"x": 278, "y": 15},
                    "13:30:00": {"x": 288, "y": 15},
                    "13:45:00": {"x": 288, "y": 5},
                    "14:00:00": {"x": 299, "y": 5},
                    "14:15:00": {"x": 299, "y": 15},
                    "14:30:00": {"x": 309, "y": 15},
                    "14:45:00": {"x": 309, "y": 5},
                    "15:00:00": {"x": 320, "y": 5},
                    "15:15:00": {"x": 320, "y": 15},
                    "15:30:00": {"x": 330, "y": 15},
                    "15:45:00": {"x": 330, "y": 5},
                    "16:00:00": {"x": 341, "y": 5},
                    "16:15:00": {"x": 341, "y": 15},
                    "16:30:00": {"x": 351, "y": 15},
                    "16:45:00": {"x": 351, "y": 5},
                    "17:00:00": {"x": 362, "y": 5},
                    "17:15:00": {"x": 362, "y": 15},
                    "17:30:00": {"x": 372, "y": 15},
                    "17:45:00": {"x": 372, "y": 5},
                    "18:00:00": {"x": 383, "y": 5},
                    "18:15:00": {"x": 383, "y": 15},
                    "18:30:00": {"x": 393, "y": 15},
                    "18:45:00": {"x": 393, "y": 5},
                    "19:00:00": {"x": 404, "y": 5},
                    "19:15:00": {"x": 404, "y": 15},
                    "19:30:00": {"x": 414, "y": 15},
                    "19:45:00": {"x": 414, "y": 5},
                    "20:00:00": {"x": 425, "y": 5},
                    "20:15:00": {"x": 425, "y": 15},
                    "20:30:00": {"x": 435, "y": 15},
                    "20:45:00": {"x": 435, "y": 5},
                    "21:00:00": {"x": 446, "y": 5},
                    "21:15:00": {"x": 446, "y": 15},
                    "21:30:00": {"x": 456, "y": 15},
                    "21:45:00": {"x": 456, "y": 5},
                    "22:00:00": {"x": 467, "y": 5},
                    "22:15:00": {"x": 467, "y": 15},
                    "22:30:00": {"x": 477, "y": 15},
                    "22:45:00": {"x": 477, "y": 5},
                    "23:00:00": {"x": 488, "y": 5},
                    "23:15:00": {"x": 488, "y": 15},
                    "23:30:00": {"x": 498, "y": 15},
                    "23:45:00": {"x": 498, "y": 5}
                },
                day:['00','01','02','03','04','05','06','07','08','09',10,11,12,13,14,15,16,17,18,19,20,21,22,23],
                commonPoints:[],
                currentPoints:[],
            }
        },
        computed: {
            ...mapGetters(['DGMap','logisticDate','points','schedule','markers','popups','polylines','mapActs','mapLids','masters','h','newImplement']),
        },
        methods:{
            ...mapActions(['setMasterAction','removeMasterAction','openPopup']),

            setActiveRect(id){
               return  this.newImplement.activeRect === id ? 'fill:#3C8DBC':''
            },

            implementColor(userID){
                let role = this.h.roles[this.h.users[userID].role_id],cLass;
                switch (role) {
                    case undefined:
                        cLass = 'line safe';
                        break;
                    case 'manager':
                        cLass = 'line ';
                        break;
                    default:
                        cLass = 'line safe'
                }
                return cLass;
            },


            clock(){
                let cnt = 5;
                let Obj = {};
                for(let i = 0, len = 24; i < len; i++) {
                    let h = i < 10 ? '0'+ i : i;
                    Obj[h+':00:00'] = {x:cnt ,y:5 };
                    Obj[h+':15:00'] = {x:cnt ,y:15 };
                    cnt = cnt + 10;
                    Obj[h+':30:00'] = {x:cnt ,y:15 };
                    Obj[h+':45:00'] = {x:cnt ,y:5 };
                    cnt = cnt + 11;
                }

                return Obj;
            },
            nextKey(i){
                let nextArr = i.split(':');
                let res = '';
                switch (nextArr[1]) {
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
                        res = d + ':'+'00:00';
                }
                return res;
            },
            prevKey(i){
                let prevArr = i.split(':');
                let res = '';
                switch (prevArr[1]) {
                    case '00':
                        let d = Number(prevArr[0]) - 1;
                        let c = d < 10 ? '0'+ d : d;
                        res = c + ':'+'45:00';
                        break;
                    case '15':
                        res = prevArr[0]+ ':'+'00:00';
                        break;
                    case '30':
                        res = prevArr[0]+ ':'+'15:00';
                        break;
                    case '45':
                        res = prevArr[0] + ':'+'30:00';
                }
                return res;
            },
            makePolyline(start,end){
                if(start === undefined || end === undefined) { return  false}
                let line = '';
                let s = start.slice(11);
                let f = end.slice(11);
                for(let i in this.oClock){
                    if(i >= s && i <= f) {
                        if(i === f){
                            let prev = this.prevKey(i);
                            line += this.oClock[prev].x + ',' + this.oClock[prev].y + ' ' + this.oClock[prev].x + ',' + this.oClock[prev].y + ' ' ;
                        } else {
                            line += this.oClock[i].x + ',' + this.oClock[i].y + ' ' + this.oClock[i].x + ',' + this.oClock[i].y + ' ' ;
                        }
                    }
                }
                return line;
            },
/*            openPopup(act){
                bus.$emit('open-popup',act);

            },
            changeMaster(implement){
                 this.$store.dispatch('activeImplement', implement);
            }*/
        },
        created(){


        },
        mounted() {}
    }
</script>

