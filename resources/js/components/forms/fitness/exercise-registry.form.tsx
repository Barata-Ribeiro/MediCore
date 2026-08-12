import ExerciseController from '@/actions/App/Http/Controllers/Fitness/ExerciseController';
import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Field, FieldDescription, FieldLabel } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import type { CatalogExercise, CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    exercise?: CatalogExercise | null;
    muscleGroups: CatalogMuscleGroup[];
    closeAction: () => void;
};

const ExerciseRegistryForm = memo<Readonly<Props>>(({ exercise, muscleGroups, closeAction }) => {
    const { __ } = lang();

    const isEditMode = exercise && exercise !== null;
    const formRoute = isEditMode ? ExerciseController.update.form(exercise.id) : ExerciseController.store.form();
    const selectedMuscleGroupIds = exercise?.muscle_groups.map((muscleGroup) => muscleGroup.id) ?? [];

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
                        <FieldLabel htmlFor="name">{__('exercise_pages.form.name')}</FieldLabel>
                        <Input
                            id="name"
                            name="name"
                            placeholder={__('exercise_pages.form.name_placeholder')}
                            defaultValue={exercise?.name}
                            aria-invalid={!!errors['name']}
                            required
                        />
                        <InputError message={errors['name']} />
                    </Field>

                    <Field data-invalid={!!errors['description']}>
                        <FieldLabel htmlFor="description">{__('exercise_pages.form.description_field')}</FieldLabel>
                        <Textarea
                            id="description"
                            name="description"
                            placeholder={__('exercise_pages.form.description_placeholder')}
                            defaultValue={exercise?.description ?? undefined}
                            aria-invalid={!!errors['description']}
                        />
                        <InputError message={errors['description']} />
                    </Field>

                    <Field data-invalid={!!errors['video_url']}>
                        <FieldLabel htmlFor="video_url">{__('exercise_pages.form.video_url')}</FieldLabel>
                        <Input
                            id="video_url"
                            name="video_url"
                            type="url"
                            placeholder={__('exercise_pages.form.video_url_placeholder')}
                            defaultValue={exercise?.video_url ?? undefined}
                            aria-invalid={!!errors['video_url']}
                        />
                        <InputError message={errors['video_url']} />
                    </Field>

                    <Field data-invalid={!!errors['muscle_group_ids']}>
                        <FieldLabel>{__('exercise_pages.form.muscle_groups')}</FieldLabel>
                        <FieldDescription>{__('exercise_pages.form.muscle_groups_hint')}</FieldDescription>
                        {/* TODO: Implement better UI for muscle group selection */}
                        <div className="grid gap-2 sm:grid-cols-2">
                            {muscleGroups.map((muscleGroup) => (
                                <label
                                    key={muscleGroup.id}
                                    className="flex items-center gap-2 rounded-xl border px-3 py-2 text-sm"
                                >
                                    <input
                                        type="checkbox"
                                        name="muscle_group_ids[]"
                                        value={muscleGroup.id}
                                        defaultChecked={selectedMuscleGroupIds.includes(muscleGroup.id)}
                                    />
                                    <span>{muscleGroup.name}</span>
                                </label>
                            ))}
                        </div>
                        <InputError message={errors['muscle_group_ids']} />
                    </Field>

                    <Button data-test="save-exercise" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        {isEditMode ? __('exercise_pages.form.update_action') : __('exercise_pages.form.create_action')}
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default ExerciseRegistryForm;
