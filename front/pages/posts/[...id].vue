<script setup lang="ts">
const { hook, $recaptcha } = useNuxtApp();
const { fetchPostById, readPost } = useApi();

const { id } = useRoute().params;
const { data: post } = await useAsyncData(`post-${id}`, async () => await fetchPostById(id as string));
const read = ref(0);

hook("page:loading:end", async () => {
    const postId = Number(id);

    if (read.value === postId) {
        return;
    }

    read.value = Number(id);
    const token = await $recaptcha.execute();
    await readPost(postId, token);
});
</script>

<template>
    <div :class="$style.container">
        <article v-if="post" :class="$style.article">
            <header>
                <h1>
                    <UiButtonBack />
                    <span>{{ post.title }}</span>
                </h1>
            </header>
            <p>{{ post.body }}</p>
            <div :class="$style.author">
                автор: <NuxtLink :to="`/?author=${post.author.id}`">{{ post.author.name }}</NuxtLink>
            </div>
        </article>
        <div v-else>Ничего не найдено</div>
    </div>
</template>

<style module lang="scss">
.container {
    height: calc(100vh - 40px - 20px - 60px);
}

.article {
    display: grid;
    gap: 15px;
    margin: 0 15px;

    h1 {
        display: flex;
        align-items: center;
        font-size: 2rem;

        span {
            text-transform: capitalize;
        }
    }

    p {
        font-size: 1.5rem;
    }
}

.author {
    text-align: right;
}
</style>
