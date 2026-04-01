import CompleteBloodCountController from '@/actions/App/Http/Controllers/Exams/CompleteBloodCountController';
import DatePicker from '@/components/helpers/date-picker';
import InputError from '@/components/helpers/input-error';
import RequiredIndicator from '@/components/helpers/required-indicator';
import { Button } from '@/components/ui/button';
import { Field, FieldGroup, FieldLabel } from '@/components/ui/field';
import { InputGroup, InputGroupAddon, InputGroupInput, InputGroupText } from '@/components/ui/input-group';
import { Spinner } from '@/components/ui/spinner';
import type { CompleteBloodCount } from '@/types/application/exams/complete-blood-count';
import { Form } from '@inertiajs/react';
import { Activity, Fragment, memo } from 'react';

type Props = {
    cbcCount?: CompleteBloodCount;
};

const CbcCountForm = memo(({ cbcCount }: Readonly<Props>) => {
    const isEditMode = cbcCount && cbcCount !== null;

    const formRoute = isEditMode
        ? CompleteBloodCountController.update.form(cbcCount.id)
        : CompleteBloodCountController.store.form();

    return (
        <Form
            {...formRoute}
            options={{ preserveScroll: true }}
            disableWhileProcessing
            className="space-y-6 inert:pointer-events-none inert:grayscale-100"
        >
            {({ processing, errors }) => (
                <Fragment>
                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['hematocrit']}>
                            <FieldLabel htmlFor="hematocrit">
                                Hematocrit
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="hematocrit"
                                    name="hematocrit"
                                    placeholder="e.g. 45.5"
                                    defaultValue={cbcCount?.hematocrit}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['hematocrit']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>%</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['hematocrit']} />
                        </Field>

                        <Field data-invalid={!!errors['hemoglobin']}>
                            <FieldLabel htmlFor="hemoglobin">
                                Hemoglobin
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="hemoglobin"
                                    name="hemoglobin"
                                    placeholder="e.g. 13.2"
                                    defaultValue={cbcCount?.hemoglobin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['hemoglobin']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>g/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['hemoglobin']} />
                        </Field>

                        <Field data-invalid={!!errors['red_blood_cells_count']}>
                            <FieldLabel htmlFor="red_blood_cells_count">
                                Red Blood Cells
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="red_blood_cells_count"
                                    name="red_blood_cells_count"
                                    placeholder="e.g. 4.5"
                                    defaultValue={cbcCount?.red_blood_cell_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['red_blood_cells_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>million/µL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['red_blood_cells']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['mean_corpuscular_volume']}>
                            <FieldLabel htmlFor="mean_corpuscular_volume">
                                Mean Corpuscular Volume (MCV)
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="mean_corpuscular_volume"
                                    name="mean_corpuscular_volume"
                                    placeholder="e.g. 90.5"
                                    defaultValue={cbcCount?.mean_corpuscular_volume}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['mean_corpuscular_volume']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>fL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['mean_corpuscular_volume']} />
                        </Field>

                        <Field data-invalid={!!errors['mean_corpuscular_hemoglobin']}>
                            <FieldLabel htmlFor="mean_corpuscular_hemoglobin">
                                Mean Corpuscular Hemoglobin (MCH)
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="mean_corpuscular_hemoglobin"
                                    name="mean_corpuscular_hemoglobin"
                                    placeholder="e.g. 30.2"
                                    defaultValue={cbcCount?.mean_corpuscular_hemoglobin}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['mean_corpuscular_hemoglobin']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>pg</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['mean_corpuscular_hemoglobin']} />
                        </Field>

                        <Field data-invalid={!!errors['mean_corpuscular_hemoglobin_concentration']}>
                            <FieldLabel htmlFor="mean_corpuscular_hemoglobin_concentration">
                                Mean Corpuscular Hemoglobin Concentration (MCHC)
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="mean_corpuscular_hemoglobin_concentration"
                                    name="mean_corpuscular_hemoglobin_concentration"
                                    placeholder="e.g. 33.5"
                                    defaultValue={cbcCount?.mean_corpuscular_hemoglobin_concentration}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['mean_corpuscular_hemoglobin_concentration']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>g/dL</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['mean_corpuscular_hemoglobin_concentration']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['red_blood_cell_distribution_width']}>
                            <FieldLabel htmlFor="red_blood_cell_distribution_width">
                                Red Blood Cell Distribution Width (RDW)
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="red_blood_cell_distribution_width"
                                    name="red_blood_cell_distribution_width"
                                    placeholder="e.g. 14.5"
                                    defaultValue={cbcCount?.red_blood_cell_distribution_width}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['red_blood_cell_distribution_width']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>%</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['red_blood_cell_distribution_width']} />
                        </Field>

                        <Field data-invalid={!!errors['leukocyte_count']}>
                            <FieldLabel htmlFor="leukocyte_count">
                                Leukocyte Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="leukocyte_count"
                                    name="leukocyte_count"
                                    placeholder="e.g. 1500"
                                    defaultValue={cbcCount?.leukocyte_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['leukocyte_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['leukocyte_count']} />
                        </Field>

                        <Field data-invalid={!!errors['rod_neutrophil_count']}>
                            <FieldLabel htmlFor="rod_neutrophil_count">
                                Rod Neutrophil Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="rod_neutrophil_count"
                                    name="rod_neutrophil_count"
                                    placeholder="e.g. 300"
                                    defaultValue={cbcCount?.rod_neutrophil_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['rod_neutrophil_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['rod_neutrophil_count']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['segmented_neutrophil_count']}>
                            <FieldLabel htmlFor="segmented_neutrophil_count">
                                Segmented Neutrophil Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="segmented_neutrophil_count"
                                    name="segmented_neutrophil_count"
                                    placeholder="e.g. 1200"
                                    defaultValue={cbcCount?.segmented_neutrophil_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['segmented_neutrophil_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['segmented_neutrophil_count']} />
                        </Field>

                        <Field data-invalid={!!errors['lymphocyte_count']}>
                            <FieldLabel htmlFor="lymphocyte_count">
                                Lymphocyte Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="lymphocyte_count"
                                    name="lymphocyte_count"
                                    placeholder="e.g. 800"
                                    defaultValue={cbcCount?.lymphocyte_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['lymphocyte_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['lymphocyte_count']} />
                        </Field>

                        <Field data-invalid={!!errors['monocyte_count']}>
                            <FieldLabel htmlFor="monocyte_count">
                                Monocyte Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="monocyte_count"
                                    name="monocyte_count"
                                    placeholder="e.g. 400"
                                    defaultValue={cbcCount?.monocyte_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['monocyte_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['monocyte_count']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['eosinophil_count']}>
                            <FieldLabel htmlFor="eosinophil_count">
                                Eosinophil Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="eosinophil_count"
                                    name="eosinophil_count"
                                    placeholder="e.g. 100"
                                    defaultValue={cbcCount?.eosinophil_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['eosinophil_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['eosinophil_count']} />
                        </Field>

                        <Field data-invalid={!!errors['basophil_count']}>
                            <FieldLabel htmlFor="basophil_count">
                                Basophil Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="basophil_count"
                                    name="basophil_count"
                                    placeholder="e.g. 50"
                                    defaultValue={cbcCount?.basophil_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['basophil_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['basophil_count']} />
                        </Field>

                        <Field data-invalid={!!errors['metamyelocyte_count']}>
                            <FieldLabel htmlFor="metamyelocyte_count">
                                Metamyelocyte Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="metamyelocyte_count"
                                    name="metamyelocyte_count"
                                    placeholder="e.g. 20"
                                    defaultValue={cbcCount?.metamyelocyte_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['metamyelocyte_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['metamyelocyte_count']} />
                        </Field>
                    </FieldGroup>

                    <FieldGroup className="grid gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <Field data-invalid={!!errors['promyelocyte_count']}>
                            <FieldLabel htmlFor="promyelocyte_count">
                                Promyelocyte Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="promyelocyte_count"
                                    name="promyelocyte_count"
                                    placeholder="e.g. 10"
                                    defaultValue={cbcCount?.promyelocyte_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['promyelocyte_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['promyelocyte_count']} />
                        </Field>

                        <Field data-invalid={!!errors['atypical_cell_count']}>
                            <FieldLabel htmlFor="atypical_cell_count">
                                Atypical Cell Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="atypical_cell_count"
                                    name="atypical_cell_count"
                                    placeholder="e.g. 5"
                                    defaultValue={cbcCount?.atypical_cell_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['atypical_cell_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['atypical_cell_count']} />
                        </Field>

                        <Field data-invalid={!!errors['platelet_count']}>
                            <FieldLabel htmlFor="platelet_count">
                                Platelet Count
                                <RequiredIndicator />
                            </FieldLabel>
                            <InputGroup>
                                <InputGroupInput
                                    type="number"
                                    id="platelet_count"
                                    name="platelet_count"
                                    placeholder="e.g. 250000"
                                    defaultValue={cbcCount?.platelet_count}
                                    min={0}
                                    step={0.01}
                                    aria-invalid={!!errors['platelet_count']}
                                    required
                                    aria-required
                                />
                                <InputGroupAddon align="inline-end">
                                    <InputGroupText>/mm</InputGroupText>
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError message={errors['platelet_count']} />
                        </Field>
                    </FieldGroup>

                    <Field data-invalid={!!errors['report_date']}>
                        <FieldLabel htmlFor="report_date">
                            Report Date
                            <RequiredIndicator />
                        </FieldLabel>
                        <DatePicker id="report_date" name="report_date" defaultValue={cbcCount?.report_date} />
                        <InputError message={errors['report_date']} />
                    </Field>

                    <Button data-test="save-lipid-profile" type="submit" disabled={processing}>
                        <Activity mode={processing ? 'visible' : 'hidden'}>
                            <Spinner aria-hidden />
                        </Activity>
                        Save
                    </Button>
                </Fragment>
            )}
        </Form>
    );
});

export default CbcCountForm;
