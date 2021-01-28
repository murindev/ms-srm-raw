import svgJs from "svg.js/dist/svg"
/*
export default {
    install: Vue => {
        Vue.prototype.$svg = svgJs;
    }
}
*/
export default {
    install(Vue, options) {
        Vue.prototype.$svg = svgJs;
    }
}
