export type ProfileSex = 'male' | 'female';

export interface Profile {
    id: number;
    user_id: number;
    first_name: string;
    last_name: string;
    bio: string | null;
    birth_date: string;
    phone_number: string;
    address: string;
    sex: ProfileSex | null;
    gender_identity: string | null;
    created_at: string;
    updated_at: string;

    // Appended accessors from Profile model
    full_name?: string;
    age?: number;
}

export type ProfileUpdatePayload = Omit<Profile, 'id' | 'user_id' | 'created_at' | 'updated_at' | 'full_name' | 'age'>;
