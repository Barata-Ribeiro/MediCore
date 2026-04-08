import DashboardGreetings from '@/components/helpers/dashboard/dashboard-greetings';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Field, FieldLabel } from '@/components/ui/field';
import { Progress } from '@/components/ui/progress';
import { useInitials } from '@/hooks/use-initials';
import { cn } from '@/lib/utils';
import { dashboard } from '@/routes';
import { edit as editMedicalFile } from '@/routes/medical-file';
import { edit as editProfile } from '@/routes/profile';
import type { MedicalFile } from '@/types/application/medical-file';
import { bloodTypeLabel } from '@/types/application/medical-file';
import type { Profile } from '@/types/application/profile';
import { Head, Link, usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { Fragment } from 'react/jsx-runtime';

type Props = {
    profile: Profile;
    medicalFile: MedicalFile;
    exams: Record<string, number>;
};

const BMI_PROGRESS_MIN = 16;
const BMI_PROGRESS_MAX = 40;

function getBMIProgressPercentage(bmi: number): number {
    if (bmi <= BMI_PROGRESS_MIN) {
        return 0;
    }

    if (bmi <= 18.5) {
        return ((bmi - BMI_PROGRESS_MIN) / (18.5 - BMI_PROGRESS_MIN)) * 25;
    }

    if (bmi <= 25) {
        return 25 + ((bmi - 18.5) / (25 - 18.5)) * 25;
    }

    if (bmi <= 30) {
        return 50 + ((bmi - 25) / (30 - 25)) * 25;
    }

    if (bmi <= BMI_PROGRESS_MAX) {
        return 75 + ((bmi - 30) / (BMI_PROGRESS_MAX - 30)) * 25;
    }

    return 100;
}

function getBMICategory(bmi: number): { label: string; style: string; percentage: number } {
    switch (true) {
        case bmi <= 18.4:
            return {
                label: 'Underweight',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 18.5 && bmi <= 24.9:
            return {
                label: 'Normal',
                style: cn('bg-success text-success-content'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 25 && bmi <= 29.9:
            return {
                label: 'Overweight',
                style: cn('bg-warning text-warning-content'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 30 && bmi <= 34.9:
            return {
                label: 'Obesity Class I',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        case bmi >= 35 && bmi <= 39.9:
            return {
                label: 'Obesity Class II',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
        default:
            return {
                label: 'Obesity Class III',
                style: cn('bg-destructive text-destructive-foreground'),
                percentage: getBMIProgressPercentage(bmi),
            };
    }
}

export default function Dashboard({ data }: Readonly<{ data: Props }>) {
    const page = usePage();
    const { auth } = page.props;
    const getInitials = useInitials();
    const bmi = data.medicalFile.bmi ?? 0;
    const bmiCategory = getBMICategory(bmi);
    const roundedBmi = Math.floor(bmi * 100) / 100;

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

                        <CardFooter className="mt-auto flex items-end justify-between gap-4 border-t">
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

                    <div className="grid gap-4 lg:grid-cols-2">
                        {/* BMI */}
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle className="text-xl">Body Mass Index (BMI)</CardTitle>
                                <CardDescription>
                                    Your BMI is a measure of body fat based on your weight and height.
                                </CardDescription>
                            </CardHeader>

                            <CardContent className="grid gap-4">
                                <h3 className="text-center text-4xl font-bold">{roundedBmi}</h3>

                                <div className="flex flex-col items-center">
                                    <Badge className={bmiCategory.style}>{bmiCategory.label}</Badge>

                                    <Field className="mt-2 w-full">
                                        <FieldLabel htmlFor="progress-bmi" className="sr-only">
                                            BMI progress bar
                                        </FieldLabel>

                                        <Progress
                                            id="progress-bmi"
                                            aria-label="BMI progress bar"
                                            aria-valuetext={`BMI ${roundedBmi}`}
                                            value={bmiCategory.percentage}
                                            aria-readonly
                                        />

                                        <small className="relative flex w-full justify-between text-xs font-medium text-muted-foreground">
                                            <span>{BMI_PROGRESS_MIN}</span>
                                            <span>18.5</span>
                                            <span>25</span>
                                            <span>30</span>
                                            <span>{BMI_PROGRESS_MAX}</span>
                                        </small>

                                        <div className="mt-3 flex justify-between text-sm text-muted-foreground">
                                            <p className="m-0">
                                                Height:&nbsp; <span>{data.medicalFile.height ?? '0'}</span> cm
                                            </p>
                                            <p className="m-0">
                                                Weight:&nbsp; <span>{data.medicalFile.weight ?? '0'}</span> kg
                                            </p>
                                        </div>
                                    </Field>
                                </div>
                            </CardContent>

                            <CardFooter className="mt-auto flex flex-wrap items-end justify-between gap-4 border-t">
                                <time dateTime={data.medicalFile.updated_at} className="text-sm text-muted-foreground">
                                    Last updated: {format(new Date(data.medicalFile.updated_at), 'MMMM dd, yyyy')}
                                </time>

                                <Button variant="secondary" size="sm" asChild>
                                    <Link href={editMedicalFile()} as="button" prefetch="hover">
                                        Edit Medical File
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>

                        {/* MEDICAL FILE SUMMARY */}
                        <Card className="w-full">
                            <CardHeader>
                                <CardTitle className="text-xl">Medical File Summary</CardTitle>
                                <CardDescription>A quick overview of your medical history and records.</CardDescription>
                            </CardHeader>

                            <CardContent className="grid gap-4">
                                <dl className="grid gap-2">
                                    <div>
                                        <dt className="font-medium">Blood Type</dt>
                                        <dd>
                                            {data.medicalFile.blood_type
                                                ? bloodTypeLabel(data.medicalFile.blood_type)
                                                : 'Not Informed'}
                                        </dd>
                                    </div>

                                    <div>
                                        <dt className="font-medium">Allergies</dt>
                                        <dd>{data.medicalFile.allergies ?? 'Not Informed'}</dd>
                                    </div>

                                    <div>
                                        <dt className="font-medium">Diseases</dt>
                                        <dd>{data.medicalFile.diseases ?? 'Not Informed'}</dd>
                                    </div>

                                    <div>
                                        <dt className="font-medium">Medications</dt>
                                        <dd>{data.medicalFile.medications ?? 'Not Informed'}</dd>
                                    </div>
                                </dl>
                            </CardContent>

                            <CardFooter className="mt-auto flex flex-wrap items-end justify-between gap-4 border-t">
                                <time dateTime={data.medicalFile.updated_at} className="text-sm text-muted-foreground">
                                    Last updated: {format(new Date(data.medicalFile.updated_at), 'MMMM dd, yyyy')}
                                </time>

                                <Button variant="secondary" size="sm" asChild>
                                    <Link href={editMedicalFile()} as="button" prefetch="hover">
                                        Edit Medical File
                                    </Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
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
