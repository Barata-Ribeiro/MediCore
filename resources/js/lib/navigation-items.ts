import { dashboard } from '@/routes';
import { index as cbcIndex } from '@/routes/complete-blood-count';
import { index as glucoseIndex } from '@/routes/glucose';
import { index as lipidProfileIndex } from '@/routes/lipid-profile';
import { edit } from '@/routes/medical-file';
import { index as ultrasensitiveTshIndex } from '@/routes/ultrasensitive-tsh';
import { index as ureaAndCreatinineIndex } from '@/routes/urea-and-creatinine';
import { index as vitaminB12Index } from '@/routes/vitamin-b12';
import { index as vitaminD3Index } from '@/routes/vitamin-d3';
import type { NavItem } from '@/types';
import { BriefcaseBusinessIcon, FileTextIcon, FolderGit2Icon, LayoutGridIcon, MicroscopeIcon } from 'lucide-react';

const mainNavItems: NavItem[] = [
    {
        title_path: 'main.menu.sidebar_items.dashboard',
        href: dashboard(),
        icon: LayoutGridIcon,
    },
    {
        title_path: 'main.menu.sidebar_items.medical_file',
        href: edit(),
        icon: FileTextIcon,
    },
    {
        title_path: 'main.menu.sidebar_items.exams',
        href: '#',
        icon: MicroscopeIcon,
        items: [
            {
                title_path: 'main.menu.sidebar_items.exams_items.complete_blood_count',
                href: cbcIndex(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.glucose',
                href: glucoseIndex(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.lipid_profile',
                href: lipidProfileIndex(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.ultrasensitive_tsh',
                href: ultrasensitiveTshIndex(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.urea_and_creatinine',
                href: ureaAndCreatinineIndex(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.vitamin_b12',
                href: vitaminB12Index(),
            },
            {
                title_path: 'main.menu.sidebar_items.exams_items.vitamin_d3',
                href: vitaminD3Index(),
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title_path: 'main.menu.sidebar_items.footer_items.repository',
        href: 'https://github.com/Barata-Ribeiro/MediCore',
        icon: FolderGit2Icon,
    },
    {
        title_path: 'main.menu.sidebar_items.footer_items.made_by',
        href: 'https://barataribeiro.com',
        icon: BriefcaseBusinessIcon,
    },
];

export { footerNavItems, mainNavItems };
