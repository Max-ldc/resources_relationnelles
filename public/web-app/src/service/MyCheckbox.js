import {CheckboxProps} from 'react-aria-components';
import {CheckboxContext, useContextProps} from 'react-aria-components';
import {useToggleState} from 'react-stately';
import {useCheckbox} from 'react-aria';
import React from 'react';


const MyCheckbox = React.forwardRef(
  (props: CheckboxProps, ref: React.ForwardedRef<HTMLInputElement>) => {
    // Merge the local props and ref with the ones provided via context.
    [props, ref] = useContextProps(props, ref, CheckboxContext);

    // Follow the hook docs and implement your customizations...
    let state = useToggleState(props);
    let { inputProps } = useCheckbox(props, state, ref);
    return <input {...inputProps} ref={ref} />;
  }
);

export default MyCheckbox;      
