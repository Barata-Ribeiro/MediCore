import { useCallbackRef } from '@/lib/use-callback-ref';
import type { RefObject } from 'react';
import { useEffect, useMemo, useRef } from 'react';

export interface UseDebouncedCallbackOptions {
    delay: number;
    flushOnUnmount?: boolean;
    leading?: boolean;
    maxWait?: number;
}

export type UseDebouncedCallbackReturnValue<T extends (...args: any[]) => any> = ((...args: Parameters<T>) => void) & {
    flush: () => void;
    cancel: () => void;
    isPending: () => boolean;
};

export function useDebouncedCallback<T extends (...args: any[]) => any>(
    callback: T,
    options: number | UseDebouncedCallbackOptions,
) {
    const { delay, flushOnUnmount, leading, maxWait } =
        typeof options === 'number'
            ? {
                  delay: options,
                  flushOnUnmount: false,
                  leading: false,
                  maxWait: undefined as number | undefined,
              }
            : options;

    const handleCallback = useCallbackRef(callback);
    const debounceTimerRef = useRef(0);
    const maxWaitTimerRef = useRef(0);
    const latestArgsRef = useRef<Parameters<T> | null>(null);

    /* eslint-disable react-hooks/refs */
    const lastCallback = useMemo(() => {
        const currentCallback = Object.assign(
            (...args: Parameters<T>) => {
                globalThis.window.clearTimeout(debounceTimerRef.current);
                latestArgsRef.current = args;

                const isFirstCall = currentCallback._isFirstCall;
                currentCallback._isFirstCall = false;

                function clearTimeoutAndLeadingRef() {
                    globalThis.window.clearTimeout(debounceTimerRef.current);
                    globalThis.window.clearTimeout(maxWaitTimerRef.current);
                    debounceTimerRef.current = 0;
                    maxWaitTimerRef.current = 0;
                    currentCallback._isFirstCall = true;
                    currentCallback._hasPendingCallback = false;
                }

                function startMaxWaitTimer() {
                    if (maxWait !== undefined && maxWaitTimerRef.current === 0) {
                        maxWaitTimerRef.current = globalThis.window.setTimeout(() => {
                            if (debounceTimerRef.current !== 0) {
                                const latestArgs = latestArgsRef.current!;
                                clearTimeoutAndLeadingRef();
                                handleCallback(...latestArgs);
                            }
                        }, maxWait);
                    }
                }

                if (leading && isFirstCall) {
                    handleCallback(...args);

                    const resetLeadingState = () => {
                        clearTimeoutAndLeadingRef();
                    };

                    const flush = () => {
                        if (debounceTimerRef.current !== 0) {
                            clearTimeoutAndLeadingRef();
                            handleCallback(...args);
                        }
                    };

                    const cancel = () => {
                        clearTimeoutAndLeadingRef();
                    };

                    currentCallback.flush = flush;
                    currentCallback.cancel = cancel;
                    debounceTimerRef.current = globalThis.window.setTimeout(resetLeadingState, delay);
                    startMaxWaitTimer();

                    return;
                }

                if (leading && !isFirstCall) {
                    currentCallback._hasPendingCallback = true;
                    const flush = buildFlushFunction<T>(
                        debounceTimerRef,
                        clearTimeoutAndLeadingRef,
                        handleCallback,
                        args,
                    );

                    const cancel = () => {
                        clearTimeoutAndLeadingRef();
                    };

                    currentCallback.flush = flush;
                    currentCallback.cancel = cancel;

                    const resetLeadingState = () => {
                        clearTimeoutAndLeadingRef();
                    };
                    debounceTimerRef.current = globalThis.window.setTimeout(resetLeadingState, delay);
                    startMaxWaitTimer();

                    return;
                }

                currentCallback._hasPendingCallback = true;

                const flush = buildFlushFunction<T>(debounceTimerRef, clearTimeoutAndLeadingRef, handleCallback, args);

                const cancel = () => {
                    clearTimeoutAndLeadingRef();
                };

                currentCallback.flush = flush;
                currentCallback.cancel = cancel;
                debounceTimerRef.current = globalThis.window.setTimeout(flush, delay);
                startMaxWaitTimer();
            },
            {
                flush: () => {},
                cancel: () => {},
                isPending: () => currentCallback._hasPendingCallback,
                _isFirstCall: true,
                _hasPendingCallback: false,
            },
        );

        return currentCallback;
    }, [handleCallback, delay, leading, maxWait]);

    useEffect(
        () => () => {
            if (flushOnUnmount) {
                lastCallback.flush();
            } else {
                lastCallback.cancel();
            }
        },
        [lastCallback, flushOnUnmount],
    );

    return lastCallback;
}

function buildFlushFunction<T extends (...args: any[]) => any>(
    debounceTimerRef: RefObject<number>,
    clearTimeoutAndLeadingRef: () => void,
    handleCallback: T,
    args: Parameters<T>,
) {
    return () => {
        if (debounceTimerRef.current !== 0) {
            clearTimeoutAndLeadingRef();
            handleCallback(...args);
        }
    };
}
