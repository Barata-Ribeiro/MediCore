import MuscleGroupRegistryForm from '@/components/forms/fitness/muscle-group-registry.form';
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
    muscleGroup: CatalogMuscleGroup;
};

export default function EditMuscleGroup({ muscleGroup }: Readonly<Props>) {
    const { __ } = lang();

    return (
        <Modal>
            {({ close }: Readonly<CloseActionType>) => (
                <Fragment>
                    <CardHeader className="flex items-start justify-between gap-2 border-b">
                        <div className="w-max space-y-1">
                            <CardTitle>{__('muscle_group_pages.form.title')}</CardTitle>
                            <CardDescription>{__('muscle_group_pages.form.description')}</CardDescription>
                        </div>
                        <Button
                            onClick={close}
                            variant="secondary"
                            size="icon"
                            aria-label={__('muscle_group_pages.form.cancel_action')}
                            title={__('muscle_group_pages.form.cancel_action')}
                        >
                            <XIcon aria-hidden />
                        </Button>
                    </CardHeader>
                    <CardContent>
                        <MuscleGroupRegistryForm muscleGroup={muscleGroup} closeAction={close} />
                    </CardContent>
                </Fragment>
            )}
        </Modal>
    );
}
