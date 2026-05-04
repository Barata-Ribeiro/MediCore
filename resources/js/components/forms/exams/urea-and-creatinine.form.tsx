import UreaAndCreatinineController from '@/actions/App/Http/Controllers/Exams/UreaAndCreatinineController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { UreaAndCreatinine } from '@/types/application/exams/urea-and-creatinine';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    ureaAndCreatinine?: UreaAndCreatinine;
};

const UreaAndCreatinineForm = memo<Readonly<Props>>(({ ureaAndCreatinine }) => {
    const { __ } = lang();
    const isEditMode = ureaAndCreatinine && ureaAndCreatinine !== null;

    const formRoute = isEditMode
        ? UreaAndCreatinineController.update.form(ureaAndCreatinine.id)
        : UreaAndCreatinineController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['urea_level']}>
                            <FieldLabel htmlFor="urea_level">
                                {__('urea_and_creatinine_pages.form.urea_level')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="urea_level"
                                    name="urea_level"
                                    placeholder={__('urea_and_creatinine_pages.form.urea_level_placeholder')}
                                    defaultValue={ureaAndCreatinine?.urea_level}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['urea_level']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('urea_and_creatinine_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['urea_level']} />
                        </Field>

                        <Field data-invalid={!!errors['creatinine_level']}>
                            <FieldLabel htmlFor="creatinine_level">
                                {__('urea_and_creatinine_pages.form.creatinine_level')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="creatinine_level"
                                    name="creatinine_level"
                                    placeholder={__('urea_and_creatinine_pages.form.creatinine_level_placeholder')}
                                    defaultValue={ureaAndCreatinine?.creatinine_level}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['creatinine_level']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('urea_and_creatinine_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['creatinine_level']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('urea_and_creatinine_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={ureaAndCreatinine?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-urea-and-creatinine" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('urea_and_creatinine_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default UreaAndCreatinineForm;
