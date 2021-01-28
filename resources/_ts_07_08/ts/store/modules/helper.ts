import {VuexModule, Module, Mutation, Action} from 'vuex-module-decorators'
import axios from "axios";

import * as T from '../../types/helper'


@Module({
    namespaced: true
})
export default class Helper extends VuexModule {

    h: T.IHelper = {}

    @Mutation setHelper(payload: T.IHelper) {
        this.h = payload
    }

    @Action({commit: 'setHelper'})
    async fetchHelpers() {
        let response = await axios.post('/helper/async', {
            func: 'arrHelpers'
        });
        return response.data
    }

}
