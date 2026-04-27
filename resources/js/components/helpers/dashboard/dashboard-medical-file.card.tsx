import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { edit as editMedicalFile } from '@/routes/medical-file';
import type { MedicalFile } from '@/types/application/medical-file';
import { bloodTypeLabel } from '@/types/application/medical-file';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link } from '@inertiajs/react';
import { format } from 'date-fns';
import { FileTextIcon } from 'lucide-react';
import { memo } from 'react';

type Props = {
    medicalFile: MedicalFile | null;
};

const DashboardMedicalFileCard = memo<Readonly<Props>>(({ medicalFile }) => {
    const { __ } = lang();

    if (!medicalFile) {
        return (
            <Empty className="border border-dashed">
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <FileTextIcon aria-hidden />
                    </EmptyMedia>
                    <EmptyTitle>{__('dashboard.medical_file_card.empty.title')}</EmptyTitle>
                    <EmptyDescription>{__('dashboard.medical_file_card.empty.message')}</EmptyDescription>
                </EmptyHeader>
                <EmptyContent>
                    <Button variant="outline" size="sm" asChild>
                        <Link href={editMedicalFile()} as="button" prefetch="hover">
                            {__('dashboard.medical_file_card.empty.action')}
                        </Link>
                    </Button>
                </EmptyContent>
            </Empty>
        );
    }

    return (
        <Card className="w-full">
            <CardHeader>
                <CardTitle className="text-xl">{__('dashboard.medical_file_card.card.title')}</CardTitle>
                <CardDescription>{__('dashboard.medical_file_card.card.description')}</CardDescription>
            </CardHeader>

            <CardContent className="grid gap-4">
                <dl className="grid gap-2">
                    <div>
                        <dt className="font-medium">{__('dashboard.medical_file_card.card.blood_type')}</dt>
                        <dd>
                            {medicalFile.blood_type
                                ? bloodTypeLabel(medicalFile.blood_type)
                                : __('dashboard.card_not_informed')}
                        </dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.medical_file_card.card.allergies')}</dt>
                        <dd>{medicalFile.allergies ?? __('dashboard.card_not_informed')}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.medical_file_card.card.diseases')}</dt>
                        <dd>{medicalFile.diseases ?? __('dashboard.card_not_informed')}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.medical_file_card.card.medications')}</dt>
                        <dd>{medicalFile.medications ?? __('dashboard.card_not_informed')}</dd>
                    </div>
                </dl>
            </CardContent>

            <CardFooter className="mt-auto flex flex-wrap items-end justify-between gap-4 border-t">
                <time dateTime={medicalFile.updated_at} className="text-sm text-muted-foreground">
                    {__('dashboard.medical_file_card.card.updated_at')}{' '}
                    {format(new Date(medicalFile.updated_at), 'MMMM dd, yyyy')}
                </time>

                <Button variant="secondary" size="sm" asChild>
                    <Link href={editMedicalFile()} as="button" prefetch="hover">
                        {__('dashboard.medical_file_card.card.edit_action')}
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
});

export default DashboardMedicalFileCard;
