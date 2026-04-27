import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { useInitials } from '@/hooks/use-initials';
import { edit as editProfile } from '@/routes/profile';
import type { Profile } from '@/types/application/profile';
import { lang } from '@erag/lang-sync-inertia/react';
import { Link, usePage } from '@inertiajs/react';
import { format } from 'date-fns';
import { UserPenIcon } from 'lucide-react';
import { memo } from 'react';

type Props = {
    profile: Profile | null;
};

const DashboardProfileCard = memo<Readonly<Props>>(({ profile }) => {
    const { __ } = lang();
    const page = usePage();
    const { auth } = page.props;
    const getInitials = useInitials();

    if (!profile) {
        return (
            <Empty className="border border-dashed">
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <UserPenIcon aria-hidden />
                    </EmptyMedia>
                    <EmptyTitle>{__('dashboard.profile_card.empty.title')}</EmptyTitle>
                    <EmptyDescription>{__('dashboard.profile_card.empty.message')}</EmptyDescription>
                </EmptyHeader>
                <EmptyContent>
                    <Button variant="outline" size="sm" asChild>
                        <Link href={editProfile()} as="button" prefetch="hover">
                            {__('dashboard.profile_card.empty.action')}
                        </Link>
                    </Button>
                </EmptyContent>
            </Empty>
        );
    }

    return (
        <Card className="w-full">
            <CardHeader className="flex items-center gap-4">
                <Avatar className="size-16 overflow-hidden rounded-full">
                    <AvatarImage src={auth.user.avatar} alt={auth.user.name} />
                    <AvatarFallback className="rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                        {getInitials(auth.user.name)}
                    </AvatarFallback>
                </Avatar>

                <div className="grid gap-1">
                    <CardTitle className="text-xl">{__('dashboard.profile_card.card.title')}</CardTitle>
                    <CardDescription>{__('dashboard.profile_card.card.description')}</CardDescription>
                </div>
            </CardHeader>

            <CardContent className="grid gap-4 md:grid-cols-2">
                <dl className="grid gap-2">
                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.full_name')}</dt>
                        <dd>{profile.full_name}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.sex')}</dt>
                        <dd className="capitalize">{profile?.sex ?? __('dashboard.card_not_informed')}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.date_of_birth')}</dt>
                        <dd>
                            {profile?.birth_date
                                ? format(new Date(profile.birth_date), 'MMMM dd, yyyy')
                                : __('dashboard.card_not_informed')}
                        </dd>
                    </div>
                </dl>

                <dl className="grid gap-2">
                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.phone_number')}</dt>
                        <dd>{profile.phone_number ?? __('dashboard.card_not_informed')}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.address')}</dt>
                        <dd>{profile.address ?? __('dashboard.card_not_informed')}</dd>
                    </div>

                    <div>
                        <dt className="font-medium">{__('dashboard.profile_card.card.email')}</dt>
                        <dd>{auth.user.email ?? __('dashboard.card_not_informed')}</dd>
                    </div>
                </dl>
            </CardContent>

            <CardFooter className="mt-auto flex items-end justify-between gap-4 border-t">
                <time dateTime={profile.updated_at} className="text-sm text-muted-foreground">
                    {__('dashboard.profile_card.card.updated_at')}{' '}
                    {format(new Date(profile.updated_at), 'MMMM dd, yyyy')}
                </time>

                <Button variant="secondary" size="sm" asChild>
                    <Link href={editProfile()} as="button" prefetch="hover">
                        {__('dashboard.profile_card.card.edit_action')}
                    </Link>
                </Button>
            </CardFooter>
        </Card>
    );
});

export default DashboardProfileCard;
