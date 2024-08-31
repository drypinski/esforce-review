<script setup lang="ts">
const { fetchAuthors } = useApi();

const { data: authors } = await useAsyncData("authors", async () => await fetchAuthors(), {
    default: () => [],
});
</script>

<template>
    <h1 :class="$style.header">Авторы</h1>
    <ul v-if="authors.length > 0" :class="$style.authors">
        <li v-for="author in authors" :key="author.id" :class="$style.author">
            <NuxtLink :to="`/?author=${author.id}`">{{ author.name }}</NuxtLink>
        </li>
    </ul>
    <div v-else>Ничего не найдено</div>
</template>

<style module>
.header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin: 0;
}

.authors {
    display: grid;
    gap: 15px;
    background-color: var(--dark-color);
    border-radius: var(--border-radius);
    margin: 10px 0;
    padding: 10px;
}
.author {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    border-radius: var(--border-radius);
    overflow: hidden;

    a {
        display: block;
        padding: 5px 10px;
        background-color: var(--dark-color);
        color: var(--light-color);
        width: 100%;
        text-decoration: none;

        &:hover {
            background-color: var(--secondary-color);
        }
    }
}
</style>
