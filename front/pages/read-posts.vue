<script setup lang="ts">
const { fetchReadPosts } = useApi();
const loading = ref(false);

const { data: posts } = await useAsyncData("posts", async () => await fetchReadPosts(), {
    default: () => [],
});
</script>

<template>
    <h1>Просмотренные</h1>
    <div v-if="loading" :class="$style.loading">
        <UiLoading />
    </div>
    <PostList v-else :posts="posts" />
</template>

<style module>
.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}
</style>
