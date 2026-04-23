import UricAcidController from '@/actions/App/Http/Controllers/Exams/UricAcidController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { UricAcid } from '@/types/application/exams/uric-acid';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    uricAcid?: UricAcid;
};

const UricAcidForm = memo<Readonly<Props>>(({ uricAcid }) => {
    const isEditMode = uricAcid && uricAcid !== null;

    const formRoute = isEditMode ? UricAcidController.update.form(uricAcid.id) : UricAcidController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['uric_acid_level']}>
                        <FieldLabel htmlFor="uric_acid_level">
                            Uric Acid Level <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="uric_acid_level"
                                name="uric_acid_level"
                                placeholder="e.g. 5.5"
                                defaultValue={uricAcid?.uric_acid_level}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['uric_acid_level']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>mg/dL</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['uric_acid_level']} />
                    </Field>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={uricAcid?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-uric-acid" type="submit" disabled={processing}>
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

export default UricAcidForm;
