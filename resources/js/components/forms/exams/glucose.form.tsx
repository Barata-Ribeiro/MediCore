import GlucoseController from '@/actions/App/Http/Controllers/Exams/GlucoseController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { Glucose } from '@/types/application/exams/glucose';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    glucose?: Glucose;
};

export default function GlucoseForm({ glucose }: Readonly<Props>) {
    const { __ } = lang();
    const isEditMode = glucose && glucose !== null;

    const formRoute = isEditMode ? GlucoseController.update.form(glucose.id) : GlucoseController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['glucose_level']}>
                        <FieldLabel htmlFor="glucose_level">
                            {__('glucose_pages.form.glucose_level')} <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="glucose_level"
                                name="glucose_level"
                                placeholder={__('glucose_pages.form.glucose_level_placeholder')}
                                defaultValue={glucose?.glucose_level}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['glucose_level']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>{__('glucose_pages.shared.unit')}</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['glucose_level']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['glycated_hemoglobin']}>
                            <FieldLabel htmlFor="glycated_hemoglobin">
                                {__('glucose_pages.form.glycated_hemoglobin')}
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="glycated_hemoglobin"
                                    name="glycated_hemoglobin"
                                    placeholder={__('glucose_pages.form.glycated_hemoglobin_placeholder')}
                                    defaultValue={glucose?.glycated_hemoglobin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['glycated_hemoglobin']}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('glucose_pages.shared.percentage_unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['glycated_hemoglobin']} />
                        </Field>

                        <Field data-invalid={!!errors['estimated_average_glucose']}>
                            <FieldLabel htmlFor="estimated_average_glucose">
                                {__('glucose_pages.form.estimated_average_glucose')}
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="estimated_average_glucose"
                                    name="estimated_average_glucose"
                                    placeholder={__('glucose_pages.form.estimated_average_glucose_placeholder')}
                                    defaultValue={glucose?.estimated_average_glucose}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['estimated_average_glucose']}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('glucose_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['estimated_average_glucose']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('glucose_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={glucose?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-glucose" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('glucose_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
}
