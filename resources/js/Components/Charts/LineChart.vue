<template>
    <LineChartGenerator
    :chart-options="chartOptions"
    :chart-data="chartData"
    :chart-id="chartId"
    :dataset-id-key="datasetIdKey"
    :plugins="plugins"
    :css-classes="cssClasses"
    :styles="styles"
    :width="width"
    :height="height"
    />
</template>

<script>
import {Line as LineChartGenerator} from  'vue-chartjs/legacy'
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, ArcElement, PointElement } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, ArcElement, PointElement)

export default {
    name: "LineChart",

    components:{
        LineChartGenerator
    },
    props: {
        chartOptions: {
            type: Object,
        },
        chartData: {
            type: Object,
        },
        chartId: {
            type: String,
            default: 'line-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 48
        },
        height: {
            type: Number,
            default: 300
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Object,
            default: () => {}
        }
    },
}
</script>

<style scoped>

</style>
