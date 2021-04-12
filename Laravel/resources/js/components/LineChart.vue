{{chartData}}
<script>
import { Line } from "vue-chartjs";

export default {
    extends: Line,
    props: {
        label: {
            type: String
        },
        chartData: {
            type: Object
        },
        options: {
            type: Object
        }
    },
    mounted() {
        // this.chartData is created in the mixin.
        // If you want to pass options please create a local options object
        this.renderChart(this.chartData, this.options);
        console.log(this.chartData);
    }
};
</script>
