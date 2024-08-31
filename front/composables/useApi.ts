import type { Author, Article } from "#app";

export const useApi = () => {
    const {
        public: { API_BASE_URL: baseUrl },
    } = useRuntimeConfig();

    const fetchAuthors = async (): Promise<Array<Author>> => await $fetch(`${baseUrl}/authors`, {
        credentials: 'include'
    });

    const fetchAuthor = async (id?: string): Promise<Author | undefined> => {
        if (!id) {
            return undefined;
        }

        const authors = await fetchAuthors();
        const authorId = Number(id);

        return authors.find((author) => author.id === authorId);
    };

    const fetchPosts = async (authorId?: string): Promise<Array<Article>> => {
        const query: any = {};

        if (authorId && authorId.length > 0) {
            query.author = authorId;
        }

        return await $fetch(`${baseUrl}/posts`, { query, credentials: 'include' });
    };

    const fetchPostById = async (id?: string): Promise<Article> => await $fetch(`${baseUrl}/posts/${id}`, {
        credentials: 'include'
    });

    const fetchReadPosts = async () => await $fetch(`${baseUrl}/read-posts`, { credentials: 'include' });

    const readPost = async (id?: number, token?: string) =>
        await $fetch(`${baseUrl}/posts/${id}/read`, {
            method: "POST",
            headers: {
                "X-Token": String(token),
            },
            credentials: 'include',
        });

    return { fetchAuthors, fetchAuthor, fetchPosts, fetchPostById, fetchReadPosts, readPost };
};
