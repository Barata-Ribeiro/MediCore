import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import { useIsomorphicLayoutEffect } from '@/hooks/use-isomorphic-layout-effect';
import { useIsMounted } from '@/hooks/use-mounted';
import type { Profile } from '@/types/application/profile';
import { lang } from '@erag/lang-sync-inertia/react';
import { Transition } from '@headlessui/react';
import { Form, usePage } from '@inertiajs/react';
import { Activity } from 'react';
import { Fragment } from 'react/jsx-runtime';

type ProfilePayload = {
    profile: Profile | null;
};

export default function PersonalProfileManagerForm() {
    const { __ } = lang();
    const { profile } = usePage<ProfilePayload>().props;
    const isMounted = useIsMounted();

    useIsomorphicLayoutEffect(() => {
        if (!isMounted) {
            return;
        }
    }, []);

    return (
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
                                {__('settings_pages.profile_page.personal_profile_manager.form.first_name')}{' '}
                                <RequiredIndicator />
                            </FieldLabel>
                            <Input
                                type="text"
                                id="first_name"
                                name="first_name"
                                placeholder={__(
                                    'settings_pages.profile_page.personal_profile_manager.form.first_name_placeholder',
                                )}
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
                                {__('settings_pages.profile_page.personal_profile_manager.form.last_name')}{' '}
                                <RequiredIndicator />
                            </FieldLabel>
                            <Input
                                type="text"
                                id="last_name"
                                name="last_name"
                                placeholder={__(
                                    'settings_pages.profile_page.personal_profile_manager.form.last_name_placeholder',
                                )}
                                defaultValue={profile?.last_name}
                                aria-invalid={!!errors['last_name']}
                                aria-describedby={errors['last_name'] ? 'last_name-error' : undefined}
                                required
                                aria-required
                            />
                            <InputError message={errors['last_name']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['bio']}>
                        <FieldLabel htmlFor="bio">
                            {__('settings_pages.profile_page.personal_profile_manager.form.biography')}
                        </FieldLabel>
                        <Textarea
                            id="bio"
                            name="bio"
                            placeholder={__(
                                'settings_pages.profile_page.personal_profile_manager.form.biography_placeholder',
                            )}
                            rows={4}
                            defaultValue={profile?.bio ?? ''}
                            aria-invalid={!!errors['bio']}
                            aria-describedby={errors['bio'] ? 'bio-error' : undefined}
                        />
                        <InputError message={errors['bio']} />

                        <FieldDescription>
                            {__('settings_pages.profile_page.personal_profile_manager.form.biography_description')}
                        </FieldDescription>
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['birth_date']}>
                            <FieldLabel htmlFor="birth_date">
                                {__('settings_pages.profile_page.personal_profile_manager.form.birth_date')}
                            </FieldLabel>
                            <DatePicker id="birth_date" name="birth_date" defaultValue={profile?.birth_date} />
                            <InputError message={errors['birth_date']} />
                        </Field>
                        <Field data-invalid={!!errors['phone_number']}>
                            <FieldLabel htmlFor="phone_number">
                                {__('settings_pages.profile_page.personal_profile_manager.form.phone_number')}
                            </FieldLabel>
                            <Input
                                type="tel"
                                id="phone_number"
                                name="phone_number"
                                placeholder={__(
                                    'settings_pages.profile_page.personal_profile_manager.form.phone_number_placeholder',
                                )}
                                defaultValue={profile?.phone_number ?? ''}
                                aria-invalid={!!errors['phone_number']}
                                aria-describedby={errors['phone_number'] ? 'phone_number-error' : undefined}
                            />
                            <InputError message={errors['phone_number']} />
                        </Field>
                    </FieldGroup>
                    <Field data-invalid={!!errors['address']}>
                        <FieldLabel htmlFor="address">
                            {__('settings_pages.profile_page.personal_profile_manager.form.address')}
                        </FieldLabel>
                        <Input
                            type="text"
                            id="address"
                            name="address"
                            placeholder={__(
                                'settings_pages.profile_page.personal_profile_manager.form.address_placeholder',
                            )}
                            defaultValue={profile?.address ?? ''}
                            aria-invalid={!!errors['address']}
                            aria-describedby={errors['address'] ? 'address-error' : undefined}
                        />
                        <InputError message={errors['address']} />
                    </Field>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2">
                        <Field data-invalid={!!errors['sex']}>
                            <FieldLabel htmlFor="sex">
                                {__('settings_pages.profile_page.personal_profile_manager.form.sex')}
                            </FieldLabel>
                            <Select
                                name="sex"
                                defaultValue={profile?.sex ?? undefined}
                                aria-describedby={errors['sex'] ? 'sex-error' : undefined}
                            >
                                <SelectTrigger aria-invalid={!!errors['sex']}>
                                    <SelectValue
                                        placeholder={__(
                                            'settings_pages.profile_page.personal_profile_manager.form.sex_label',
                                        )}
                                    />
                                </SelectTrigger>
                                <SelectContent position="item-aligned">
                                    <SelectGroup>
                                        <SelectItem value="male">
                                            {__('settings_pages.profile_page.personal_profile_manager.form.sex_male')}
                                        </SelectItem>
                                        <SelectItem value="female">
                                            {__('settings_pages.profile_page.personal_profile_manager.form.sex_female')}
                                        </SelectItem>
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </Field>

                        <Field data-invalid={!!errors['gender_identity']}>
                            <FieldLabel htmlFor="gender_identity">
                                {__('settings_pages.profile_page.personal_profile_manager.form.gender_identity')}
                            </FieldLabel>
                            <Input
                                type="text"
                                id="gender_identity"
                                name="gender_identity"
                                placeholder={__(
                                    'settings_pages.profile_page.personal_profile_manager.form.gender_identity_placeholder',
                                )}
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
                            {__('settings_pages.profile_page.personal_profile_manager.form.submit')}
                        </Button>

                        <Transition
                            show={recentlySuccessful}
                            enter="transition ease-in-out"
                            enterFrom="opacity-0"
                            leave="transition ease-in-out"
                            leaveTo="opacity-0"
                        >
                            <p className="text-sm text-neutral-600">
                                {__('settings_pages.profile_page.personal_profile_manager.form.success_message')}
                            </p>
                        </Transition>
                    </div>
                </Fragment>
            )}
        </Form>
    );
}
