import LipidProfileController from '@/actions/App/Http/Controllers/Exams/LipidProfileController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    lipidProfile?: LipidProfile;
};

const LipidProfileForm = memo(({ lipidProfile }: Readonly<Props>) => {
    const isEditMode = lipidProfile && lipidProfile !== null;

    const formRoute = isEditMode
        ? LipidProfileController.update.form(lipidProfile.id)
        : LipidProfileController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['total_cholesterol']}>
                        <FieldLabel htmlFor="total_cholesterol">
                            Total Cholesterol <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="total_cholesterol"
                                name="total_cholesterol"
                                placeholder="e.g. 175"
                                defaultValue={lipidProfile?.total_cholesterol}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['total_cholesterol']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>mg/dL</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['total_cholesterol']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['hdl_cholesterol']}>
                            <FieldLabel htmlFor="hdl_cholesterol">
                                HDL Cholesterol <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="hdl_cholesterol"
                                    name="hdl_cholesterol"
                                    placeholder="e.g. 50"
                                    defaultValue={lipidProfile?.hdl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['hdl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>mg/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['hdl_cholesterol']} />
                        </Field>

                        <Field data-invalid={!!errors['ldl_cholesterol']}>
                            <FieldLabel htmlFor="ldl_cholesterol">
                                LDL Cholesterol <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="ldl_cholesterol"
                                    name="ldl_cholesterol"
                                    placeholder="e.g. 100"
                                    defaultValue={lipidProfile?.ldl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['ldl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>mg/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['ldl_cholesterol']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['vldl_cholesterol']}>
                            <FieldLabel htmlFor="vldl_cholesterol">
                                VLDL Cholesterol <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="vldl_cholesterol"
                                    name="vldl_cholesterol"
                                    placeholder="e.g. 25"
                                    defaultValue={lipidProfile?.vldl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['vldl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>mg/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['vldl_cholesterol']} />
                        </Field>

                        <Field data-invalid={!!errors['triglycerides']}>
                            <FieldLabel htmlFor="triglycerides">
                                Triglycerides <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="triglycerides"
                                    name="triglycerides"
                                    placeholder="e.g. 150"
                                    defaultValue={lipidProfile?.triglycerides}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['triglycerides']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>mg/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['triglycerides']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={lipidProfile?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-lipid-profile" type="submit" disabled={processing}>
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

export default LipidProfileForm;
