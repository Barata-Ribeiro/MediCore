import AppLogoIcon from '@/components/application/app-logo-icon';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { dashboard, login, register } from '@/routes';
import { lang } from '@erag/lang-sync-inertia/react';
import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowRight, ClipboardList, Dumbbell, FlaskConical } from 'lucide-react';

const features = [
    { label: 'main.menu.sidebar_items.exams', icon: FlaskConical },
    { label: 'main.menu.sidebar_items.medical_file', icon: ClipboardList },
    { label: 'main.menu.sidebar_items.fitness', icon: Dumbbell },
];

export default function Welcome({ canRegister = true }: Readonly<{ canRegister?: boolean }>) {
    const { auth, name } = usePage().props;
    const { __ } = lang();

    return (
        <>
            <Head title={__('main.welcome.head_title')} />

            <div className="bg-background text-foreground flex min-h-dvh flex-col">
                <header className="mx-auto flex w-full max-w-6xl flex-wrap items-center justify-between gap-4 px-6 py-6 sm:px-10">
                    <div className="flex items-center gap-2.5">
                        <span className="bg-primary text-primary-foreground flex size-10 items-center justify-center rounded-2xl [&>svg]:size-6">
                            <AppLogoIcon aria-hidden="true" />
                        </span>
                        <span className="font-heading text-xl font-semibold tracking-tight">{name}</span>
                    </div>

                    <nav aria-label={__('main.welcome.navigation')} className="flex items-center gap-2">
                        {auth.user ? (
                            <Button nativeButton={false} render={<Link href={dashboard()} />}>
                                {__('main.menu.sidebar_items.dashboard')}
                                <ArrowRight aria-hidden="true" data-icon="inline-end" />
                            </Button>
                        ) : (
                            <>
                                <Button variant="ghost" nativeButton={false} render={<Link href={login()} />}>
                                    {__('main.welcome.login')}
                                </Button>
                                {canRegister && (
                                    <Button variant="outline" nativeButton={false} render={<Link href={register()} />}>
                                        {__('main.welcome.register')}
                                    </Button>
                                )}
                            </>
                        )}
                    </nav>
                </header>

                <main className="mx-auto grid w-full max-w-6xl flex-1 items-center gap-14 px-6 py-14 sm:px-10 sm:py-20 lg:grid-cols-2 lg:gap-20">
                    <div className="flex flex-col items-start gap-8">
                        <Badge variant="secondary">{__('main.welcome.eyebrow')}</Badge>

                        <h1 className="max-w-lg text-5xl leading-[1.05] font-medium tracking-tight text-balance sm:text-6xl lg:text-7xl">
                            {__('main.welcome.title')}{' '}
                            <span className="text-muted-foreground">{__('main.welcome.title_end')}</span>
                        </h1>

                        <Button
                            size="lg"
                            nativeButton={false}
                            render={<Link href={auth.user ? dashboard() : login()} />}
                        >
                            {__(auth.user ? 'main.welcome.open_dashboard' : 'main.welcome.get_started')}
                            <ArrowRight aria-hidden="true" data-icon="inline-end" />
                        </Button>
                    </div>

                    <div className="relative isolate mx-auto w-full max-w-md px-4 py-8 sm:px-8 sm:py-12">
                        <div aria-hidden="true" className="bg-muted/60 absolute inset-0 -z-10 rounded-[3rem]" />
                        <div
                            aria-hidden="true"
                            className="border-primary/20 absolute inset-x-10 top-4 -z-10 aspect-square rounded-full border"
                        />
                        <div
                            aria-hidden="true"
                            className="border-primary/20 absolute inset-x-20 top-14 -z-10 aspect-square rounded-full border"
                        />

                        <Card>
                            <CardHeader className="items-center justify-items-center gap-5 py-5">
                                <span className="bg-primary text-primary-foreground flex size-20 items-center justify-center rounded-3xl [&>svg]:size-11">
                                    <AppLogoIcon aria-hidden="true" />
                                </span>
                                <CardTitle>
                                    <h2>{__('main.welcome.overview')}</h2>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <ul className="flex flex-col">
                                    {features.map(({ label, icon: Icon }, index) => (
                                        <li key={label}>
                                            {index > 0 && <Separator />}
                                            <div className="flex items-center gap-4 py-4">
                                                <span className="bg-muted text-muted-foreground flex size-10 shrink-0 items-center justify-center rounded-2xl">
                                                    <Icon aria-hidden="true" className="size-5" />
                                                </span>
                                                <span className="font-heading text-base font-medium">{__(label)}</span>
                                            </div>
                                        </li>
                                    ))}
                                </ul>
                            </CardContent>
                        </Card>
                    </div>
                </main>

                <footer className="text-muted-foreground mx-auto flex w-full max-w-6xl items-center gap-3 px-6 py-6 text-xs sm:px-10">
                    <span>{name}</span>
                    <Separator orientation="vertical" className="h-3" />
                    <span>{__('main.welcome.footer')}</span>
                </footer>
            </div>
        </>
    );
}
