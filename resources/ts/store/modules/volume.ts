import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios"
import {IVolume} from "../../types/volume"
import {IFieldValue} from "../../types/Global";


@Module({
    namespaced: true
})
export default class Volume extends VuexModule {
    public table: IVolume[] = []
    public row: IVolume = {}


    public searchRow: IVolume = {}
    public contract: string = ''

    get implements(){
        let cash = 0, cashless = 0, total = 0
        this.search.forEach(o => {
            cash += Number(o.price_fact)
            cashless += Number(o.price_standard)
        })
        return {cash, cashless, total: cash + cashless}
    }

    // search
    public search: IVolume[] = []
    get searchArray(){return this.search}
    @Mutation setSearch(payload: IVolume[]){this.search = payload}
    @Action({commit:'setSearch'}) public async fetchSearch(payload: string){
        let response = await axios.post('/vuex/volume', {func: 'search', data: payload})
        return  response.data
    }
    @Action({commit:'setSearch'})
    public async searchField(data: IFieldValue){
        let response = await axios.post('/vuex/volume', {func: 'searchField', data: data})
        return  response.data
    }

// CONTRACT NR
    @Mutation setContract(payload: string) {
        this.contract = payload
    }
    @Action({commit: 'setContract'}) public async fetchContract(payload: boolean) {
        if(payload) {
            let response = await axios.post('/vuex/volume', {func: 'contract'})
            return response.data
        } else  {
            return ''
        }
    }
// CREATE ROW
    @Mutation setRow(payload: IVolume){this.row = payload}
    @Action({commit: 'setRow'}) public async createRow(payload: string) {
        let response = await axios.post('/vuex/volume',{func: '8888', data: payload})
        return response.data
    }
    /** @desc standard */
    @Action public async eCreateRows(payload: string) {
        await axios.post('/vuex/volume',{func: 'eCreateRows', data: payload})
    }
    @Action public async eCreateRow(payload: string) {
        await axios.post('/vuex/volume',{func: 'eCreateRow', data: payload})
    }
    @Action public async eUpdateRows(payload: string) {
        await axios.post('/vuex/volume',{func: 'eUpdateRows', data: payload})
    }
    @Action public async eUpdateRow(payload: string) {
        await axios.post('/vuex/volume',{func: 'eUpdateRow', data: payload})
    }
    @Action public async eDeleteRow(payload: string) {
        await axios.post('/vuex/volume',{func: 'eDeleteRow', data: payload})
    }


}
