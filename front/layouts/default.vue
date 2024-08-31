<script setup lang="ts"></script>

<template>
    <LayoutHeader />
    <div :class="$style.container">
        <div :class="$style.body">
            <slot />
        </div>
    </div>
    <LayoutFooter />
</template>

<style module>
body {
    margin: 0;
}

.container {
    margin: 10px;
}

.body {
    margin: 0 auto;
    max-width: 800px;
}
</style>
