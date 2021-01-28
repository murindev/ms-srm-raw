<template>
    <div>
        <AutoComplete
                style="width: 90%"
                :option="option"
                type="object"
                v-model="model"
                :placeholder="placeholder"
                @change="onChange"
        ></AutoComplete>
    </div>
</template>

<script lang="ts">
    import { Component,Prop, Watch, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    const ex = namespace('Example')
    import axios from "axios";
    import Axios from "axios";
    import * as H from "../../types/helper";
    const Helper = namespace('Helper')
    const  loadData = function(filter: string, callback: any){
        // @ts-ignore
        axios.post('/dadata/async',{ func: this.orgId, query:filter })
            .then(response => {
                let list = [];
                for(let s in response.data.suggestions){
                    list.push({
                        // key: s,
                        title: response.data.suggestions[s].value,
                        data: response.data.suggestions[s].data
                    });
                }
                callback(list);
            })
    };
    @Component
    export default class DadataInput extends Vue { // organization, inn, bank, address
        @Prop() entity!: string
        @Prop() placeholder!: string
        @Helper.State public h!: H.IHelper
        @Helper.Action public fetchHelpers!: () => void

        @Watch('entity',{immediate: true, deep: true})
        onEntityChange(val:string, oldVal: string) {
            this.option.orgId = val
        }


        public offices = {
            moscow: {
                 lat: '55.810896',
                 lon: '37.768956'
            }
        }

        public model:string = ''
        public option: any = {
            orgId: this.entity,
            loadData,
            minWord: 1
        }
        public async onChange(data: any, trigger: any) {
console.log(data);
            if (data.title === null) {
                return  false
            }
            let lat,lon;
            if( this.entity === 'organization' || this.entity === 'bank' ){
                lat = data.value.data.address.data.geo_lat
                lon = data.value.data.address.data.geo_lon
            } else if (this.entity === 'address') {
                lat = data.value.data.geo_lat
                lon = data.value.data.geo_lon
            } else {
                lat = data.address.data.geo_lat
                lon = data.address.data.geo_lon
            }

            if(lat && lon) {
                this.$emit('dadata',{
                    title: data.title,
                    data: await data.value.data,
                    value: await data.value.value,
                    destination: await this.getDistance( lat,  lon)
                })
            }

        }

        public async getDistance(lat: any,lon: any) {
            try {
                let response = await Axios.post('/dadata/async', {
                    func: 'osmMoscow',
                    tlat: '55.810896',
                    tlon: '37.768956',
                    flat: lat,
                    flon: lon
                });
                if ('distance' in response.data.properties) {
                    // @ts-ignore
                    this.$Notice({
                        type: 'success',
                        title: response.data.properties.distance + ' км от офиса',
                        content: ''
                    });
                    return response.data.properties.distance
                } else {
                    // @ts-ignore
                    this.$Notice({
                        type: 'warn',
                        title: 'Не достаточно данных для определения расстояния',
                        content: ''
                    });
                    return null
                }
            } catch (error) {console.log(error);}
        }

        validateTable(){

        }

        created() {

        }
        mounted(){
            this.fetchHelpers()
        }
    }
</script>
