import { dashboard } from '@/routes';
import { index as cbcIndex } from '@/routes/complete-blood-count';
import { index as glucoseIndex } from '@/routes/glucose';
import { index as lipidProfileIndex } from '@/routes/lipid-profile';
import { edit } from '@/routes/medical-file';
import { index as vitaminB12Index } from '@/routes/vitamin-b12';
import { index as vitaminD3Index } from '@/routes/vitamin-d3';
import type { NavItem } from '@/types';
import { BriefcaseBusinessIcon, FileTextIcon, FolderGit2, LayoutGrid, MicroscopeIcon } from 'lucide-react';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Medical File',
        href: edit(),
        icon: FileTextIcon,
    },
    {
        title: 'Exams',
        href: '#',
        icon: MicroscopeIcon,
        items: [
            {
                title: 'Complete Blood Count',
                href: cbcIndex(),
            },
            {
                title: 'Glucose',
                href: glucoseIndex(),
            },
            {
                title: 'Lipid Profile',
                href: lipidProfileIndex(),
            },
            {
                title: 'Vitamin B12',
                href: vitaminB12Index(),
            },
            {
                title: 'Vitamin D3',
                href: vitaminD3Index(),
            },
        ],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/Barata-Ribeiro/MediCore',
        icon: FolderGit2,
    },
    {
        title: 'Made by Barata Ribeiro',
        href: 'https://barataribeiro.com',
        icon: BriefcaseBusinessIcon,
    },
];

export { footerNavItems, mainNavItems };
