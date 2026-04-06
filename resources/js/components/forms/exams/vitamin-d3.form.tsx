import VitaminD3Controller from '@/actions/App/Http/Controllers/Exams/VitaminD3Controller';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { VitaminD3 } from '@/types/application/exams/vitamin-d3';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    vitaminD3?: VitaminD3;
};

const VitaminD3Form = memo<Readonly<Props>>(({ vitaminD3 }) => {
    const isEditMode = vitaminD3 && vitaminD3 !== null;

    const formRoute = isEditMode ? VitaminD3Controller.update.form(vitaminD3.id) : VitaminD3Controller.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['twenty_five_hydroxyvitamin_d3']}>
                        <FieldLabel htmlFor="twenty_five_hydroxyvitamin_d3">
                            Vitamin D3 <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="twenty_five_hydroxyvitamin_d3"
                                name="twenty_five_hydroxyvitamin_d3"
                                placeholder="e.g. 30"
                                defaultValue={vitaminD3?.twenty_five_hydroxyvitamin_d3}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['twenty_five_hydroxyvitamin_d3']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>ng/mL</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['twenty_five_hydroxyvitamin_d3']} />
                    </Field>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={vitaminD3?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-vitamin-d3" type="submit" disabled={processing}>
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

export default VitaminD3Form;
