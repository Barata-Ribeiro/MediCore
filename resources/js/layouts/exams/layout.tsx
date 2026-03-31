import { useInterval } from '@/hooks/use-interval';
import { format } from 'date-fns';
import { ClockIcon } from 'lucide-react';
import type { ReactNode } from 'react';
import { Fragment, useRef } from 'react';

type Props = {
    title: string;
    description: string;
    children: ReactNode;
};

export default function ExamsLayout({ title, description, children }: Readonly<Props>) {
    const currentTimeRef = useRef<HTMLSpanElement>(null);

    useInterval(() => {
        if (!currentTimeRef.current) {
            return;
        }

        const now = new Date();
        currentTimeRef.current.textContent = format(now, 'dd/MM/yyyy HH:mm');
    }, 60000);

    return (
        <Fragment>
            <div className="relative h-96 bg-linear-to-r from-chart-5 to-chart-1" />

            <header className="relative z-10 mx-auto -mt-15 w-full max-w-7xl px-4 lg:px-0">
                <div
                    style={{ backgroundColor: 'color-mix(in oklab, var(--primary-foreground) 50%, transparent)' }}
                    className="flex flex-wrap items-center justify-between gap-8 rounded-xl border border-chart-5 p-6 shadow-lg backdrop-blur-2xl dark:border-chart-5"
                >
                    <div>
                        <h1 className="text-2xl font-bold">{title}</h1>
                        <p className="mt-2 text-muted-foreground dark:text-muted">{description}</p>
                    </div>

                    <div className="inline-flex items-center gap-x-1">
                        <ClockIcon aria-hidden size={16} />
                        <span ref={currentTimeRef} className="text-sm text-muted-foreground dark:text-muted">
                            {format(new Date(), 'dd/MM/yyyy HH:mm')}
                        </span>
                    </div>
                </div>
            </header>

            <div className="px-4 pt-6 pb-2" aria-label={`${title} content`}>
                {children}
            </div>
        </Fragment>
    );
}
