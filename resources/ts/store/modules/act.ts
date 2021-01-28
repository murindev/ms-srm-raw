import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";
import {IAct, IPaid, IPropertyType} from "../../types/act";
import {IFieldValue} from "../../types/Global";
import {ICardPayment} from "../../types/crm.statistic";


@Module({
    namespaced: true
})
export default class Act extends VuexModule {
    public table: IAct[] = []
    public row: IAct = {}
    public search: IAct[] = []
    public searchRow: IAct = {}
    public contract: string = ''
    public propertyType: IPropertyType[] = [
        {id:1,name:'квартира'},
        {id:2,name:'дом'},
        {id:3,name:'офис'},
        {id:4,name:'клиника'},
        {id:5,name:'больница'},
        {id:6,name:'дом престарелых'},
        {id:7,name:'пр-во пищевое'},
        {id:8,name:'пр-во непищевое'},
        {id:9,name:'складские пом-я'},
        {id:10,name:'магазин пищевой'},
        {id:11,name:'магазин непищевой'},
        {id:12,name:'открытая территория'},
        {id:13,name:'школа'},
        {id:14,name:'детский сад'},
        {id:15,name:'фитнес клуб'},
        {id:16,name:'торговый центр'},
        {id:17,name:'бизнес центр'},
        {id:100,name:'другое'}
    ]
    public paid: IPaid[] = [
        {id: 1, name: 'Не оплачено'},
        {id: 2, name: 'Оплачено'}
    ]
    public cardPayment: ICardPayment[] = [
        {value: 0, name: 'Нет'},
        {value: 1, name: 'Да'},
    ]


    // search
    @Mutation setSearch(payload: IAct[]){this.search = payload}
    @Action({commit:'setSearch'}) public async fetchSearch(payload: string){
        let response = await axios.post('/vuex/act', {func: 'search', data: payload})
        return  response.data
    }
    @Action({commit:'setSearch'}) public async fetchFieldSearch(payload: object){
        let response = await axios.post('/vuex/act', {func: 'searchField', data: payload})
        return  response.data
    }

    @Mutation setSearchRow(payload: IAct){ this.searchRow = payload}
    @Action({commit: 'setSearchRow'}) public async fetchSearchRow(data: IFieldValue) {
        let response = await axios.post('/vuex/act',{func: 'fetchSearchRow', data: data})
        return response.data
    }

// CONTRACT NR
    @Mutation setContract(payload: string) {
        this.contract = payload
    }
    @Action({commit: 'setContract'}) public async fetchContract(payload: boolean) {
        if(payload) {
            let response = await axios.post('/vuex/act', {func: 'contract'})
            return response.data
        } else  {
            return ''
        }
    }
// CREATE ROW
    @Mutation setRow(payload: IAct){this.row = payload}
    @Action({commit: 'setRow'}) public async createRow(payload: string) {
        let response = await axios.post('/vuex/act',{func: '8888', data: payload})
        return response.data
    }
    // UPDATE ROW
    @Action({commit: 'setRow'}) public async updateRow(data: IAct) {
        let response = await axios.post('/vuex/act',{func: 'updateRow', data: data})
        return response.data
    }

    /** @desc standard */
    @Action public async eCreateRow(payload: string) {
        await axios.post('/vuex/act',{func: 'eCreateRow', data: payload})
    }
    @Action public async eCreateRowWithVolumes(payload: string) {
        await axios.post('/vuex/act',{func: 'eCreateRowWithVolumes', data: payload})
    }
    @Action public async eUpdateRow(payload: string) {
        await axios.post('/vuex/act',{func: 'eUpdateRow', data: payload})
        // return response.data
    }


}
