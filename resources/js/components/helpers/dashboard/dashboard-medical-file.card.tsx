import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { edit as editMedicalFile } from '@/routes/medical-file';
import type { MedicalFile } from '@/types/application/medical-file';
import { bloodTypeLabel } from '@/types/application/medical-file';
import { Link } from '@inertiajs/react';
import { format } from 'date-fns';
import { FileTextIcon } from 'lucide-react';
import { memo } from 'react';

type Props = {
    medicalFile: MedicalFile | null;
};

const DashboardMedicalFileCard = memo<Readonly<Props>>(({ medicalFile }) => {
    if (!medicalFile) {
        return (
            <Empty className="border border-dashed">
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <FileTextIcon aria-hidden />
                    </EmptyMedia>
                    <EmptyTitle>No Medical File Information</EmptyTitle>
                    <EmptyDescription>Please complete your medical file to view this card</EmptyDescription>
                </EmptyHeader>
                <EmptyContent>
                    <Button variant="outline" size="sm" asChild>
                        <Link href={editMedicalFile()} as="button" prefetch="hover">
                            Complete Medical File
                        </Link>
                    </Button>
                </EmptyContent>
            </Empty>
        );
    }

    return (
        <Card className="w-full">
            <CardHeader>
                <CardTitle className="text-xl">Medical File Summary</CardTitle>
                <CardDescription>A quick overview of your medical history and records.</CardDescription>
            </CardHeader>

            <CardContent className="grid gap-4">
                <dl className="grid gap-2">
                    <div>
                        <dt className="font-medium">Blood Type</dt>
                        <dd>{medicalFile.blood_type ? bloodTypeLabel(medicalFile.blood_type) : 'Not Informed'}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">Allergies</dt>
                        <dd>{medicalFile.allergies ?? 'Not Informed'}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">Diseases</dt>
                        <dd>{medicalFile.diseases ?? 'Not Informed'}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">Medications</dt>
                        <dd>{medicalFile.medications ?? 'Not Informed'}</dd>
                    </div>
                </dl>
            </CardContent>

            <CardFooter className="mt-auto flex flex-wrap items-end justify-between gap-4 border-t">
                <time dateTime={medicalFile.updated_at} className="text-sm text-muted-foreground">
                    Last updated: {format(new Date(medicalFile.updated_at), 'MMMM dd, yyyy')}
                </time>

                <Button variant="secondary" size="sm" asChild>
                    <Link href={editMedicalFile()} as="button" prefetch="hover">
                        Edit Medical File
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
});

export default DashboardMedicalFileCard;
