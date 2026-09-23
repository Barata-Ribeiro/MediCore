import type { features } from '@/hooks/features';
import { createTableHookContexts } from '@tanstack/react-table';

export const { tableContext, cellContext, headerContext, useTableContext, useCellContext, useHeaderContext } =
    createTableHookContexts<typeof features>();
