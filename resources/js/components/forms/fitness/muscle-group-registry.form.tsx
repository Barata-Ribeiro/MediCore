import MuscleGroupController from '@/actions/App/Http/Controllers/Fitness/MuscleGroupController';
import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    muscleGroup?: CatalogMuscleGroup | null;
    closeAction: () => void;
};

const MuscleGroupRegistryForm = memo<Readonly<Props>>(({ muscleGroup, closeAction }) => {
    const { __ } = lang();

    const isEditMode = muscleGroup && muscleGroup !== null;
    const formRoute = isEditMode
        ? MuscleGroupController.update.form(muscleGroup.id)
        : MuscleGroupController.store.form();

    return (
        <Form
            {...formRoute}
            onSuccess={closeAction}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <Field data-invalid={!!errors['name']}>
                        <FieldLabel htmlFor="name">{__('muscle_group_pages.form.name')}</FieldLabel>
                        <Input
                            id="name"
                            name="name"
                            placeholder={__('muscle_group_pages.form.name_placeholder')}
                            defaultValue={muscleGroup?.name}
                            aria-invalid={!!errors['name']}
                            required
                        />
                        <InputError message={errors['name']} />
                    </Field>

                    <Button data-test="save-muscle-group" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {isEditMode
                            ? __('muscle_group_pages.form.update_action')
                            : __('muscle_group_pages.form.create_action')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default MuscleGroupRegistryForm;
