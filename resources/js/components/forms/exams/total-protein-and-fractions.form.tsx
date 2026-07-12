import TotalProteinsAndFractionsController from '@/actions/App/Http/Controllers/Exams/TotalProteinsAndFractionsController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { TotalProteinsAndFractions } from '@/types/application/exams/total-proteins-and-fractions';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

type Props = {
    totalProteinAndFractions?: TotalProteinsAndFractions;
};

export default function TotalProteinsAndFractionsForm({ totalProteinAndFractions }: Readonly<Props>) {
    const { __ } = lang();
    const isEditMode = totalProteinAndFractions && totalProteinAndFractions !== null;

    const formRoute = isEditMode
        ? TotalProteinsAndFractionsController.update.form(totalProteinAndFractions.id)
        : TotalProteinsAndFractionsController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['total_proteins']}>
                        <FieldLabel htmlFor="total_proteins">
                            {__('total_protein_and_fractions_pages.form.total_proteins')} <RequiredIndicator />
                        </FieldLabel>
                        <InputGroup>
                            <InputGroupInput
                                type="number"
                                id="total_proteins"
                                name="total_proteins"
                                placeholder={__('total_protein_and_fractions_pages.form.total_proteins_placeholder')}
                                defaultValue={totalProteinAndFractions?.total_proteins}
                                min={0}
                                step={0.01}
                                aria-invalid={!!errors['total_proteins']}
                                required
                                aria-required
                            />
                            <InputGroupAddon align="inline-end">
                                <InputGroupText>{__('total_protein_and_fractions_pages.shared.unit')}</InputGroupText>
                            </InputGroupAddon>
                        </InputGroup>
                        <InputError message={errors['total_proteins']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['albumin']}>
                            <FieldLabel htmlFor="albumin">
                                {__('total_protein_and_fractions_pages.form.albumin')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="albumin"
                                    name="albumin"
                                    placeholder={__('total_protein_and_fractions_pages.form.albumin_placeholder')}
                                    defaultValue={totalProteinAndFractions?.albumin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['albumin']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>
                                        {__('total_protein_and_fractions_pages.shared.unit')}
                                    </InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['albumin']} />
                        </Field>

                        <Field data-invalid={!!errors['globulin']}>
                            <FieldLabel htmlFor="globulin">
                                {__('total_protein_and_fractions_pages.form.globulin')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="globulin"
                                    name="globulin"
                                    placeholder={__('total_protein_and_fractions_pages.form.globulin_placeholder')}
                                    defaultValue={totalProteinAndFractions?.globulin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['globulin']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>
                                        {__('total_protein_and_fractions_pages.shared.unit')}
                                    </InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['globulin']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('total_protein_and_fractions_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker
                            id="report_date"
                            name="report_date"
                            defaultValue={totalProteinAndFractions?.report_date}
                        />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-total-protein-and-fractions" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {__('total_protein_and_fractions_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
}
