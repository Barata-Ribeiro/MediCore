export interface CatalogMuscleGroup {
    id: number;
    name: string;
    exercises_count?: number;
}

export interface CatalogExercise {
    id: number;
    name: string;
    description: string | null;
    video_url: string | null;
    muscle_groups: CatalogMuscleGroup[];
}
