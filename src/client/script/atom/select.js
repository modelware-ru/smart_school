import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class Select extends Atom {
    labelFor = 'l' + new Date().getTime() + Math.random();

    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            className = '',
            label = '',
            value = '',
            optionData = [],
            help = '',
            hasError = 'unknown', // 'yes', 'no', 'unknown'
            error = '',
            disabled = false,
            mandatory = false,
        } = settings;

        this._prop = {
            className,
            label,
            value,
            optionData,
            help,
            hasError,
            error,
            disabled,
            mandatory,
        };

        this._state = {
            value,
        };

        this._callback = {};

        this.el = this._ui_render();

    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { select: _ui_select, label: _ui_label, error: _ui_error, errorParent: _ui_errorParent } = this._el;

        switch (name) {
            // start "className"
            case 'className':
                break;
            // finish "className"
            // start "label"
            case 'label':
                break;
            // finish "label"
            // start "value"
            case 'value':
                break;
            // finish "value"
            // start "optionData"
            case 'optionData':
                break;
            // finish "optionData"
            // start "help"
            case 'help':
                break;
            // finish "help"
            // start "hasError"
            case 'hasError':
            // break;
            // finish "hasError"
            // start "error"
            case 'error':
                this._el.error = mount(_ui_errorParent, this._ui_error(), _ui_error, true);
                _ui_select.className = this._inputClassName();
                break;
            // finish "error"
            // start "disabled"
            case 'disabled':
                if (value) {
                    _ui_select.setAttribute('disabled', '');
                } else {
                    _ui_select.removeAttribute('disabled');
                }
                break;
            // finish "disabled"
            // start "mandatory"
            case 'mandatory':
                break;
            // finish "mandatory"
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        const { select: _ui_select, counter: _ui_counter } = this._el;

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

    _ui_help = () => {
        const { hasError, help } = this._prop;
        return <span className="text-secondary">{hasError !== 'yes' && help}</span>;
    };

    _ui_error = () => {
        const { hasError, error } = this._prop;
        return <span className="text-danger">{hasError === 'yes' && error}</span>;
    };

    // start "_ui_render"
    _ui_render = () => {
        const {
            className,
            label,
            // value,
            optionData,
            help,
            hasError,
            error,
            disabled,
            mandatory,
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
            <select id={this.labelFor} className={this._inputClassName()} disabled={disabled} value={value} onchange={this._onChange}>
                {optionList}
            </select>
        );
        this._el.label = <span>{label}</span>;
        this._el.help = this._ui_help();
        this._el.error = this._ui_error();

        return (
            <div class={className}>
                <label for={this.labelFor} class="form-label">
                    {this._el.label}
                    {mandatory && <span className="text-danger">&nbsp;*</span>}
                </label>
                {this._el.select}
                <div className="d-flex justify-content-between">
                    {
                        (this._el.errorParent = (
                            <div>
                                {this._el.help}
                                {this._el.error}
                            </div>
                        ))
                    }
                </div>
            </div>
        );
    };
    // finish "_ui_render"
}
