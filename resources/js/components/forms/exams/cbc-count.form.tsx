import CompleteBloodCountController from '@/actions/App/Http/Controllers/Exams/CompleteBloodCountController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    cbcCount?: CompleteBloodCount;
};

type NumericFieldName = Exclude<
    keyof CompleteBloodCount,
    'id' | 'medical_file_id' | 'report_date' | 'created_at' | 'updated_at'
>;

type NumericField = {
    name: NumericFieldName;
    unit: string;
};

const fieldGroups: NumericField[][] = [
    [
        { name: 'hematocrit', unit: '%' },
        { name: 'hemoglobin', unit: 'g/dL' },
        { name: 'red_blood_cell_count', unit: 'million/µL' },
    ],
    [
        { name: 'mean_corpuscular_volume', unit: 'fL' },
        { name: 'mean_corpuscular_hemoglobin', unit: 'pg' },
        { name: 'mean_corpuscular_hemoglobin_concentration', unit: 'g/dL' },
    ],
    [
        { name: 'red_blood_cell_distribution_width', unit: '%' },
        { name: 'leukocyte_count', unit: '/mm' },
        { name: 'rod_neutrophil_count', unit: '/mm' },
    ],
    [
        { name: 'segmented_neutrophil_count', unit: '/mm' },
        { name: 'lymphocyte_count', unit: '/mm' },
        { name: 'monocyte_count', unit: '/mm' },
    ],
    [
        { name: 'eosinophil_count', unit: '/mm' },
        { name: 'basophil_count', unit: '/mm' },
        { name: 'metamyelocyte_count', unit: '/mm' },
    ],
    [
        { name: 'promyelocyte_count', unit: '/mm' },
        { name: 'atypical_cell_count', unit: '/mm' },
        { name: 'platelet_count', unit: '/mm' },
    ],
];

export default function CbcCountForm({ cbcCount }: Readonly<Props>) {
    const { __ } = lang();
    const isEditMode = cbcCount && cbcCount !== null;

    const formRoute = isEditMode
        ? CompleteBloodCountController.update.form(cbcCount.id)
        : CompleteBloodCountController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    {fieldGroups.map((group) => (
                        <FieldGroup
                            key={group.map((field) => field.name).join('-')}
                            className="grid gap-4 sm:grid-cols-2 md:grid-cols-3"
                        >
                            {group.map((field) => (
                                <Field key={field.name} data-invalid={!!errors[field.name]}>
                                    <FieldLabel htmlFor={field.name}>
                                        {__(`complete_blood_count_pages.form.${field.name}`)}
                                        <RequiredIndicator />
                                    </FieldLabel>
                                    <InputGroup>
                                        <InputGroupInput
                                            type="number"
                                            id={field.name}
                                            name={field.name}
                                            placeholder={__(
                                                `complete_blood_count_pages.form.${field.name}_placeholder`,
                                            )}
                                            defaultValue={cbcCount?.[field.name]}
                                            min={0}
                                            step={0.01}
                                            aria-invalid={!!errors[field.name]}
                                            required
                                            aria-required
                                        />
                                        <InputGroupAddon align="inline-end">
                                            <InputGroupText>{field.unit}</InputGroupText>
                                        </InputGroupAddon>
                                    </InputGroup>
                                    <InputError message={errors[field.name]} />
                                </Field>
                            ))}
                        </FieldGroup>
                    ))}

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('complete_blood_count_pages.form.report_date')}
                            <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={cbcCount?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-complete-blood-count" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('complete_blood_count_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
}
