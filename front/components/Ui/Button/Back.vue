<script setup lang="ts">
</script>

<template>
    <button @click="$router.go(-1)" :class="$style.button">
        <b>&#11164;</b>
        <span><slot>Назад</slot></span>
    </button>
</template>

<style module lang="scss">
.button {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 15px 0 0;
    padding: 0 5px;
    background-color: inherit;
    color: var(--secondary-color);
    line-height: 30px;
    border: none;
    cursor: pointer;

    b {
        font-size: 2rem;
    }
}
</style>
