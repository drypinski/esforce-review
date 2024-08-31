<script setup lang="ts">
const year = new Date().getFullYear();
</script>

<template>
    <footer :class="$style.footer">&copy; {{ year }}</footer>
</template>

<style module>
.footer {
    margin: 10px 10px 0;
    padding: 20px;
    text-align: center;
}
</style>
