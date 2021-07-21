<template>
    <div>
        CrmLid
    </div>
</template>

<script lang="ts">
    import {Component, Prop, Vue} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import * as Globals from "../types/Global";
    import {bus} from "../bus";
    import {ILid} from "../types/lid";
    import {IArrHelper} from "../types/Global";
    import lid from "../store/modules/lid";
    const Lid = namespace('Lid')

    @Component
    export default class CrmLid extends Vue {

    }
</script>


