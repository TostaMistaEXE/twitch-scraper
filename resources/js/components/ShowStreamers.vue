<template>
    <div
        class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-4"
    >
        <div>
            <div
                class="text-xl font-medium text-black"
                v-for="streamer in streamers"
                :key="streamer"
            >
                <a :href="streamer.streamer"> {{ streamer.streamer }}</a>
            </div>
            <p class="text-gray-500"></p>
        </div>
    </div>
</template>

<script>
export default {
    props: ["streamers"],
    mounted() {
        console.log("Component mounted.");
    }
};
</script>
