export interface Category {
    id: number;
    name: string;
    icon: string | null;
    color: string;
    description: string | null;
    is_active: boolean;
    expenses_count?: number;
    created_at: string;
    updated_at: string;
}
