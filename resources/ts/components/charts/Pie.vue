<script lang="ts">

    import { Component, Prop, Watch, Vue } from 'vue-property-decorator'
    import {State, Mutation, Action, namespace } from 'vuex-class'

    import { Pie, mixins } from 'vue-chartjs'
    const { reactiveProp } = mixins

    @Component({
        extends: Pie,
        mixins: [reactiveProp],
        props: ['options','data'],
    })
    export default class PieChart extends Vue {
        // @Prop() options!: any



        initials(){
            return  {
                datasets: [{
                    data: [10, 20, 30],
                    backgroundColor:['#f87979','#797ff8','yellow',],
                    labels: [
                        'Red',
                        'Yellow',
                        'Blue'
                    ],
                }],

                /*            datasets: [
                                {
                                    label: 'Data One',
                                    backgroundColor: '#f87979',
                                    data: [this.getRandomInt()]
                                }, {
                                    label: 'Data Two',
                                    backgroundColor: '#797ff8',
                                    data: [this.getRandomInt()]
                                }, {
                                    label: 'Data Tree',
                                    backgroundColor: '#79f87b',
                                    data: [this.getRandomInt()]
                                }
                            ]*/

                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'Red',
                    'Yellow',
                    'Blue'
                ],
            };
        }
        mounted () {
            // @ts-ignore
            this.renderChart(this.chartData,  {
                tooltips: {
                    enabled: true,
                    mode: 'index',
                },
                legend: {
                    display: false
                }
            })
        }

    }
</script>
