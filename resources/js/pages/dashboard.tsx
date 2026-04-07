import DashboardGreetings from '@/components/helpers/dashboard/dashboard-greetings';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { useInitials } from '@/hooks/use-initials';
import { dashboard } from '@/routes';
import { edit as editProfile } from '@/routes/profile';
import type { MedicalFile } from '@/types/application/medical-file';
import type { Profile } from '@/types/application/profile';
import { Head, Link, usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    profile: Profile;
    medicalFile: MedicalFile;
    exams: Record<string, number>;
};

export default function Dashboard({ data }: Readonly<{ data: Props }>) {
    const page = usePage();
    const { auth } = page.props;
    const getInitials = useInitials();

    return (
        <Fragment>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <DashboardGreetings />

                <div className="grid gap-4 sm:grid-cols-2">
                    {/* PROFILE SUMMARY */}
                    <Card className="w-full">
                        <CardHeader className="flex items-center gap-4">
                            <Avatar className="size-16 overflow-hidden rounded-full">
                                <AvatarImage src={auth.user.avatar} alt={auth.user.name} />
                                <AvatarFallback className="rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {getInitials(auth.user.name)}
                                </AvatarFallback>
                            </Avatar>

                            <div className="grid gap-1">
                                <CardTitle className="text-xl">Profile Summary</CardTitle>
                                <CardDescription>A brief overview of your profile information.</CardDescription>
                            </div>
                        </CardHeader>

                        <CardContent className="grid gap-4 sm:grid-cols-2">
                            <dl className="grid gap-2">
                                <div>
                                    <dt className="font-medium">Full Name</dt>
                                    <dd>{data.profile.full_name}</dd>
                                </div>

                                <div>
                                    <dt className="font-medium">Sex</dt>
                                    <dd className="capitalize">{data.profile?.sex ?? 'N/A'}</dd>
                                </div>

                                <div>
                                    <dt className="font-medium">Date of Birth</dt>
                                    <dd>
                                        {data.profile?.birth_date
                                            ? format(new Date(data.profile.birth_date), 'MMMM dd, yyyy')
                                            : 'N/A'}
                                    </dd>
                                </div>
                            </dl>

                            <dl className="grid gap-2">
                                <div>
                                    <dt className="font-medium">Phone Number</dt>
                                    <dd>{data.profile.phone_number ?? 'N/A'}</dd>
                                </div>

                                <div>
                                    <dt className="font-medium">Address</dt>
                                    <dd>{data.profile.address ?? 'N/A'}</dd>
                                </div>

                                <div>
                                    <dt className="font-medium">Email</dt>
                                    <dd>{auth.user.email ?? 'N/A'}</dd>
                                </div>
                            </dl>
                        </CardContent>

                        <CardFooter className="flex items-end justify-between gap-4 border-t">
                            <time dateTime={data.profile.updated_at} className="text-sm text-muted-foreground">
                                Last updated: {format(new Date(data.profile.updated_at), 'MMMM dd, yyyy')}
                            </time>

                            <Button variant="secondary" size="sm" asChild>
                                <Link href={editProfile()} as="button" prefetch="hover">
                                    Edit Profile
                                </Link>
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </Fragment>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};
