<template>
    <div class="spin">
        <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div>
</template>

<script>
export default {
    name: "Spiner"
}
</script>

<style scoped>
    .spin {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>
