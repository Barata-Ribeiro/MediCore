import { usePage } from '@inertiajs/react';
import { CalendarDaysIcon } from 'lucide-react';

export default function DashboardGreetings() {
    const { auth, date } = usePage().props;

    return (
        <div className="relative overflow-hidden rounded-2xl bg-linear-to-br from-primary via-primary/90 to-accent p-6 text-white sm:p-8">
            <div
                className="pointer-events-none absolute inset-0 opacity-10"
                style={{
                    backgroundImage: 'radial-gradient(rgba(255,255,255,0.8) 1px, transparent 1px)',
                    backgroundSize: '20px 20px',
                }}
            />

            <div className="relative flex justify-between gap-4">
                <div className="grid gap-1">
                    <h1 className="text-2xl font-bold tracking-tight text-balance sm:text-3xl">
                        {date.greeting}, {auth.user.name}
                    </h1>
                    <p className="mt-1 text-sm text-white/70">Here&apos;s a quick overview of your account.</p>
                </div>

                <time dateTime={date.now} className="hidden items-center gap-x-1 text-sm text-white/70 sm:inline-flex">
                    <CalendarDaysIcon className="size-4" aria-hidden /> {date.displayDate}
                </time>
            </div>
        </div>
    );
}
