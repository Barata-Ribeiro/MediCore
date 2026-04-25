import MedicalFileController from '@/actions/App/Http/Controllers/Settings/MedicalFileController';
import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLabel, FieldLegend, FieldSet } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import type { MedicalFile } from '@/types/application/medical-file';
import { BloodType, bloodTypeLabel } from '@/types/application/medical-file';
import { lang } from '@erag/lang-sync-inertia/react';
import { Transition } from '@headlessui/react';
import { Form, usePage } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

type MedicalFilePayload = {
    file: MedicalFile;
};

export default function MedicalFileManagerForm() {
    const { __ } = lang();

    const { file } = usePage<MedicalFilePayload>().props;

    return (
        <Form
            {...MedicalFileController.update.form()}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, recentlySuccessful, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['blood_type']}>
                        <FieldLabel htmlFor="blood_type">
                            {__('settings_pages.medical_file_page.form.blood_type')}
                        </FieldLabel>
                        <Select
                            name="blood_type"
                            defaultValue={file?.blood_type ?? undefined}
                            aria-describedby={errors['blood_type'] ? 'error-blood_type' : undefined}
                        >
                            <SelectTrigger aria-invalid={!!errors['blood_type']}>
                                <SelectValue
                                    placeholder={__('settings_pages.medical_file_page.form.blood_type_placeholder')}
                                />
                            </SelectTrigger>
                            <SelectContent position="item-aligned">
                                <SelectGroup>
                                    {Object.values(BloodType).map((type) => (
                                        <SelectItem key={type} value={type}>
                                            {bloodTypeLabel(type)}
                                        </SelectItem>
                                    ))}
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </Field>

                    <Field data-invalid={!!errors['allergies']}>
                        <FieldLabel htmlFor="textarea-allergies">
                            {__('settings_pages.medical_file_page.form.allergies')}
                        </FieldLabel>
                        <Textarea
                            id="textarea-allergies"
                            name="allergies"
                            placeholder={__('settings_pages.medical_file_page.form.allergies_placeholder')}
                            rows={4}
                            defaultValue={file?.allergies ?? ''}
                            aria-invalid={!!errors['allergies']}
                            aria-describedby={errors['allergies'] ? 'error-allergies' : undefined}
                        />
                        <InputError message={errors['allergies']} />
                    </Field>

                    <Field data-invalid={!!errors['diseases']}>
                        <FieldLabel htmlFor="textarea-diseases">
                            {__('settings_pages.medical_file_page.form.diseases')}
                        </FieldLabel>
                        <Textarea
                            id="textarea-diseases"
                            name="diseases"
                            placeholder={__('settings_pages.medical_file_page.form.diseases_placeholder')}
                            rows={4}
                            defaultValue={file?.diseases ?? ''}
                            aria-invalid={!!errors['diseases']}
                            aria-describedby={errors['diseases'] ? 'error-diseases' : undefined}
                        />
                        <InputError message={errors['diseases']} />
                    </Field>

                    <Field data-invalid={!!errors['medications']}>
                        <FieldLabel htmlFor="textarea-medications">
                            {__('settings_pages.medical_file_page.form.medications')}
                        </FieldLabel>
                        <Textarea
                            id="textarea-medications"
                            name="medications"
                            placeholder={__('settings_pages.medical_file_page.form.medications_placeholder')}
                            rows={4}
                            defaultValue={file?.medications ?? ''}
                            aria-invalid={!!errors['medications']}
                            aria-describedby={errors['medications'] ? 'error-medications' : undefined}
                        />
                        <InputError message={errors['medications']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['height']}>
                            <FieldLabel htmlFor="input-height">
                                {__('settings_pages.medical_file_page.form.height')}
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    id="input-height"
                                    name="height"
                                    type="number"
                                    min={0}
                                    placeholder={__('settings_pages.medical_file_page.form.height_placeholder')}
                                    defaultValue={file?.height ?? ''}
                                    aria-invalid={!!errors['height']}
                                    aria-describedby={errors['height'] ? 'error-height' : undefined}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>Cm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['height']} />
                        </Field>
                        <Field data-invalid={!!errors['weight']}>
                            <FieldLabel htmlFor="input-weight">
                                {__('settings_pages.medical_file_page.form.weight')}
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    id="input-weight"
                                    name="weight"
                                    type="number"
                                    placeholder={__('settings_pages.medical_file_page.form.weight_placeholder')}
                                    min={0}
                                    defaultValue={file?.weight ?? ''}
                                    aria-invalid={!!errors['weight']}
                                    aria-describedby={errors['weight'] ? 'error-weight' : undefined}
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>Kg</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['weight']} />
                        </Field>
                    </FieldGroup>

                    <FieldSet>
                        <FieldLegend>
                            {__('settings_pages.medical_file_page.form.emergency_contact_field_legend')}
                        </FieldLegend>
                        <FieldDescription>
                            {__('settings_pages.medical_file_page.form.emergency_contact_field_description')}
                        </FieldDescription>
                        <FieldGroup>
                            <FieldGroup className="grid gap-4 sm:grid-cols-2">
                                <Field data-invalid={!!errors['emergency_contact_name']}>
                                    <FieldLabel htmlFor="input-emergency_contact_name">
                                        {__('settings_pages.medical_file_page.form.emergency_contact_name')}
                                    </FieldLabel>
                                    <Input
                                        id="input-emergency_contact_name"
                                        name="emergency_contact_name"
                                        type="text"
                                        placeholder={__(
                                            'settings_pages.medical_file_page.form.emergency_contact_name_placeholder',
                                        )}
                                        defaultValue={file?.emergency_contact_name ?? ''}
                                        aria-invalid={!!errors['emergency_contact_name']}
                                        aria-describedby={
                                            errors['emergency_contact_name']
                                                ? 'error-emergency_contact_name'
                                                : undefined
                                        }
                                    />
                                    <InputError message={errors['emergency_contact_name']} />
                                </Field>

                                <Field data-invalid={!!errors['emergency_contact_relationship']}>
                                    <FieldLabel htmlFor="input-emergency_contact_relationship">
                                        {__('settings_pages.medical_file_page.form.emergency_contact_relationship')}
                                    </FieldLabel>
                                    <Input
                                        id="input-emergency_contact_relationship"
                                        name="emergency_contact_relationship"
                                        type="text"
                                        placeholder={__(
                                            'settings_pages.medical_file_page.form.emergency_contact_relationship_placeholder',
                                        )}
                                        defaultValue={file?.emergency_contact_relationship ?? ''}
                                        aria-invalid={!!errors['emergency_contact_relationship']}
                                        aria-describedby={
                                            errors['emergency_contact_relationship']
                                                ? 'error-emergency_contact_relationship'
                                                : undefined
                                        }
                                    />
                                    <InputError message={errors['emergency_contact_relationship']} />
                                </Field>
                            </FieldGroup>

                            <Field data-invalid={!!errors['emergency_contact_phone_number']}>
                                <FieldLabel htmlFor="input-emergency_contact_phone_number">
                                    {__('settings_pages.medical_file_page.form.emergency_contact_phone')}
                                </FieldLabel>
                                <Input
                                    id="input-emergency_contact_phone_number"
                                    name="emergency_contact_phone_number"
                                    type="tel"
                                    placeholder={__(
                                        'settings_pages.medical_file_page.form.emergency_contact_phone_placeholder',
                                    )}
                                    defaultValue={file?.emergency_contact_phone_number ?? ''}
                                    aria-invalid={!!errors['emergency_contact_phone_number']}
                                    aria-describedby={
                                        errors['emergency_contact_phone_number']
                                            ? 'error-emergency_contact_phone_number'
                                            : undefined
                                    }
                                />
                                <InputError message={errors['emergency_contact_phone_number']} />
                            </Field>
                        </FieldGroup>
                    </FieldSet>

                    <div className="flex items-center gap-4">
                        <Button data-test="update-profile-button">
                            <Activity mode={processing ? 'visible' : 'hidden'}>
                                <Spinner aria-hidden />
                            </Activity>
                            {__('settings_pages.medical_file_page.form.submit')}
                        </Button>

                        <Transition
                            show={recentlySuccessful}
                            enter="transition ease-in-out"
                            enterFrom="opacity-0"
                            leave="transition ease-in-out"
                            leaveTo="opacity-0"
                        >
                            <p className="text-sm text-neutral-600">
                                {__('settings_pages.medical_file_page.form.success_message')}
                            </p>
                        </Transition>
                    </div>
                </Fragment>
            )}
        </Form>
    );
}
