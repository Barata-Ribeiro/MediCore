import { Head } from '@inertiajs/react';

type WorkoutShowProps = {
    workout: {
        id: number;
        goal: string | null;
        method: string | null;
        sections: Array<{ id: number; name: string }>;
    };
};

export default function Show({ workout }: Readonly<WorkoutShowProps>) {
    return (
        <>
            <Head title="Workout Details" />
            <h1 className="text-2xl font-semibold">Workout #{workout.id}</h1>
            <p className="mt-2 text-sm text-muted-foreground">Sections: {workout.sections.length}</p>
        </>
    );
}
