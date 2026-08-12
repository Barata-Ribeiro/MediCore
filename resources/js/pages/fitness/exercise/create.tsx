import ExerciseRegistryForm from '@/components/forms/fitness/exercise-registry.form';
import { Button } from '@/components/ui/button';
import { CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import type { CatalogMuscleGroup } from '@/types/application/fitness/catalog';
import { lang } from '@erag/lang-sync-inertia/react';
import { Modal } from '@inertiaui/modal-react';
import { XIcon } from 'lucide-react';
import { Fragment } from 'react/jsx-runtime';

type CloseActionType = {
    close: () => void;
};

type Props = {
    muscleGroups: CatalogMuscleGroup[];
};

export default function CreateExercise({ muscleGroups }: Readonly<Props>) {
    const { __ } = lang();

    return (
        <Modal>
            {({ close }: Readonly<CloseActionType>) => (
                <Fragment>
                    <CardHeader className="flex items-start justify-between gap-2 border-b">
                        <div className="w-max space-y-1">
                            <CardTitle>{__('exercise_pages.form.title')}</CardTitle>
                            <CardDescription>{__('exercise_pages.form.description')}</CardDescription>
                        </div>
                        <Button onClick={close} variant="secondary" size="icon">
                            <XIcon aria-hidden />
                        </Button>
                    </CardHeader>
                    <CardContent>
                        <ExerciseRegistryForm muscleGroups={muscleGroups} closeAction={close} />
                    </CardContent>
                </Fragment>
            )}
        </Modal>
    );
}
