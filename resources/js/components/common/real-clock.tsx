import { useInterval } from '@/hooks/use-interval';
import { useComposedRefs } from '@/lib/compose-refs';
import { format } from 'date-fns/format';
import { forwardRef, useRef } from 'react';

type Props = {
    className?: string;
};

const RealClock = forwardRef<HTMLSpanElement, Props>((props, ref) => {
    const timeElRef = useRef<HTMLSpanElement>(null);
    const composedRef = useComposedRefs(ref, timeElRef);

    const { className, ...otherProps } = props;

    useInterval(() => {
        if (!timeElRef.current) {
            return;
        }

        const now = new Date();
        timeElRef.current.textContent = format(now, 'dd/MM/yyyy HH:mm');
    }, 60000);

    return (
        <span ref={composedRef} className={className} {...otherProps}>
            {format(new Date(), 'dd/MM/yyyy HH:mm')}
        </span>
    );
});

export default RealClock;
