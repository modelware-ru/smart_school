import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';
import Button from './button';

export default class SelectMenuItem extends Atom {

    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            className = '',
            status = 'new', // 'new', 'done'
            value = '',
            optionData = [],
            hasError = 'unknown', // 'yes', 'no', 'unknown'
            onChanged = null,
        } = settings;

        this._prop = {
            className,
            status,
            value,
            optionData,
            hasError,
        };

        this._state = {
            value,
        };

        this._callback = {
            onChanged,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        // let { ???: _ui_??? } = this._el;

        switch (name) {
            // start "className"
            case 'className':
                break;
            // finish "className"
            // start "status"
            case 'status':
                break;
            // finish "status"
            // start "value"
            case 'value':
                break;
            // finish "value"
            // start "optionData"
            case 'optionData':
                break;
            // finish "optionData"
            // start "hasError"
            case 'hasError':
                break;
            // finish "hasError"
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        const { select: _ui_select } = this._el;

        switch (name) {
            // start "value"
            case 'value':
                _ui_select.value = value;
                break;
            // finish "value"
            default:
                return;
        }
    };
    // finish "_renderState"

    _onChange = (e) => {
        const { value: oldValue } = this._state;
        const newValue = e.target.value;

        if (oldValue === newValue) return;

        this._updateState('value', newValue);
    };

    _inputClassName = () => {
        const { hasError } = this._prop;
        return clsx('form-select', hasError === 'yes' && 'is-invalid', hasError === 'no' && 'is-valid');
    };

    _onButtonClick = (e) => {
        console.log('_onButtonClick');
    };

    // start "_ui_render"
    _ui_render = () => {
        const {
            className,
            status,
            // value,
            optionData,
            hasError,
        } = this._prop;

        const { value } = this._state;

        const optionList = optionData.map((item) => {
            return (
                <option value={item['value']} disabled={item['disabled']} selected={item['value'] === value}>
                    {item['name']}
                </option>
            );
        });

        this._el.select = (
            <select className={this._inputClassName()} disabled={status === 'done'} value={value} onchange={this._onChange}>
                {optionList}
            </select>
        );

        return (
            <div class={clsx(className, 'd-flex')}>
                {this._el.select}
                {status === 'new' && (
                    <Button className="btn btn-outline-success ms-4" icon={'bi-plus-circle'} onClick={this._onButtonClick} />
                )}
                {status === 'done' && (
                    <Button className="btn btn-outline-danger ms-4" icon={'bi-trash'} onClick={this._onButtonClick} />
                )}
            </div>
        );
    };
    // finish "_ui_render"
}
