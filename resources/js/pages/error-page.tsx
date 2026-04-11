import errorImageAvif from '../../../public/static/error/elke-burhenne-Dp1iUwhlDEA-unsplash.avif';
import errorImageJpg from '../../../public/static/error/elke-burhenne-Dp1iUwhlDEA-unsplash.jpg';
import errorImageWebp from '../../../public/static/error/elke-burhenne-Dp1iUwhlDEA-unsplash.webp';

import { Button } from '@/components/ui/button';
import { dashboard } from '@/routes';
import { Link } from '@inertiajs/react';
import { ArrowLeftFromLineIcon } from 'lucide-react';

type Props = {
    status: number;
};

export default function ErrorPage({ status }: Readonly<Props>) {
    const title = {
        503: 'Service Unavailable',
        500: 'Server Error',
        404: 'Page Not Found',
        403: 'Forbidden',
    }[status];

    const description = {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[status];

    return (
        <main className="isolate flex h-dvh min-h-full flex-col items-center justify-center">
            <picture className="absolute inset-0 -z-10">
                <source srcSet={errorImageWebp} type="image/webp" />
                <source srcSet={errorImageAvif} type="image/avif" />
                <img
                    src={errorImageJpg}
                    alt="a foggy road lined with bare trees"
                    className="size-full object-cover object-bottom"
                />
            </picture>
            <div className="flex min-h-screen flex-col items-center justify-center px-8 py-8 sm:py-16 lg:justify-between lg:py-24">
                <span className="bg-linear-to-b from-white from-30% to-transparent bg-clip-text text-[clamp(10rem,16vw,16.625rem)] leading-none font-bold text-transparent">
                    {status}
                </span>
                <div className="text-center max-lg:mt-36">
                    <h1 className="text-2xl font-semibold tracking-tight text-muted sm:text-4xl">{title}</h1>
                    <p className="mt-6 text-lg leading-8 text-muted-foreground">{description}</p>

                    <Button variant="secondary" className="mt-10" asChild>
                        <Link href={dashboard()} as="button" viewTransition prefetch="hover">
                            <ArrowLeftFromLineIcon aria-hidden />
                            Go to dashboard
                        </Link>
                    </Button>
                </div>
            </div>
        </main>
    );
}
