import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";
import {ICrmStatistic} from "../../types/crm.statistic";
import moment from "moment";
import {IAct} from "../../types/act";
import {IFinance} from "../../types/finance";
import {IImplement} from "../../types/implement";
import {IVolume} from "../../types/volume";
import {Getter} from "vuex-class";
import {ILid} from "../../types/lid";


@Module({
    namespaced: true
})
export default class Funnel extends VuexModule {


    public funnelData: any = []
    public funnelFinances: any = []


/*    public period = {
        start: moment().startOf('month').format('YYYY-MM-DD'),
        end: moment().endOf('month').format('YYYY-MM-DD'),
        type: 'month'
    }*/



    public acts: IAct[] = []
    public actsById: IAct[] = []
    public finances: IFinance[] = []
    public implements: IImplement[] = []
    public volumes: IVolume[] = []
    public statistic: ICrmStatistic[] = []
    public lidWithActs: ILid[] = []


    //funnelData
    @Mutation setFunnelData(payload: any) {
        this.funnelData = payload
    }
    @Action({commit: 'setFunnelData'}) public async fetchFunnelData(date:any) {
        let response = await axios.post('/vuex/funnel',{
            real: true,
            period: date
        })
        return await response.data
    }

    //FunnelFinances
    @Mutation setFunnelFinances(payload: any) {
        this.funnelFinances = payload
    }
    @Action({commit: 'setFunnelFinances'}) public async fetchFunnelFinances(date:any) {
        let response = await axios.post('/vuex/statistic-finance',{
            real: true,
            period: date
        })
        return await response.data
    }






    // Acts
    @Mutation setActs(payload: IAct[]) {
        this.acts = payload
    }
    @Action({commit: 'setActs'}) public async fetchActs() {
        let response = await axios.post('/vuex/funnel', {func: 'getActs'})
        return await response.data
    }

    @Mutation setActsById(payload: IAct[]) {
        this.actsById = payload;
        // Object.assign(this.actsById,payload)
    }
    @Action({commit: 'setActsById'}) public async fetchActsById() {
        let response = await axios.post('/vuex/funnel', {func: 'getActsById'})
        return await response.data
    }

    // Finances
    @Mutation setFinances(payload: IFinance[]) {
        this.finances = payload
    }
    @Action({commit: 'setFinances'}) public async fetchFinances() {
        let response = await axios.post('/vuex/funnel', {func: 'getFinances'})
        return await response.data
    }

    // Implements
    @Mutation setImplements(payload: IImplement[]) {
        this.implements = payload
    }
    @Action({commit: 'setImplements'}) public async fetchImplements() {
        let response = await axios.post('/vuex/funnel', {func: 'getImplements'})
        return await response.data
    }

    // Volumes
    @Mutation setVolumes(payload: IVolume[]) {
        this.volumes = payload
    }
    @Action({commit: 'setVolumes'}) public async fetchVolumes() {
        let response = await axios.post('/vuex/funnel', {func: 'getVolumes'})
        return await response.data
    }

    // Statistics
    @Mutation setStatistics(payload: ICrmStatistic[]) {
        this.statistic = payload
    }
    @Action({commit: 'setStatistics'}) public async fetchStatistics() {
        let response = await axios.post('/vuex/funnel', {func: 'getStatistics'})
        return await response.data
    }

    // LidWithActs
    @Mutation setLidWithActs(payload: ILid[]) {
        this.lidWithActs = payload
    }
    @Action({commit: 'setLidWithActs'}) public async fetchLidWithActs() {
        let response = await axios.post('/vuex/funnel', {func: 'getLidWithActs'})
        return await response.data
    }





}
