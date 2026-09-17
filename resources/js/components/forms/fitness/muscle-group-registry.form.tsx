import { store, update } from '@/routes/muscle-groups';
import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { router, useHttp, usePage } from '@inertiajs/react';
import { useModal } from '@inertiaui/modal-react';
import type { FormEvent } from 'react';
import { toast } from 'sonner';

type Props = { muscleGroup?: CatalogMuscleGroup | null; closeAction: () => void };

export default function MuscleGroupRegistryForm({ muscleGroup, closeAction }: Readonly<Props>) {
    const { __ } = lang();
    const modal = useModal();
    const page = usePage();
    const form = useHttp<{ name: string }, { muscleGroup: CatalogMuscleGroup; message: string }>({
        name: muscleGroup?.name ?? '',
    });

    const submit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        try {
            const response = await form.submit(muscleGroup ? update(muscleGroup.id) : store());
            modal?.emit('saved', response.muscleGroup);
            toast.success(response.message);
            closeAction();
            if (page.component === 'fitness/muscle-group/index') {
                router.reload({ only: ['muscleGroups'] });
            }
        } catch {
            toast.error(__('muscle_group_pages.form.save_failed'));
        }
    };

    return (
        <form onSubmit={submit}>
            <FieldGroup>
                <Field data-invalid={!!form.errors.name}>
                    <FieldLabel htmlFor="muscle-group-name">{__('muscle_group_pages.form.name')}</FieldLabel>
                    <Input
                        id="muscle-group-name"
                        value={form.data.name}
                        onChange={(event) => form.setData('name', event.target.value)}
                        placeholder={__('muscle_group_pages.form.name_placeholder')}
                        required
                        maxLength={255}
                        aria-invalid={!!form.errors.name}
                    />
                    <InputError message={form.errors.name} />
                </Field>
                <div className="flex justify-end gap-2">
                    <Button type="button" variant="outline" onClick={closeAction}>
                        {__('muscle_group_pages.form.cancel_action')}
                    </Button>
                    <Button data-test="save-muscle-group" type="submit" disabled={form.processing}>
                        {form.processing && <Spinner data-icon="inline-start" />}
                        {__('muscle_group_pages.form.' + (muscleGroup ? 'update_action' : 'create_action'))}
                    </Button>
                </div>
            </FieldGroup>
        </form>
    );
}
