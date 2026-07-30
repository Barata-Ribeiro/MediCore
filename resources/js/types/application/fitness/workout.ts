export interface WorkoutOptionMuscleGroup {
    id: number;
    name: string;
}

export interface WorkoutOptionExercise {
    id: number;
    name: string;
    muscle_groups: WorkoutOptionMuscleGroup[];
}

export interface WorkoutFormOptions {
    exercises: WorkoutOptionExercise[];
    muscleGroups: WorkoutOptionMuscleGroup[];
}

export interface WorkoutExerciseResource {
    id: number;
    workout_section_id: number;
    exercise_id: number;
    muscle_group_id: number | null;
    code: string | null;
    order: number;
    sets: number;
    reps: string;
    load: number | null;
    load_unit: string;
    rest_seconds: number | null;
    notes: string | null;
    exercise?: {
        id: number;
        name: string;
    };
    muscle_group?: {
        id: number;
        name: string;
    };
}

export interface WorkoutSectionResource {
    id: number;
    name: string;
    order: number;
    exercises: WorkoutExerciseResource[];
}

export interface WorkoutResource {
    id: number;
    filled_at: string | null;
    next_change_at: string | null;
    goal: string | null;
    method: string | null;
    rest_between_sets: number | null;
    rest_between_exercises: number | null;
    is_active: boolean;
    sections: WorkoutSectionResource[];
}
