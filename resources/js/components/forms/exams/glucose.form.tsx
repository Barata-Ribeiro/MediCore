import GlucoseController from '@/actions/App/Http/Controllers/Exams/GlucoseController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { Glucose } from '@/types/application/exams/glucose';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    glucose?: Glucose;
};

const GlucoseForm = memo(({ glucose }: Readonly<Props>) => {
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
                            Glucose Level <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="glucose_level"
                                name="glucose_level"
                                placeholder="e.g. 87"
                                defaultValue={glucose?.glucose_level}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['glucose_level']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>mg/dL</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['glucose_level']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['glycated_hemoglobin']}>
                            <FieldLabel htmlFor="glycated_hemoglobin">Glycated Hemoglobin (HbA1c)</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="glycated_hemoglobin"
                                    name="glycated_hemoglobin"
                                    placeholder="e.g. 4.9"
                                    defaultValue={glucose?.glycated_hemoglobin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['glycated_hemoglobin']}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>%</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['glycated_hemoglobin']} />
                        </Field>

                        <Field data-invalid={!!errors['estimated_average_glucose']}>
                            <FieldLabel htmlFor="estimated_average_glucose">Estimated Average Glucose (eAG)</FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="estimated_average_glucose"
                                    name="estimated_average_glucose"
                                    placeholder="e.g. 99"
                                    defaultValue={glucose?.estimated_average_glucose}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['estimated_average_glucose']}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>mg/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['estimated_average_glucose']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={glucose?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-glucose" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        Save
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default GlucoseForm;
