export interface CatalogMuscleGroup {
    id: number;
    name: string;
    exercises_count?: number;
    created_at: string;
}

export interface CatalogExercise {
    id: number;
    name: string;
    description: string | null;
    video_url: string | null;
    created_at: string;
    muscle_group_name?: string | null;
    muscle_groups: CatalogMuscleGroup[];
}
