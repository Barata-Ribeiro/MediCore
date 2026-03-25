import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Link } from '@inertiajs/react';
import { Activity, Fragment } from 'react';

export function Breadcrumbs({
    breadcrumbs,
}: Readonly<{
    breadcrumbs: BreadcrumbItemType[];
}>) {
    return (
        <Fragment>
            {breadcrumbs.length > 0 && (
                <Breadcrumb>
                    <BreadcrumbList>
                        {breadcrumbs.map((item, index) => {
                            const isLast = index === breadcrumbs.length - 1;

                            return (
                                <Fragment key={`${JSON.stringify(item.href)}-${item.title}`}>
                                    <BreadcrumbItem>
                                        {isLast ? (
                                            <BreadcrumbPage>{item.title}</BreadcrumbPage>
                                        ) : (
                                            <BreadcrumbLink asChild>
                                                <Link href={item.href}>{item.title}</Link>
                                            </BreadcrumbLink>
                                        )}
                                    </BreadcrumbItem>
                                    <Activity mode={isLast ? 'hidden' : 'visible'}>
                                        <BreadcrumbSeparator />
                                    </Activity>
                                </Fragment>
                            );
                        })}
                    </BreadcrumbList>
                </Breadcrumb>
            )}
        </Fragment>
    );
}
