<template>
    <table class="statistic-act-table" style="margin: 0 15px;">
        <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="statistic-act-table_head">
            <td colspan="2"><span>Предмет работ:</span></td>
            <td><span> Площадь</span></td>
            <td><span>Пл. факт</span></td>
            <td colspan="2"><span>Единица площади</span></td>
            <td colspan="7"><span>Методы</span></td>
            <td><span>Нал.</span></td>
            <td><span>Безнал</span></td>
        </tr>

        <tr class="statistic-act-table_body" v-for="volume in volume_search" :class="volume.parent === row_CrmStatistic.act_id ? 'current_volume' : ''">
            <td colspan="2">
                <Select v-if="h.pests_asc" v-model="volume.pest" :datas="h.pests_asc" keyName="id" titleName="name"
                        @change="changePests(volume)" disabled/>
            </td>
            <td>
                <input type="text" v-model="volume.square" disabled
                       @focusout="eUpdateRowVolume({id:volume.id, square:volume.square})"><!--square-->
            </td>
            <td>
                <input type="text" v-model="volume.square_fact" disabled
                       @focusout="eUpdateRowVolume({id:volume.id, square_fact:volume.square_fact})"><!--square_fact-->
            </td>
            <td colspan="2">
                <Select v-if="h.square" v-model="volume.entity" :datas="h.square" keyName="id" titleName="name"  disabled
                        @change="eUpdateRowVolume({id:volume.id, entity:volume.entity})"/><!--entity-->
            </td>
            <td colspan="7">
                <Category :option="params(volume.pest)" type="key" :multiple="true" v-model="volume.method" disabled
                          @change="eUpdateRowVolume({id:volume.id, method:volume.method.join()})"/>
            </td>
            <td>
                <input type="text" v-model="volume.price_fact"
                       @focusout="eUpdateRowVolume({id:volume.id, price_fact:volume.price_fact})"><!--price_fact-->
            </td>
            <td>
                <input type="text" v-model="volume.price_standard"
                       @focusout="eUpdateRowVolume({id:volume.id, price_standard:volume.price_standard})"><!--price_standard-->
            </td>

        </tr>

        </tbody>
    </table>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import {IVolume} from "../../../types/volume";
    import {IHelper, IMethods, IPests} from "../../../types/Global";
    import {ICrmStatistic} from "../../../types/crm.statistic";
    import index from "../../../store";
    const Volume = namespace('Volume')
    const CrmStatistic = namespace('CrmStatistic')

    @Component
    export default class CrmStatisticVolumesCheck extends Vue {
        @Prop() h!: IHelper
/*CrmStatistic*/
        @CrmStatistic.State('row') public row_CrmStatistic!: ICrmStatistic
/*Volume*/
        @Volume.State('search') public volume_search!: IVolume[]
        @Volume.State('table') public table_Volume!: IVolume[]
        @Volume.State('row') public row_Volume!: IVolume
        @Volume.Action('eCreateRow') public eCreateRowVolume!: (payload: any) => void
        @Volume.Action('eUpdateRow') public eUpdateRowVolume!: (payload: IVolume) => void
        @Volume.Action('eDeleteRow') public eDeleteRowVolume!: (payload: number) => void

        public messages(message: string, type: string){
            // @ts-ignore
            this.$Message[type](message)
        }


        createVolume(){
            this.eCreateRowVolume({
                statistic_id: this.row_CrmStatistic.id,
            })
        }

        public params(pest_id: number){
            let methods = this.h.pests_asc.find(o => o.id === pest_id)?.methods

            return {
                title: 'Выбор методов',
                keyName: 'id',
                titleName: 'name',
                dataMode: 'tree',
                // @ts-ignore
                datas: !pest_id ? [] :  methods.map((k) => {
                    return this.h.methods_asc?.find((o) => {
                        return o.id === Number(k)
                    })
                })
            }
        }
        async changePests(vol: IVolume){
           await this.eUpdateRowVolume({
               id: vol.id,
               method:null,
               pest: vol.pest
           })
        }

    }
</script>
