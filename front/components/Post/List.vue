<script setup lang="ts">
import type { Article } from "#app";

const { posts } = defineProps({
    posts: {
        type: Array<Article>,
        default: () => [],
    },
    authorId: String,
});
</script>

<template>
    <ul v-if="posts.length > 0" :class="$style.items">
        <li v-for="post in posts" :key="post.id" :class="$style.item">
            <PostItem :post="post" :with-link="Number(authorId) !== post.author.id" />
        </li>
    </ul>
    <div v-else>Ничего не найдено</div>
</template>

<style module>
.items {
    display: grid;
    gap: 15px;
    padding: 0;
}

.item {
    display: grid;
    padding: 0;
    border-radius: var(--border-radius);
    overflow: hidden;
}
</style>
