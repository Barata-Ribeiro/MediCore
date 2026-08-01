import type { ReactNode } from 'react';

type Props = {
    title: string;
    children: ReactNode;
};

export default function FitnessLayout({ title, children }: Readonly<Props>) {
    return (
        <div className="px-4 pt-6 pb-2" aria-label={`${title} content`}>
            {children}
        </div>
    );
}
