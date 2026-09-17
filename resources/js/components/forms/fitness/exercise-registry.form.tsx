import { store, update } from '@/routes/exercises';
import { create as createMuscleGroup } from '@/routes/muscle-groups';
import InputError from '@/components/helpers/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Field, FieldDescription, FieldGroup, FieldLabel, FieldLegend, FieldSet } from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import type { CatalogExercise, CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { router, useHttp, usePage } from '@inertiajs/react';
import { ModalLink, useModal } from '@inertiaui/modal-react';
import { PlusIcon } from 'lucide-react';
import { useState, type FormEvent } from 'react';
import { toast } from 'sonner';

type Props = { exercise?: CatalogExercise | null; muscleGroups: CatalogMuscleGroup[]; closeAction: () => void };
type ExerciseData = { name: string; description: string; video_url: string; muscle_group_ids: number[] };

export default function ExerciseRegistryForm({ exercise, muscleGroups, closeAction }: Readonly<Props>) {
    const { __ } = lang();
    const modal = useModal();
    const page = usePage();
    const [groups, setGroups] = useState(muscleGroups);
    const form = useHttp<ExerciseData, { exercise: CatalogExercise; message: string }>({
        name: exercise?.name ?? '',
        description: exercise?.description ?? '',
        video_url: exercise?.video_url ?? '',
        muscle_group_ids: exercise?.muscle_groups.map((group) => group.id) ?? [],
    });
    const submit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        try {
            const response = await form.submit(exercise ? update(exercise.id) : store());
            modal?.emit('saved', response.exercise);
            toast.success(response.message);
            closeAction();
            if (page.component === 'fitness/exercise/index') {
                router.reload({ only: ['exercises'] });
            }
        } catch {
            toast.error(__('exercise_pages.form.save_failed'));
        }
    };

    return (
        <form onSubmit={submit}>
            <FieldGroup>
                <Field data-invalid={!!form.errors.name}>
                    <FieldLabel htmlFor="catalog-exercise-name">{__('exercise_pages.form.name')}</FieldLabel>
                    <Input
                        id="catalog-exercise-name"
                        value={form.data.name}
                        onChange={(event) => form.setData('name', event.target.value)}
                        placeholder={__('exercise_pages.form.name_placeholder')}
                        required
                        maxLength={255}
                        aria-invalid={!!form.errors.name}
                    />
                    <InputError message={form.errors.name} />
                </Field>
                <FieldSet>
                    <FieldLegend>{__('exercise_pages.form.muscle_groups')}</FieldLegend>
                    <FieldDescription>{__('exercise_pages.form.muscle_groups_hint')}</FieldDescription>
                    <FieldGroup className="grid gap-3 sm:grid-cols-2">
                        {groups.map((group) => (
                            <Field key={group.id} orientation="horizontal">
                                <Checkbox
                                    id={`catalog-group-${group.id}`}
                                    checked={form.data.muscle_group_ids.includes(group.id)}
                                    onCheckedChange={(checked) =>
                                        form.setData(
                                            'muscle_group_ids',
                                            checked
                                                ? [...form.data.muscle_group_ids, group.id]
                                                : form.data.muscle_group_ids.filter((id) => id !== group.id),
                                        )
                                    }
                                />
                                <FieldLabel htmlFor={`catalog-group-${group.id}`}>{group.name}</FieldLabel>
                            </Field>
                        ))}
                    </FieldGroup>
                    <InputError message={form.errors.muscle_group_ids} />
                    <Button
                        type="button"
                        nativeButton={false}
                        variant="outline"
                        className="w-fit"
                        render={
                            <ModalLink
                                children={null}
                                href={createMuscleGroup().url}
                                onSaved={(group: CatalogMuscleGroup) => {
                                    setGroups((current) => [...current, group]);
                                    form.setData('muscle_group_ids', [...form.data.muscle_group_ids, group.id]);
                                }}
                            />
                        }
                    >
                        <PlusIcon data-icon="inline-start" />
                        {__('exercise_pages.form.create_muscle_group')}
                    </Button>
                </FieldSet>
                <Field data-invalid={!!form.errors.description}>
                    <FieldLabel htmlFor="catalog-exercise-description">
                        {__('exercise_pages.form.description_field')}
                    </FieldLabel>
                    <Textarea
                        id="catalog-exercise-description"
                        value={form.data.description}
                        onChange={(event) => form.setData('description', event.target.value)}
                        aria-invalid={!!form.errors.description}
                    />
                    <InputError message={form.errors.description} />
                </Field>
                <Field data-invalid={!!form.errors.video_url}>
                    <FieldLabel htmlFor="catalog-exercise-video">{__('exercise_pages.form.video_url')}</FieldLabel>
                    <Input
                        id="catalog-exercise-video"
                        type="url"
                        value={form.data.video_url}
                        onChange={(event) => form.setData('video_url', event.target.value)}
                        aria-invalid={!!form.errors.video_url}
                    />
                    <InputError message={form.errors.video_url} />
                </Field>
                <div className="flex justify-end gap-2">
                    <Button type="button" variant="outline" onClick={closeAction}>
                        {__('exercise_pages.form.cancel_action')}
                    </Button>
                    <Button data-test="save-exercise" type="submit" disabled={form.processing}>
                        {form.processing && <Spinner data-icon="inline-start" />}
                        {__('exercise_pages.form.' + (exercise ? 'update_action' : 'create_action'))}
                    </Button>
                </div>
            </FieldGroup>
        </form>
    );
}
