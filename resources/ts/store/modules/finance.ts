import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";
import * as Types from "../../types/crm.statistic";
import {IFinance, IPaymentForm, IPaymentType} from "../../types/finance";


@Module({
    namespaced: true
})
export default class Finance extends VuexModule {
    public type: IPaymentForm[] = [
        {id:1, name: 'Дебет', short: 'Деб', shortest: 'Д'},
        {id:2, name: 'Кредит', short: 'Кр', shortest: 'К'},
    ]
    public form: IPaymentType[] = [
        {id:1, name: 'Наличные', short: 'Нал', shortest: 'H'},
        {id:2, name: 'Безналичные', short: 'Безнал', shortest: 'БН'},
    ]
    public table: IFinance[] = []
    public row: IFinance = {}
    public searchTable: IFinance[] = []
    public searchRow: IFinance = {}

    get receipts(){
        let cash = 0, cashless = 0, total = 0
        this.table.forEach(o => {
            if(o.type === 1) {
                total += Number(o.amount)
                o.form === 1 ? cash += Number(o.amount) : cashless += Number(o.amount)
            }

        })
        return {cash, cashless, total}
    }
    get payoff(){
        let cash = 0, cashless = 0, total = 0
        this.table.forEach(o => {
            if(o.type === 2) {
                total += Number(o.amount)
                o.form === 1 ? cash += Number(o.amount) : cashless += Number(o.amount)
            }

        })
        return {cash, cashless, total}
    }


    // SEARCH TABLE
    @Mutation setSearchTable(payload: IFinance[]){this.searchTable = payload}
    @Action({commit:'setSearchTable'}) public async fetchSearchTable(data: string){
        let response = await axios.post('/vuex/finance', {func: 'searchTable', data: data})
        return  response.data
    }
    @Action({commit:'setSearchTable'}) public async fetchFieldSearchTable(data: object){
        let response = await axios.post('/vuex/finance', {func: 'searchFieldTable', data: data})
        return  response.data
    }

    // SEARCH ROW
    @Mutation setSearchRow(payload: IFinance[]){this.searchTable = payload}
    @Action({commit:'setSearchRow'}) public async fetchSearchRow(data: IFinance){
        let response = await axios.post('/vuex/finance', {func: 'searchRow', data: data})
        return  response.data
    }
    @Action({commit:'setSearchRow'}) public async fetchFieldSearchRow(payload: object){
        let response = await axios.post('/vuex/finance', {func: 'searchFieldRow', data: payload})
        return  response.data
    }




// READ TABLE
    @Mutation setTable(payload: IFinance[]){this.table = payload}
    @Action({commit: 'setTable'}) public async readTable(data: string) {
        let response = await axios.post('/vuex/finance',{func: 'readTable', data: data})
        return response.data
    }
// UPDATE TABLE - множественная вставка и возврат с обновлением строк
    @Mutation setUpdateTableRow(payload: IFinance) {
        this.table = this.table.map(o => {
            if (o.id === payload.id) {
                return payload
            }
            return o;
        })
    }
    @Action({commit: 'setUpdateTableRow'}) public async readUpdateRow(data: IFinance) {
        let response = await axios.post('/vuex/finance',{func: 'readUpdateRow', data: data})
        return response.data
    }
// UPDATE TABLE ROW - обновление строки и возврат с обновлением данной строки в таблице
    @Mutation setUpdateTableRows(payload: IFinance) {
        this.table = this.table.map(o => {
            if (o.id === payload.id) {
                return payload
            }
            return o;
        })
    }
    @Action({commit: 'setUpdateTableRows'}) public async readUpdateTableRows(data: IFinance) {
        let response = await axios.post('/vuex/finance',{func: 'readUpdateTable', data: data})
        return response.data
    }
    // создать строку,  добавить в таблицу
    @Mutation appendCreatedRow(payload: IFinance[]){this.table = payload}
    @Action({commit: 'appendCreatedRow'}) public async createAppendRow(data: IFinance) {
        let response = await axios.post('/vuex/finance',{func: 'createRow', data: data})
        return response.data
    }


    @Mutation setRow(payload: IFinance){this.row = payload}
// CREATE ROW
    @Action({commit: 'setRow'}) public async createRow(data: IFinance) {
        let response = await axios.post('/vuex/finance',{func: 'createRow', data: data})
        return response.data
    }
// READ ROW
    @Action({commit: 'setRow'}) public async readRow(data: string) {
        let response = await axios.post('/vuex/finance',{func: 'readRow', data: data})
        return response.data
    }
// UPDATE_ROW
    @Mutation setUpdateRow(payload: IFinance){this.row = payload}
    @Action({commit: 'setUpdateRow'}) public async updateRow(data: IFinance) {
        let response = await axios.post('/vuex/finance',{func: 'updateRow', data: data})
        return response.data
    }


    ///////////////////////////////
    /** @desc eCreateRow */
    @Action public async eCreateRow(payload: string) {
        await axios.post('/vuex/finance',{func: 'eCreateRow', data: payload})
    }

    @Action eUpdateRow(payload: IFinance) {
        axios.post('/vuex/finance', {func: 'eUpdateRow', data: payload})
    }



}
