<template>
<div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-4">
  <div>
    <div class="text-xl font-medium text-black">{{streamer}}</div>
    <p class="text-gray-500">{{subcount}}</p>
  </div>
</div>
</template>

<script>
export default {
    props: ["subs", "subcount", "streamer"],
    mounted() {
        console.log("Component mounted.");
    }
};
</script>
