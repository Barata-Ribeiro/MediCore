import VitaminB12Controller from '@/actions/App/Http/Controllers/Exams/VitaminB12Controller';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { VitaminB12 } from '@/types/application/exams/vitamin-b12';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    vitaminB12?: VitaminB12;
};

const VitaminB12Form = memo<Readonly<Props>>(({ vitaminB12 }) => {
    const { __ } = lang();
    const isEditMode = vitaminB12 && vitaminB12 !== null;

    const formRoute = isEditMode ? VitaminB12Controller.update.form(vitaminB12.id) : VitaminB12Controller.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['vitamin_b12_level']}>
                        <FieldLabel htmlFor="vitamin_b12_level">
                            {__('vitamin_b12_pages.form.vitamin_b12')} <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="vitamin_b12_level"
                                name="vitamin_b12_level"
                                placeholder={__('vitamin_b12_pages.form.vitamin_b12_placeholder')}
                                defaultValue={vitaminB12?.vitamin_b12_level}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['vitamin_b12_level']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>{__('vitamin_b12_pages.shared.unit')}</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['vitamin_b12_level']} />
                    </Field>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('vitamin_b12_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={vitaminB12?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-vitamin-b12" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('vitamin_b12_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default VitaminB12Form;
