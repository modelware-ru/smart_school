import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

//TODO: Проверить, что help отрисовывается при отсутствии ошибок (error)
export default class Input extends Atom {
    labelFor = 'l' + new Date().getTime() + Math.random();

    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            className = '',
            label = '',
            type = 'text', // text, password
            placeholder = '',
            value = '',
            help = '',
            hasError = 'unknown', // 'yes', 'no', 'unknown'
            error = '',
            maxLength = null,
            disabled = false,
            mandatory = false,
            iconBefore = '',
            onTest = null,
        } = settings;

        this._prop = {
            className,
            label,
            type,
            placeholder,
            value, // initial value
            help,
            hasError,
            error,
            maxLength,
            disabled,
            mandatory,
            iconBefore,
        };

        this._state = {
            value,
            availableCount: maxLength - value.length,
        };

        this._callback = {
            onTest,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    // start "_renderProp"
    _renderProp = (name, value) => {
        let { input: _ui_input, label: _ui_label, error: _ui_error, errorParent: _ui_errorParent } = this._el;

        switch (name) {
            // start "className"
            case 'className':
                break;
            // finish "className"
            // start "label"
            case 'label':
                _ui_label.innerText = value;
                break;
            // finish "label"
            // start "type"
            case 'type':
                break;
            // finish "type"
            // start "placeholder"
            case 'placeholder':
                _ui_input.placeholder = value;
                break;
            // finish "placeholder"
            // start "value"
            case 'value':
                break;
            // finish "value"
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
                _ui_input.className = this._input_class_name();
                break;
            // finish "error"
            // start "maxLength"
            case 'maxLength':
                break;
            // finish "maxLength"
            // start "disabled"
            case 'disabled':
                if (value) {
                    _ui_input.setAttribute('disabled', '');
                } else {
                    _ui_input.removeAttribute('disabled');
                }
                break;
            // finish "disabled"
            // start "mandatory"
            case 'mandatory':
                break;
            // finish "mandatory"
            // start "iconBefore"
            case 'iconBefore':
                break;
            // finish "iconBefore"
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        const { input: _ui_input, counter: _ui_counter } = this._el;

        switch (name) {
            // start "value"
            case 'value':
                _ui_input.value = value;
                break;
            // finish "value"
            // start "availableCount"
            case 'availableCount':
                _ui_counter.innerText = value;
                break;
            // finish "availableCount"
            default:
                return;
        }
    };
    // finish "_renderState"

    _onInput = (e) => {
        const { value: oldValue } = this._state;
        const newValue = e.target.value;

        if (oldValue === newValue) return;

        const { onTest } = this._callback;
        if (onTest && !onTest(newValue)) {
            this._renderState('value', oldValue);
            return;
        }

        const { maxLength } = this._prop;

        if (maxLength && newValue.length > maxLength) {
            this._renderState('value', oldValue);
            return;
        }
        this._updateState('value', newValue);
        if (this._el.counter) {
            this._updateState('availableCount', maxLength - newValue.length);
        }
    };

    _input_class_name = () => {
        const { hasError } = this._prop;
        return clsx('form-control', { 'is-invalid': hasError === 'yes' }, { 'is-valid': hasError === 'no' });
    };

    _ui_help = () => {
        const { hasError, help } = this._prop;
        return <span className="text-secondary">{hasError !== 'yes' && help}</span>;
    };

    _ui_error = () => {
        const { hasError, error } = this._prop;
        return <span className="text-danger">{hasError === 'yes' && error}</span>;
    };

    _ui_icon_before = () => {
        const { iconBefore } = this._prop;
        return iconBefore ? (
            <span className="input-group-text rounded-start-3">
                <i className={`bi ${iconBefore}`}></i>
            </span>
        ) : (
            <span />
        );
    };

    // start "_ui_render"
    _ui_render = () => {
        const {
            className,
            label,
            type,
            placeholder,
            // value,
            help,
            hasError,
            error,
            maxLength,
            disabled,
            mandatory,
            iconBefore,
        } = this._prop;
        const { value, availableCount } = this._state;

        this._el.input = (
            <input
                type={type}
                className={this._input_class_name()}
                id={this.labelFor}
                placeholder={placeholder}
                value={value}
                oninput={this._onInput}
                disabled={disabled}
            />
        );
        this._el.label = <span>{label}</span>;
        this._el.help = this._ui_help();
        this._el.error = this._ui_error();
        this._el.counter = maxLength && <span className="text-secondary fw-bold">{availableCount}</span>;
        this._el.iconBefore = this._ui_icon_before();

        return (
            <div className={className}>
                <label for={this.labelFor} className="form-label fw-bold">
                    {this._el.label}
                    {mandatory && <span className="text-danger">&nbsp;*</span>}
                </label>
                {this._el.iconBefore}
                {this._el.input}
                <div className="d-flex justify-content-between">
                    {
                        (this._el.errorParent = (
                            <div>
                                {this._el.help}
                                {this._el.error}
                            </div>
                        ))
                    }
                    {this._el.counter}
                </div>
            </div>
        );
    };
    // finish "_ui_render"
}
