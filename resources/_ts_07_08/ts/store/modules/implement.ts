import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";
import {IImplement} from "../../types/implement";


@Module({
    namespaced: true
})
export default class Implement extends VuexModule {
    public table: IImplement[] = []
    public row: IImplement = {}
    public search: IImplement[] = []
    public contract: string = ''

    // search
    @Mutation setSearch(payload: IImplement[]){this.search = payload}
    @Action({commit:'setSearch'}) public async fetchSearch(payload: string){
        let response = await axios.post('/vuex/implement', {func: 'search', data: payload})
        return  response.data
    }
    @Action({commit:'setSearch'}) public async fetchFieldSearch(payload: object){
        let response = await axios.post('/vuex/implement', {func: 'searchField', data: payload})
        return  response.data
    }

// CONTRACT NR
    @Mutation setContract(payload: string) {
        this.contract = payload
    }
    @Action({commit: 'setContract'}) public async fetchContract(payload: boolean) {
        if(payload) {
            let response = await axios.post('/vuex/implement', {func: 'contract'})
            return response.data
        } else  {
            return ''
        }
    }
// CREATE ROW
    @Mutation setRow(payload: IImplement){this.row = payload}
    @Action({commit: 'setRow'}) public async createRow(payload: string) {
        let response = await axios.post('/vuex/implement',{func: '8888', data: payload})
        return response.data
    }
    /** @desc standard */
    @Action public async eCreateRow(payload: string) {
        await axios.post('/vuex/implement',{func: 'eCreateRow', data: payload})
    }


}
