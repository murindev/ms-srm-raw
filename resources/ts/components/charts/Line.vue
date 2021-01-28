<script lang="ts">

    import { Component, Prop, Watch, Vue } from 'vue-property-decorator'
    import {State, Mutation, Action, namespace } from 'vuex-class'

    import { Line, mixins } from 'vue-chartjs'
    const { reactiveProp } = mixins

    @Component({
        extends: Line,
        mixins: [reactiveProp],
        props: ['options'],
        mounted () {
            // @ts-ignore
            this.renderChart(this.chartData, this.options)
        }
    })
    export default class LineChart extends Vue {
        // @Prop() options!: any

    }
</script>
