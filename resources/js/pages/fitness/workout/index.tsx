import { Head } from '@inertiajs/react';

type WorkoutIndexProps = {
    workouts: {
        data: Array<{
            id: number;
            goal: string | null;
            method: string | null;
            is_active: boolean;
        }>;
    };
};

export default function Index({ workouts }: Readonly<WorkoutIndexProps>) {
    return (
        <>
            <Head title="Workouts" />
            <h1 className="text-2xl font-semibold">Workouts</h1>
            <p className="mt-2 text-sm text-muted-foreground">{workouts.data.length} workout(s) found.</p>
        </>
    );
}
