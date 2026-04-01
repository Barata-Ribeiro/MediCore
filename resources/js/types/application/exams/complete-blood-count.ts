export interface CompleteBloodCount {
    id: number;
    medical_file_id: number;
    hematocrit: number;
    hemoglobin: number;
    red_blood_cell_count: number;
    mean_corpuscular_volume: number;
    mean_corpuscular_hemoglobin: number;
    mean_corpuscular_hemoglobin_concentration: number;
    red_blood_cell_distribution_width: number;
    leukocyte_count: number;
    rod_neutrophil_count: number;
    segmented_neutrophil_count: number;
    lymphocyte_count: number;
    monocyte_count: number;
    eosinophil_count: number;
    basophil_count: number;
    metamyelocyte_count: number;
    promyelocyte_count: number;
    atypical_cell_count: number;
    platelet_count: number;
    report_date: string;
    created_at: string;
    updated_at: string;
}

export interface CompleteBloodCountChartData extends Omit<
    CompleteBloodCount,
    'id' | 'medical_file_id' | 'report_date' | 'created_at' | 'updated_at'
> {
    date: string;
    ratio: number;
    count: number;
}
