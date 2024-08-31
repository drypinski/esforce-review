<template>
    <div :class="$style.header">
        <NuxtLink to="/">Главная</NuxtLink>
        <NuxtLink to="/read-posts">Просмотренные</NuxtLink>
        <NuxtLink to="/authors">Авторы</NuxtLink>
    </div>
</template>

<style module lang="scss">
.header {
    z-index: 1;
    margin: 0 10px;
    display: flex;
    justify-content: center;
    gap: 10px;
    text-transform: uppercase;
    border-bottom: 1px solid black;
    position: sticky;
    top: 0;
    background-color: var(--light-color);

    a {
        padding: 10px 5px;
        text-decoration: none;
        color: var(--dark-color);
        font-weight: bold;

        &:hover {
            text-decoration: underline;
        }
    }
}
</style>
