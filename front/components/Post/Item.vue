<script setup lang="ts">
import type { Article } from "#app";

defineProps({
    post: {
        type: Object as PropType<Article>,
        required: true,
    },
    withLink: Boolean,
});
</script>

<template>
    <article :class="`${$style.article}${post.read ? ` ${$style.read}` : ''}`">
        <header>
            <h2>
                <NuxtLink :to="`/posts/${post.id}`">{{ post.title }}</NuxtLink>
            </h2>
        </header>
        <p>{{ post.body }}</p>
        <div :class="$style.footer">
            <span>
                автор:
                <NuxtLink v-if="withLink" :to="`/?author=${post.author.id}`">{{ post.author.name }}</NuxtLink>
                <b v-else>{{ post.author.name }}</b>
            </span>
            <small v-if="post.read">Просмотрено</small>
        </div>
    </article>
</template>

<style module lang="scss">
.article {
    display: grid;
    gap: 5px;
    padding: 15px 20px;
    background-color: var(--dark-color);
    color: var(--secondary-color);

    & > header > h2 {
        margin: 0;
        font-size: 26px;

        & > a {
            display: block;
            text-decoration: none;
            color: var(--light-color);
        }
    }
    & > p {
        font-size: 20px;
        line-height: 24px;
    }
}
.read {
    opacity: 0.8;
}
.footer {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;

    a {
        color: var(--warning-color);
        font-weight: bold;
    }

    b {
        color: var(--warning-color);
    }

    small {
        color: var(--info-color);
    }
}
</style>
