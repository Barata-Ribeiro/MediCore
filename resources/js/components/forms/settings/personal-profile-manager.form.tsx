import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/common/heading';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import type { Profile } from '@/types/application/profile';
import { Transition } from '@headlessui/react';
import { Form, usePage } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

type ProfilePayload = {
    profile: Profile | null;
};

export default function PersonalProfileManagerForm() {
    const { profile } = usePage<ProfilePayload>().props;

    return (
        <div className="space-y-6">
            <Heading variant="small" title="Personal Information" description="Update your personal details" />

            <Form
                {...ProfileController.update.form()}
                options={{ preserveScroll: true }}
                disableWhileProcessing
                className="space-y-6 inert:pointer-events-none inert:grayscale-100"
            >
                {({ processing, recentlySuccessful, errors }) => (
                    <Fragment>
                        <FieldGroup className="grid gap-4 sm:grid-cols-2">
                            <Field data-invalid={!!errors['first_name']}>
                                <FieldLabel htmlFor="first_name">
                                    First name <RequiredIndicator />
                                </FieldLabel>
                                <Input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    placeholder="John/Jane"
                                    defaultValue={profile?.first_name}
                                    aria-invalid={!!errors['first_name']}
                                    aria-describedby={errors['first_name'] ? 'first_name-error' : undefined}
                                    required
                                    aria-required
                                />
                                <InputError message={errors['first_name']} />
                            </Field>
                            <Field data-invalid={!!errors['last_name']}>
                                <FieldLabel htmlFor="last_name">
                                    Last name <RequiredIndicator />
                                </FieldLabel>
                                <Input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    placeholder="Doe"
                                    defaultValue={profile?.last_name}
                                    aria-invalid={!!errors['last_name']}
                                    aria-describedby={errors['last_name'] ? 'last_name-error' : undefined}
                                    required
                                    aria-required
                                />
                                <InputError message={errors['last_name']} />
                            </Field>
                        </FieldGroup>

                        <Field data-invalid={!!errors['biography']}>
                            <FieldLabel htmlFor="biography">Biography</FieldLabel>
                            <Textarea
                                id="biography"
                                name="biography"
                                placeholder="Write something about yourself..."
                                rows={4}
                                defaultValue={profile?.bio ?? ''}
                                aria-invalid={!!errors['biography']}
                                aria-describedby={errors['biography'] ? 'biography-error' : undefined}
                            />
                            <InputError message={errors['biography']} />

                            <FieldDescription>
                                A brief description about yourself that will help us know a little more about you.
                            </FieldDescription>
                        </Field>

                        <FieldGroup className="grid gap-4 sm:grid-cols-2">
                            <Field data-invalid={!!errors['birth_date']}>
                                <FieldLabel htmlFor="birth_date">Birth date</FieldLabel>
                                <DatePicker id="birth_date" name="birth_date" defaultValue={profile?.birth_date} />
                                <InputError message={errors['birth_date']} />
                            </Field>
                            <Field data-invalid={!!errors['phone_number']}>
                                <FieldLabel htmlFor="phone_number">Phone number</FieldLabel>
                                <Input
                                    type="tel"
                                    id="phone_number"
                                    name="phone_number"
                                    placeholder="+1 (555) 123-4567"
                                    defaultValue={profile?.phone_number ?? ''}
                                    aria-invalid={!!errors['phone_number']}
                                    aria-describedby={errors['phone_number'] ? 'phone_number-error' : undefined}
                                />
                                <InputError message={errors['phone_number']} />
                            </Field>
                        </FieldGroup>
                        <Field data-invalid={!!errors['address']}>
                            <FieldLabel htmlFor="address">Address</FieldLabel>
                            <Input
                                type="text"
                                id="address"
                                name="address"
                                placeholder="123 Main St"
                                defaultValue={profile?.address ?? ''}
                                aria-invalid={!!errors['address']}
                                aria-describedby={errors['address'] ? 'address-error' : undefined}
                            />
                            <InputError message={errors['address']} />
                        </Field>

                        <FieldGroup className="grid gap-4 sm:grid-cols-2">
                            <Field data-invalid={!!errors['sex']}>
                                <FieldLabel htmlFor="sex">Sex</FieldLabel>
                                <Select
                                    name="sex"
                                    defaultValue={profile?.sex ?? undefined}
                                    aria-describedby={errors['sex'] ? 'sex-error' : undefined}
                                >
                                    <SelectTrigger aria-invalid={!!errors['sex']}>
                                        <SelectValue placeholder="Select your sex" />
                                    </SelectTrigger>
                                    <SelectContent position="item-aligned">
                                        <SelectGroup>
                                            <SelectItem value="male">Male</SelectItem>
                                            <SelectItem value="female">Female</SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </Field>

                            <Field data-invalid={!!errors['gender_identity']}>
                                <FieldLabel htmlFor="gender_identity">Gender identity</FieldLabel>
                                <Input
                                    type="text"
                                    id="gender_identity"
                                    name="gender_identity"
                                    placeholder="e.g. Non-binary, Genderqueer etc."
                                    defaultValue={profile?.gender_identity ?? ''}
                                    aria-invalid={!!errors['gender_identity']}
                                    aria-describedby={errors['gender_identity'] ? 'gender_identity-error' : undefined}
                                />
                                <InputError message={errors['gender_identity']} />
                            </Field>
                        </FieldGroup>

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
