import MedicalFileController from '@/actions/App/Http/Controllers/Settings/MedicalFileController';
import Heading from '@/components/common/heading';
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
import { Transition } from '@headlessui/react';
import { Form, usePage } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

type MedicalFilePayload = {
    file: MedicalFile;
};

export default function MedicalFileManagerForm() {
    const { file } = usePage<MedicalFilePayload>().props;

    return (
        <div className="space-y-6">
            <Heading variant="small" title="Medical File" description="Manage your medical information" />

            <Form
                {...MedicalFileController.update.form()}
                options={{ preserveScroll: true }}
                disableWhileProcessing
                className="space-y-6 inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, recentlySuccessful, errors }) => (
                    <Fragment>
                        <Field data-invalid={!!errors['blood_type']}>
                            <FieldLabel htmlFor="blood_type">Blood Type</FieldLabel>
                            <Select
                                name="blood_type"
                                defaultValue={file?.blood_type ?? undefined}
                                aria-describedby={errors['blood_type'] ? 'error-blood_type' : undefined}
                            >
                                <SelectTrigger aria-invalid={!!errors['blood_type']}>
                                    <SelectValue placeholder="Select your blood type" />
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
                            <FieldLabel htmlFor="textarea-allergies">Allergies</FieldLabel>
                            <Textarea
                                id="textarea-allergies"
                                placeholder="e.g. peanuts, pollen"
                                rows={4}
                                defaultValue={file?.allergies ?? ''}
                                aria-invalid={!!errors['allergies']}
                                aria-describedby={errors['allergies'] ? 'error-allergies' : undefined}
                            />
                            <InputError message={errors['allergies']} />
                        </Field>

                        <Field data-invalid={!!errors['diseases']}>
                            <FieldLabel htmlFor="textarea-diseases">Diseases</FieldLabel>
                            <Textarea
                                id="textarea-diseases"
                                placeholder="e.g. diabetes, hypertension"
                                rows={4}
                                defaultValue={file?.diseases ?? ''}
                                aria-invalid={!!errors['diseases']}
                                aria-describedby={errors['diseases'] ? 'error-diseases' : undefined}
                            />
                            <InputError message={errors['diseases']} />
                        </Field>

                        <Field data-invalid={!!errors['medications']}>
                            <FieldLabel htmlFor="textarea-medications">Medications</FieldLabel>
                            <Textarea
                                id="textarea-medications"
                                placeholder="e.g. insulin, aspirin"
                                rows={4}
                                defaultValue={file?.medications ?? ''}
                                aria-invalid={!!errors['medications']}
                                aria-describedby={errors['medications'] ? 'error-medications' : undefined}
                            />
                            <InputError message={errors['medications']} />
                        </Field>

                        <FieldGroup className="grid grid-cols-2 gap-4">
                            <Field data-invalid={!!errors['height']}>
                                <FieldLabel htmlFor="input-height">Height</FieldLabel>
                                <InputGroup>
                                    <InputGroupInput
                                        id="input-height"
                                        type="number"
                                        min={0}
                                        placeholder="e.g. 170"
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
                                <FieldLabel htmlFor="input-weight">Weight</FieldLabel>
                                <InputGroup>
                                    <InputGroupInput
                                        id="input-weight"
                                        type="number"
                                        placeholder="e.g. 65"
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
                            <FieldLegend>Emergency Contact</FieldLegend>
                            <FieldDescription>
                                Provide the name, phone number and relationship of a person to contact in case of an
                                emergency.
                            </FieldDescription>
                            <FieldGroup>
                                <FieldGroup className="grid grid-cols-2 gap-4">
                                    <Field data-invalid={!!errors['emergency_contact_name']}>
                                        <FieldLabel htmlFor="input-emergency_contact_name">Name</FieldLabel>
                                        <Input
                                            id="input-emergency_contact_name"
                                            type="text"
                                            placeholder="e.g. John Doe"
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
                                            Relationship
                                        </FieldLabel>
                                        <Input
                                            id="input-emergency_contact_relationship"
                                            type="text"
                                            placeholder="e.g. Father, Spouse"
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
                                    <FieldLabel htmlFor="input-emergency_contact_phone_number">Phone</FieldLabel>
                                    <Input
                                        id="input-emergency_contact_phone_number"
                                        type="tel"
                                        placeholder="+1 (555) 123-4567"
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
                                Save
                            </Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition>
                        </div>
                    </Fragment>
                )}
            </Form>
        </div>
    );
}
