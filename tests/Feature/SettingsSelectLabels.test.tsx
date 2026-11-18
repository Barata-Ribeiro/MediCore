import { createInertiaApp } from '@inertiajs/react';
import { renderToString } from 'react-dom/server';
import { expect, it } from 'vite-plus/test';
import MedicalFileManagerForm from '../../resources/js/components/forms/settings/medical-file-manager.form';
import PersonalProfileManagerForm from '../../resources/js/components/forms/settings/personal-profile-manager.form';

async function renderSettingsForms(bloodType: string | null, sex: string | null) {
    const { body } = await createInertiaApp({
        page: {
            component: 'SettingsFormsTest',
            url: '/settings',
            version: null,
            clearHistory: false,
            encryptHistory: false,
            props: {
                errors: {},
                file: { blood_type: bloodType },
                profile: { sex },
                lang: {
                    settings_pages: {
                        medical_file_page: { form: { blood_type_placeholder: 'Selecione seu tipo sanguíneo' } },
                        profile_page: {
                            personal_profile_manager: {
                                form: {
                                    sex_label: 'Selecione seu sexo',
                                    sex_male: 'Masculino',
                                    sex_female: 'Feminino',
                                },
                            },
                        },
                    },
                },
            },
        },
        render: renderToString,
        resolve: () => () => (
            <>
                <MedicalFileManagerForm />
                <PersonalProfileManagerForm />
            </>
        ),
        setup: ({ App, props }) => <App {...props} />,
    });

    return body;
}

it.each([
    ['a_positive', 'male', 'A+', 'Masculino'],
    ['o_negative', 'female', 'O-', 'Feminino'],
    [null, null, 'Selecione seu tipo sanguíneo', 'Selecione seu sexo'],
])('renders the closed settings selects for blood type %s and sex %s', async (bloodType, sex, bloodLabel, sexLabel) => {
    const body = await renderSettingsForms(bloodType, sex);

    const labels = Array.from(
        body.matchAll(/<span\b[^>]*data-slot="select-value"[^>]*>([^<]*)<\/span>/g),
        ([, label]) => label,
    );

    expect(labels).toEqual([bloodLabel, sexLabel]);
    expect(body).toContain(`name="blood_type" value="${bloodType ?? ''}"`);
    expect(body).toContain(`name="sex" value="${sex ?? ''}"`);
});
