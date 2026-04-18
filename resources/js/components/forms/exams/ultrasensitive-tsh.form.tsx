import UltrasensitiveTshController from '@/actions/App/Http/Controllers/Exams/UltrasensitiveTshController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { UltrasensitiveTsh } from '@/types/application/exams/ultrasensitive-tsh';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    ultrasensitiveTsh?: UltrasensitiveTsh;
};

const UltrasensitiveTshForm = memo<Readonly<Props>>(({ ultrasensitiveTsh }) => {
    const isEditMode = ultrasensitiveTsh && ultrasensitiveTsh !== null;

    const formRoute = isEditMode
        ? UltrasensitiveTshController.update.form(ultrasensitiveTsh.id)
        : UltrasensitiveTshController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['tsh_level']}>
                        <FieldLabel htmlFor="tsh_level">
                            Ultrasensitive TSH <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="tsh_level"
                                name="tsh_level"
                                placeholder="e.g. 2.50"
                                defaultValue={ultrasensitiveTsh?.tsh_level}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['tsh_level']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>uIU/mL</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['tsh_level']} />
                    </Field>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={ultrasensitiveTsh?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-ultrasensitive-tsh" type="submit" disabled={processing}>
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

export default UltrasensitiveTshForm;
