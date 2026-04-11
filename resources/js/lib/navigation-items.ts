import { dashboard } from '@/routes';
import { index as cbcIndex } from '@/routes/complete-blood-count';
import { index as glucoseIndex } from '@/routes/glucose';
import { index as lipidProfileIndex } from '@/routes/lipid-profile';
import { edit } from '@/routes/medical-file';
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
                title: 'Lipid Profile',
                href: lipidProfileIndex(),
            },
            {
                title: 'Complete Blood Count',
                href: cbcIndex(),
            },
            {
                title: 'Glucose',
                href: glucoseIndex(),
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
