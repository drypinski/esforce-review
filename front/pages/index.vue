<script setup lang="ts">
const { fetchAuthor, fetchPosts } = useApi();
const route = useRoute();
const router = useRouter();
const authorId = ref(route.query.author ? route.query.author?.toString() : undefined);
const loading = ref(false);

const { data: posts } = await useAsyncData("posts", async () => await fetchPosts(authorId.value), {
    default() {
        return [];
    },
});

const { data: author } = await useAsyncData(`author-${authorId}`, async () => await fetchAuthor(authorId.value));

watch(
    () => route.query.author,
    async (newValue, oldValue) => {
        if (newValue === oldValue) {
            return;
        }

        const authorValue = newValue && newValue.length > 0 ? newValue.toString() : undefined;
        await router.push({ path: "", query: { author: authorValue } });

        window?.scrollTo({ top: 0, behavior: "smooth" });

        loading.value = true;
        authorId.value = authorValue;

        try {
            const [postItems, authorItem] = await Promise.all([
                await fetchPosts(authorValue),
                await fetchAuthor(authorValue),
            ]);
            posts.value = postItems;
            author.value = authorItem;
        } catch (e: any) {
            console.error(e);
        }

        loading.value = false;
    },
    { immediate: true },
);
</script>

<template>
    <h1 v-if="!loading" :class="$style.header">
        <span v-if="author" :class="$style.author">
            <UiButtonBack />
            <span>{{ author.name }}</span>
        </span>
        <span v-else>Статьи</span>
    </h1>
    <div v-if="loading" :class="$style.loading">
        <UiLoading />
    </div>
    <PostList v-else :posts="posts" :author-id="authorId" />
</template>

<style module>
.header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
}
.author {
    display: flex;
    align-items: center;
    font-size: 1.5rem;
}
.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}
</style>
