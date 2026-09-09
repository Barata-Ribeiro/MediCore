import TgoAndTgpController from '@/actions/App/Http/Controllers/Exams/TgoAndTgpController';
import { Input } from '@/components/ui/input';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { TgoAndTgp } from '@/types/application/exams/tgo-and-tgp';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    tgoAndTgp?: TgoAndTgp;
};

const TgoAndTgpForm = memo<Readonly<Props>>(({ tgoAndTgp }) => {
    const { __ } = lang();
    const isEditMode = tgoAndTgp && tgoAndTgp !== null;

    const formRoute = isEditMode ? TgoAndTgpController.update.form(tgoAndTgp.id) : TgoAndTgpController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="flex flex-col gap-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['tgo_level']}>
                            <FieldLabel htmlFor="tgo_level">
                                {__('tgo_and_tgp_pages.form.tgo_level')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="tgo_level"
                                    name="tgo_level"
                                    placeholder={__('tgo_and_tgp_pages.form.tgo_level_placeholder')}
                                    defaultValue={tgoAndTgp?.tgo_level}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['tgo_level']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('tgo_and_tgp_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['tgo_level']} />
                        </Field>

                        <Field data-invalid={!!errors['tgp_level']}>
                            <FieldLabel htmlFor="tgp_level">
                                {__('tgo_and_tgp_pages.form.tgp_level')} <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="tgp_level"
                                    name="tgp_level"
                                    placeholder={__('tgo_and_tgp_pages.form.tgp_level_placeholder')}
                                    defaultValue={tgoAndTgp?.tgp_level}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['tgp_level']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>{__('tgo_and_tgp_pages.shared.unit')}</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['tgp_level']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            {__('tgo_and_tgp_pages.form.report_date')} <RequiredIndicator />
                        </FieldLabel>
                        <Input
                            type="date"
                            id="report_date"
                            name="report_date"
                            defaultValue={tgoAndTgp?.report_date}
                            aria-invalid={!!errors['report_date']}
                            required
                            aria-required
                        />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-tgo-and-tgp" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden data-icon="inline-start" />
                        </Activity>
                        {__('tgo_and_tgp_pages.form.submit')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default TgoAndTgpForm;
