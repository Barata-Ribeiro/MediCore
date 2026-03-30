import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { format } from 'date-fns';
import { Fragment, useState } from 'react';

type Props = {
    id: string;
    name: string;
    defaultValue?: string;
};

export default function DatePicker({ id, name, defaultValue }: Readonly<Props>) {
    const [open, setOpen] = useState(false);
    const [date, setDate] = useState<Date | undefined>(defaultValue ? new Date(defaultValue) : undefined);

    return (
        <Fragment>
            <input
                type="hidden"
                name={name}
                value={date ? format(date, 'yyyy-MM-dd') : ''}
                readOnly
                aria-readonly
                required
                aria-required
            />

            <Popover open={open} onOpenChange={setOpen}>
                <PopoverTrigger asChild>
                    <Button variant="outline" id={id} className="justify-start font-normal">
                        {date ? date.toLocaleDateString() : 'Select date'}
                    </Button>
                </PopoverTrigger>
                <PopoverContent className="w-auto overflow-hidden p-0" align="start">
                    <Calendar
                        mode="single"
                        selected={date}
                        defaultMonth={date}
                        captionLayout="dropdown"
                        onSelect={(date) => {
                            setDate(date);
                            setOpen(false);
                        }}
                    />
                </PopoverContent>
            </Popover>
        </Fragment>
    );
}
