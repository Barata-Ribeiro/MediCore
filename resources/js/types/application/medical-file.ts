export const BloodType = {
    A_POSITIVE: 'a_positive',
    A_NEGATIVE: 'a_negative',
    B_POSITIVE: 'b_positive',
    B_NEGATIVE: 'b_negative',
    AB_POSITIVE: 'ab_positive',
    AB_NEGATIVE: 'ab_negative',
    O_POSITIVE: 'o_positive',
    O_NEGATIVE: 'o_negative',
} as const;

export type BloodType = (typeof BloodType)[keyof typeof BloodType];

const BLOOD_TYPE_LABELS: Record<BloodType, string> = {
    [BloodType.A_POSITIVE]: 'A+',
    [BloodType.A_NEGATIVE]: 'A-',
    [BloodType.B_POSITIVE]: 'B+',
    [BloodType.B_NEGATIVE]: 'B-',
    [BloodType.AB_POSITIVE]: 'AB+',
    [BloodType.AB_NEGATIVE]: 'AB-',
    [BloodType.O_POSITIVE]: 'O+',
    [BloodType.O_NEGATIVE]: 'O-',
};

export function bloodTypeLabel(bloodType: BloodType): string {
    return BLOOD_TYPE_LABELS[bloodType];
}

export interface MedicalFile {
    id: number;
    user_id: number;
    blood_type: BloodType | null;
    allergies: string | null;
    medications: string | null;
    weight: number | null;
    height: number | null;
    emergency_contact_name: string | null;
    emergency_contact_phone: string | null;
    emergency_contact_relationship: string | null;
    created_at: string;
    updated_at: string;

    // APpend accessors from MedicalFile model
    bmi?: number | null;
}
