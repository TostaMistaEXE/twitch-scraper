<template>
    <div>
        <div
            class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md items-center space-x-4">
            <div>
                <div
                    class="text-xl font-medium text-red-600"
                    v-for="streamer in streamers">
                    <a :href="streamer.streamer" v-bind:class="{'text-green-500': streamer.run }"> {{ streamer.streamer }}</a>
                </div>
                <p class="text-gray-500"></p>
            </div>
            <div class="flex-auto max-w-sm mx-auto items-center">
                {{ streamerName }}
                <input v-model="streamerName"
                       class="w-full h-12 px-4 mb-2 text-lg text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline"
                       type="text" placeholder="Insert a name of a streamer"/>
                <button v-on:click="insertStreamer"
                        class="w-full h-12 px-6 m-2 text-lg text-indigo-100 transition-colors duration-150 bg-indigo-700 rounded-lg focus:shadow-outline hover:bg-indigo-800">
                    Add
                </button>
            </div>

        </div>


        <!--<line-chart
            :chartData="datacollection"
            :chartOptions="chartOptions"
            label="Positve"
        />--->

    </div>
</template>

<script>
import LineChart from "./LineChart.vue";

export default {
    components: {
        LineChart
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
            chartOptions: {responsive: true},
            streamerName: '',
            streamers: '',
        };
    },
    mounted() {
        this.getStreamers();
    },
    methods: {
        getStreamers() {
            axios.get('/api/streamers/getAll').then(response => {
                this.streamers = response.data
            });
        },
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
        },
        insertStreamer() {
            axios.post('/api/streamers/insert', {streamer: this.streamerName})
                .catch(error => {
                    console.log(error.message);
                }).then(response => {

                this.getStreamers();
            });
        },
    }
};
</script>
