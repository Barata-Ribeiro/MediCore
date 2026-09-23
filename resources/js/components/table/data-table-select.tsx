import { Select, SelectContent, SelectGroup, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

export function DataTableSelect({
    value,
    options,
    onChange,
    label,
}: Readonly<{
    value: string;
    options: { label: string; value: string }[];
    onChange: (value: string) => void;
    label: string;
}>) {
    return (
        <Select
            value={value}
            items={options}
            onValueChange={(next) => {
                if (next !== null) onChange(next);
            }}
        >
            <SelectTrigger aria-label={label}>
                <SelectValue />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    {options.map((option) => (
                        <SelectItem key={option.value} value={option.value}>
                            {option.label}
                        </SelectItem>
                    ))}
                </SelectGroup>
            </SelectContent>
        </Select>
    );
}
