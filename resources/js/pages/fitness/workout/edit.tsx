import { Head } from '@inertiajs/react';

type WorkoutEditProps = {
    workout: {
        id: number;
        goal: string | null;
        method: string | null;
    };
};

export default function Edit({ workout }: Readonly<WorkoutEditProps>) {
    return (
        <>
            <Head title="Edit Workout" />
            <h1 className="text-2xl font-semibold">Edit Workout #{workout.id}</h1>
            <p className="mt-2 text-sm text-muted-foreground">Goal: {workout.goal ?? 'N/A'}</p>
        </>
    );
}
