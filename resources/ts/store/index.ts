import Vue from 'vue'
import Vuex from 'vuex'

Vue.config.productionTip = false;
Vue.config.performance = true;

import CrmStatistic from './modules/crmStatistic'
import Customer from "./modules/customer";
import Helper from "./modules/helper"
import Lid from "./modules/lid";
import Act from './modules/act'
import Implement from './modules/implement'
import Volume from './modules/volume'
import Finance from "./modules/finance";
import Funnel from "./modules/funnel";

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        CrmStatistic: CrmStatistic,
        Implement: Implement,
        Customer: Customer,
        Helper: Helper,
        Volume: Volume,
        Lid: Lid,
        Act: Act,
        Finance: Finance,
        Funnel: Funnel,
    }
})
