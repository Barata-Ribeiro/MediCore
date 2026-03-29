export interface Link {
    url?: string;
    label: string;
    page?: number;
    active: boolean;
}

export interface PaginationMeta<T> {
    current_page: number;
    data: T;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Link[];
    next_page_url?: string;
    path: string;
    per_page: number;
    prev_page_url?: string;
    to: number;
    total: number;
}

export interface ScrollMeta<T> {
    data: T;
    path: string;
    per_page: number;
    next_cursor?: string;
    next_page_url?: string;
    prev_cursor?: string;
    prev_page_url?: string;
}
