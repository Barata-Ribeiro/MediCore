import authImageAvif from '../../../../public/static/auth/gautam-arora-gUFQybn_CVg-unsplash.avif';
import authImageJpg from '../../../../public/static/auth/gautam-arora-gUFQybn_CVg-unsplash.jpg';
import authImageWebp from '../../../../public/static/auth/gautam-arora-gUFQybn_CVg-unsplash.webp';

import AppLogoIcon from '@/components/application/app-logo-icon';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';
import { Link, usePage } from '@inertiajs/react';

export default function AuthSplitLayout({ children, title, description }: Readonly<AuthLayoutProps>) {
    const { name } = usePage().props;

    return (
        <div className="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div className="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r">
                <picture className="absolute inset-0 bg-zinc-900">
                    <source srcSet={authImageWebp} type="image/webp" />
                    <source srcSet={authImageAvif} type="image/avif" />
                    <img
                        src={authImageJpg}
                        alt="empty waiting room with couches and plants of a clinic"
                        className="size-full object-cover object-center opacity-50"
                    />
                </picture>
                <Link href={home()} className="relative z-20 inline-flex items-center gap-x-2 text-lg font-medium">
                    <span className="size-8" aria-hidden>
                        <AppLogoIcon className="fill-current text-white" />
                    </span>
                    {name}
                </Link>
            </div>
            <div className="w-full lg:p-8">
                <div className="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-87.5">
                    <Link href={home()} className="relative z-20 flex items-center justify-center lg:hidden">
                        <span className="size-10 sm:size-12" aria-hidden>
                            <AppLogoIcon className="fill-current text-black" />
                        </span>
                    </Link>
                    <div className="flex flex-col items-start gap-2 text-left sm:items-center sm:text-center">
                        <h1 className="text-xl font-medium">{title}</h1>
                        <p className="text-sm text-balance text-muted-foreground">{description}</p>
                    </div>
                    {children}
                </div>
            </div>
        </div>
    );
}
