export interface BookFormInterface {
    name: string;
    description: string;
    author: string;
    category_id: string;
    publisher: string;
    publication_year: string;
    total_copies: number;
    shelf_location: string;
    image: File | null;
}