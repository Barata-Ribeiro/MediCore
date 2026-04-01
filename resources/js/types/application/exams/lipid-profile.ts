export interface LipidProfile {
    id: number;
    medical_file_id: number;
    total_cholesterol: number;
    hdl_cholesterol: number;
    ldl_cholesterol: number;
    vldl_cholesterol: number;
    triglycerides: number;
    report_date: string;
    created_at: string;
    updated_at: string;
}

export interface LipidProfileChartData {
    x_axis_label: string;
    datasets: Record<string, { label: string; data: number }>;
}
