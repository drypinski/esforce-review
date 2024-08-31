declare module '#app' {
    interface Author {
        id: number
        name: string
    }
    interface Article {
        id: number
        title: string
        body: string
        author: Author
        read?: boolean
    }
}

export {}
