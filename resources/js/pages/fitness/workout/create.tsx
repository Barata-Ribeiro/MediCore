import { Head } from '@inertiajs/react';

type WorkoutCreateProps = {
    formOptions: {
        exercises: Array<{ id: number; name: string }>;
        muscleGroups: Array<{ id: number; name: string }>;
    };
};

export default function Create({ formOptions }: Readonly<WorkoutCreateProps>) {
    return (
        <>
            <Head title="Create Workout" />
            <h1 className="text-2xl font-semibold">Create Workout</h1>
            <p className="mt-2 text-sm text-muted-foreground">
                Available exercises: {formOptions.exercises.length}. Muscle groups: {formOptions.muscleGroups.length}.
            </p>
        </>
    );
}
