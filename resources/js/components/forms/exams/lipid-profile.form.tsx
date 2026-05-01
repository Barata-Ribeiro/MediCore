import LipidProfileController from '@/actions/App/Http/Controllers/Exams/LipidProfileController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { LipidProfile } from '@/types/application/exams/lipid-profile';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    lipidProfile?: LipidProfile;
};

export default function LipidProfileForm({ lipidProfile }: Readonly<Props>) {
    const { __ } = lang();
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
                            {__('lipid_profile_pages.form.total_cholesterol')} <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="total_cholesterol"
                                name="total_cholesterol"
                                placeholder={__('lipid_profile_pages.form.total_cholesterol_placeholder')}
                                defaultValue={lipidProfile?.total_cholesterol}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['total_cholesterol']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>{__('lipid_profile_pages.shared.unit')}</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['total_cholesterol']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['hdl_cholesterol']}>
                            <FieldLabel htmlFor="hdl_cholesterol">
                                {__('lipid_profile_pages.form.hdl_cholesterol')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="hdl_cholesterol"
                                    name="hdl_cholesterol"
                                    placeholder={__('lipid_profile_pages.form.hdl_cholesterol_placeholder')}
                                    defaultValue={lipidProfile?.hdl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['hdl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('lipid_profile_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['hdl_cholesterol']} />
                        </Field>

                        <Field data-invalid={!!errors['ldl_cholesterol']}>
                            <FieldLabel htmlFor="ldl_cholesterol">
                                {__('lipid_profile_pages.form.ldl_cholesterol')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="ldl_cholesterol"
                                    name="ldl_cholesterol"
                                    placeholder={__('lipid_profile_pages.form.ldl_cholesterol_placeholder')}
                                    defaultValue={lipidProfile?.ldl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['ldl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('lipid_profile_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['ldl_cholesterol']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['vldl_cholesterol']}>
                            <FieldLabel htmlFor="vldl_cholesterol">
                                {__('lipid_profile_pages.form.vldl_cholesterol')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="vldl_cholesterol"
                                    name="vldl_cholesterol"
                                    placeholder={__('lipid_profile_pages.form.vldl_cholesterol_placeholder')}
                                    defaultValue={lipidProfile?.vldl_cholesterol}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['vldl_cholesterol']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('lipid_profile_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['vldl_cholesterol']} />
                        </Field>

                        <Field data-invalid={!!errors['triglycerides']}>
                            <FieldLabel htmlFor="triglycerides">
                                {__('lipid_profile_pages.form.triglycerides')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="triglycerides"
                                    name="triglycerides"
                                    placeholder={__('lipid_profile_pages.form.triglycerides_placeholder')}
                                    defaultValue={lipidProfile?.triglycerides}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['triglycerides']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('lipid_profile_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['triglycerides']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('lipid_profile_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={lipidProfile?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-lipid-profile" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('lipid_profile_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
}
