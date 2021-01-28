import {VuexModule, Module, Mutation, Action, MutationAction} from 'vuex-module-decorators'
import axios from 'axios'
import {ICustomer, ICustomersLids} from "../../types/customer";
import {IFieldValue, IIdName} from "../../types/Global";
import {ILid} from "../../types/lid";

async function customerTableColumns() {
    try {
        let response = await axios.post('/vuex/customer', {func: 'columns'})
        return await response.data
    } catch (e) {
        console.log(e);
    }

}

@Module({
    namespaced: true
})
export default class Customer extends VuexModule {

    public status: IIdName[] = [
        {id: 1, name: 'Физическое лицо', short: 'Ф', amount: 0, order: 0},
        {id: 2, name: 'Юридическое лицо', short: 'Ю', amount: 0, order: 0},
        {id: 3, name: 'Клиент не прикреплен', short: 'Н', amount: 0, order: 0},
    ]


    public columns: any
    public table: ICustomer[] = []

    public row: ICustomer = {}
    public formerRow: ICustomer = {}

    public search: ICustomer[] = []
    public searchRow: ICustomer = {}


    public currentCustomer: ICustomer = {}
    public newCustomer: ICustomer = {}

    public hasManyLids: ILid[] = []


// interface
    @Mutation setColumns(payload: any) {
        this.columns = payload
    }

    @Action({commit: 'setColumns'})
    public async fetchColumns() {
        try {
            let response = await axios.post('/vuex/customer', {func: 'columns'})
            return response.data
        } catch (e) {
            console.log(e);
        }
    }


// SEARCH
    @Mutation setSearch(payload: ICustomer[]) {
        this.search = payload
    }

    // searchRow
    @Mutation setSearchRow(payload: ICustomer) {
        this.searchRow = payload
    }
    @Action({commit: 'setSearchRow'})
    public async fetchSearchRow(payload: string) {
        let response = await axios.post('/vuex/customer', {func: 'searchRow', data: payload})
        return response.data
    }


    @Action({commit: 'setSearch'})
    async fetchSearch(payload: string) {
        if (payload.length >= 2) {
            try {
                let response = await axios.post('/vuex/customer', {func: 'search', data: payload})
                return response.data
            } catch (error) {
                console.log(error)
            }
        } else {
            return []
        }
    }

    @Action({commit: 'setSearch'})
    public async fetchSearchField(payload: IFieldValue) {
        let response = await axios.post('/vuex/customer', {func: 'searchField', data: payload})
        return response.data
    }

// attachedLids
    @Mutation setHasManyLids(payload: ILid[]) {this.hasManyLids = payload}
    @Action({commit: 'setHasManyLids'}) public async fetchHasManyLids(data: IFieldValue) {
        let response = await axios.post('/vuex/customer',{func: 'fetchHasManyLids', data: data})
        return response.data
    }



// CUSTOMER SAVE
    @Mutation setNewCustomer(payload: ICustomer) {
        this.newCustomer = payload
    }

    @Action({commit: 'setNewCustomer'})
    async createCustomer(payload: ICustomer) {
        let response = await axios.post('/customer/async', {func: 'newCustomer', values: payload})
        return await response.data
    }

// GET CURRENT ROW
    @Mutation setRow(payload: ICustomer) {this.row = payload}
    @Action({commit: 'setRow'}) async fetchUpdateRow(data: ICustomer) {
        let response = await axios.post('/vuex/customer', {func: 'update', data: data})
        return response.data
    }

    @Mutation setFormerRow(payload: ICustomer) {
        this.formerRow = payload
    }


    @Mutation setCurrentCustomer(payload: ICustomer) {
        this.currentCustomer = payload
    }

    @Action({commit: 'setCurrentCustomer'})
    public async getCurrentCustomer(payload: number) {
        let response = await axios.post('/customer/async', {func: 'getByID', id: payload})
        return await response.data
    }




//   @Mutation updateRow


//////EVENT DRIVEN////////////////////////////

    @Action
    public eUpdate(data: ICustomer, logic: object = {}) {
        axios.post('/vuex/customer', {func: 'eUpdate', data, logic})
    }

    @Action
    public eCreate(data: ICustomer, logic: object = {}) {
        axios.post('/vuex/customer', {func: 'eCreate', data, logic})
    }

}
