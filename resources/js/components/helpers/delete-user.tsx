import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { Field, FieldLabel } from '@/components//ui/field';
import Heading from '@/components/common/heading';
import InputError from '@/components/helpers/input-error';
import PasswordInput from '@/components/helpers/password-input';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { useRef } from 'react';

export default function DeleteUser() {
    const { __ } = lang();
    const passwordInput = useRef<HTMLInputElement>(null);

    return (
        <div className="space-y-6">
            <Heading
                variant="small"
                title={__('settings_pages.profile_page.delete_account_section.title')}
                description={__('settings_pages.profile_page.delete_account_section.description')}
            />
            <div className="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10">
                <div className="relative space-y-0.5 text-red-600 dark:text-red-100">
                    <p className="font-medium">
                        {__('settings_pages.profile_page.delete_account_section.warning_title')}
                    </p>
                    <p className="text-sm">
                        {__('settings_pages.profile_page.delete_account_section.warning_description')}
                    </p>
                </div>

                <Dialog>
                    <DialogTrigger asChild>
                        <Button type="button" variant="destructive" data-test="delete-user-button">
                            {__('settings_pages.profile_page.delete_account_section.form.submit')}
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogTitle>
                            {__('settings_pages.profile_page.delete_account_section.modal.title')}
                        </DialogTitle>
                        <DialogDescription>
                            {__('settings_pages.profile_page.delete_account_section.modal.description')}
                        </DialogDescription>

                        <Form
                            {...ProfileController.destroy.form()}
                            options={{ preserveScroll: true }}
                            onError={() => passwordInput.current?.focus()}
                            disableWhileProcessing
                            resetOnSuccess
                            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
                        >
                            {({ resetAndClearErrors, errors }) => (
                                <>
                                    <Field data-invalid={!!errors['password']}>
                                        <FieldLabel htmlFor="password" className="sr-only">
                                            {__('settings_pages.profile_page.delete_account_section.form.password')}
                                        </FieldLabel>

                                        <PasswordInput
                                            id="password"
                                            name="password"
                                            ref={passwordInput}
                                            placeholder={__(
                                                'settings_pages.profile_page.delete_account_section.form.password_placeholder',
                                            )}
                                            autoComplete="current-password"
                                            aria-invalid={!!errors['password']}
                                        />

                                        <InputError message={errors['password']} />
                                    </Field>

                                    <DialogFooter className="gap-2">
                                        <DialogClose asChild>
                                            <Button
                                                type="button"
                                                variant="secondary"
                                                onClick={() => resetAndClearErrors()}
                                            >
                                                {__('settings_pages.profile_page.delete_account_section.form.cancel')}
                                            </Button>
                                        </DialogClose>

                                        <Button
                                            type="submit"
                                            variant="destructive"
                                            data-test="confirm-delete-user-button"
                                        >
                                            {__('settings_pages.profile_page.delete_account_section.form.submit')}
                                        </Button>
                                    </DialogFooter>
                                </>
                            )}
                        </Form>
                    </DialogContent>
                </Dialog>
            </div>
        </div>
    );
}
