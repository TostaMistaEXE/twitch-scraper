<template>
    <div>
        <div
            class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-4"
        >
            <div>
                <div
                    class="text-xl font-medium text-black"
                    v-for="streamer in streamers"
                >
                    <a :href="streamer.streamer"> {{ streamer.streamer }}</a>
                </div>
                <p class="text-gray-500"></p>
            </div>
        </div>
        <line-chart
            :chartData="datacollection"
            :chartOptions="chartOptions"
            label="Positve"
        />
    </div>
</template>

<script>
import LineChart from "./LineChart.vue";

export default {
    components: {
        LineChart
    },
    props: {
        streamers: { type: Array, default: null }
    },
    data() {
        return {
            datacollection: {
                labels: [this.getRandomInt(), this.getRandomInt()],
                datasets: [
                    {
                        label: "Data One",
                        backgroundColor: "#f87979",
                        data: [this.getRandomInt(), this.getRandomInt()]
                    },
                    {
                        label: "Data One",
                        backgroundColor: "#f87979",
                        data: [this.getRandomInt(), this.getRandomInt()]
                    }
                ]
            },
            chartOptions: { responsive: true }
        };
    },
    mounted() {
        this.fillData();
    },
    methods: {
        fillData() {
            this.datacollection = {
                labels: [this.getRandomInt(), this.getRandomInt()],
                datasets: [
                    {
                        label: "Data One",
                        backgroundColor: "#f87979",
                        data: [this.getRandomInt(), this.getRandomInt()]
                    },
                    {
                        label: "Data One",
                        backgroundColor: "#f87979",
                        data: [this.getRandomInt(), this.getRandomInt()]
                    }
                ]
            };
        },
        getRandomInt() {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5;
        }
    }
};
</script>
