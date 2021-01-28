import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";
import {ILid} from "../../types/lid";
import {IKeyValue} from "../../types/Global";
import {ICrmStatistic} from "../../types/crm.statistic";
import * as Types from "../../types/crm.statistic";
import moment from "moment";




@Module({
    namespaced: true
})
export default class Lid extends VuexModule {
    public table: ILid[] = []
    public row: ILid = {}
    public search: ILid[] = []
    public contract: string = ''

    public period: Types.IPeriod = {
        start: moment().subtract(20, "days").format('YYYY-MM-DD'),
        end: moment().endOf('month').add(2, 'days').format('YYYY-MM-DD'),
        type: 'customize'
    }
    get getPeriod(){
        return this.period
    }


    public page: number = 1
    public total: number = 0
    public last_page: number = 100

    public paymentType: { id: number, name: string }[] = [
        {id: 1, name: 'Наличная'},
        {id: 2, name: 'Безналичная'},
        {id: 3, name: 'Смешанная'}
    ]

    get getPaymentType(){
        return this.paymentType
    }
    public paymentSystem: { id: number, name: string }[] = [
        {id: 1, name: 'Фиксированная'},
        {id: 2, name: 'Накопительная'},
    ]

    // SEARCH
    @Mutation setSearch(payload: ILid[]){this.search = payload}
    @Action({commit:'setSearch'}) public async fetchSearch(payload: string){
        let response = await axios.post('/vuex/lid', {func: 'search', data: payload})
        return  response.data
    }
    @Action({commit:'setSearch'}) public async fetchFieldSearch(payload: object){
        let response = await axios.post('/vuex/lid', {func: 'searchField', data: payload})
        return  response.data
    }

// CONTRACT NR
    @Mutation setContract(payload: string) {
        this.contract = payload
    }
    @Action({commit: 'setContract'}) public async fetchContract(payload: boolean) {
        if(payload) {
            let response = await axios.post('/vuex/lid', {func: 'contract'})
            return response.data
        } else  {
            return ''
        }
    }
// GET & CREATE ROW
    @Mutation setRow(payload: ILid){this.row = payload}
    @Action({commit: 'setRow'}) public async createRow(payload: string) {
        let response = await axios.post('/vuex/lid',{func: '8888', data: payload})
        return response.data
    }
    @Action({commit: 'setRow'}) public async getRow(data: ILid) {
        let response = await axios.post('/vuex/lid',{func: 'getRow', data: data})
        return response.data
    }


// UPDATE_ROW
    @Mutation setUpdateRow(payload: ILid){this.row = payload}
    /** @desc fetchUpdateRow */
    @Action({commit: 'setUpdateRow'}) public async fetchUpdateRow(data: ILid) {
        let response = await axios.post('/vuex/lid',{func: 'fetchUpdateRow', data: data})
        return response.data
    }




    ///////////////////////////////
    /** @desc eCreateRow */
    @Action public async eCreateRow(payload: string) {
        await axios.post('/vuex/lid',{func: 'eCreateRow', data: payload})
    }

    @Action eUpdateRow(payload: ICrmStatistic) {
        axios.post('/vuex/lid', {func: 'eUpdateRow', data: payload})
    }
    @Action private_eUpdateRow(payload: ICrmStatistic) {
        axios.post('/vuex/lid', {func: 'private_eUpdateRow', data: payload})
    }

    @Action  async eImprove(payload: string) {
        let response = await axios.post('/vuex/8888', {func: '8888', data: payload})
        return response.data
    }

    public queryRow: ILid[] = []
    @Mutation setTable(payload:{data:ILid[],current_page:number,total: number,last_page:number}){
        this.table = payload.data
        this.page = payload.current_page
        this.total = payload.total
        this.last_page = payload.last_page
    }
    @Mutation setConcatTable(payload: {data:ILid[],current_page:number,total: number,last_page:number}) {
        this.table = this.table.concat(payload.data)
        this.page = payload.current_page
        this.total = payload.total
        this.last_page = payload.last_page
    }

    @Action({commit:'setTable'})
    async initTable(payload:string = '') {
        let response = await axios.get('/qb/lid' + payload);
        return response.data
    }
    @Action({commit:'setConcatTable'})
    async fetchConcatTable(payload:string = '') {
        let response = await axios.get('/qb/lid' + payload);
        return response.data
    }




}
