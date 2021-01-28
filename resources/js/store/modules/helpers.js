import Axios from "axios";

const helpers = {
    state: {
        h: {}
    },
    getters: {
        h(state) {
            return state.h
        }
    },
    mutations: {
        FETCH_HELPERS(state, payload) { state.h = payload},
    },
    actions: {
        // INIT ACTIONS
        async fetchHelpers({commit,getters},payload){
            try {
                let response = await Axios.post('/helper/async',{
                    func: 'helpers'
                });
                if(await response.data) {
                  await  commit('FETCH_HELPERS', response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },
/*         fetchHelpers({commit}) {
            return Axios.post('/helper/async', {
                func: 'helpers'
            })
                .then(response => commit('FETCH_HELPERS', response.data))
                .catch(error => console.log(error));
        },*/

    }
};


export default helpers;
