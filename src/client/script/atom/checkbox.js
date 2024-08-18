import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

export default class Checkbox extends Atom {
    labelFor = 'l' + new Date().getTime() + Math.random();

    // start "constructor"
    constructor(settings = {}) {
        super();
        const {
            className = '',
            label = '',
            checked = '',
            help = '',
            hasError = 'unknown', // 'yes', 'no', 'unknown'
            error = '',
            disabled = false,
            mandatory = false,
        } = settings;

        this._prop = {
            className,
            label,
            checked, // initial value
            help,
            hasError,
            error,
            disabled,
            mandatory,
        };

        this._state = {
            checked,
        };

        this._callback = {};

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
            // start "checked"
            case 'checked':
                if (value) {
                    _ui_input.setAttribute('checked', '');
                } else {
                    _ui_input.removeAttribute('checked');
                }
                break;
            // finish "checked"
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
                _ui_input.className = this._checkbox_class_name();
                break;
            // finish "error"
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
            default:
                return;
        }
    };
    // finish "_renderProp"

    // start "_renderState"
    _renderState = (name, value) => {
        let { input: _ui_input } = this._el;

        switch (name) {
            // start "checked"
            case 'checked':
                _ui_input.value = value;
                break;
            // finish "checked"
            default:
                return;
        }
    };
    // finish "_renderState"

    _onChange = (e) => {
        const newValue = e.target.checked;
        this._updateState('checked', newValue);
    };

    _checkbox_class_name = () => {
        const { hasError } = this._prop;
        return clsx('form-check-input', { 'is-invalid': hasError === 'yes' }, { 'is-valid': hasError === 'no' });
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
            // checked,
            help,
            hasError,
            error,
            disabled,
            mandatory,
        } = this._prop;
        const { checked } = this._state;

        this._el.input = (
            <input id={this.labelFor} className="form-check-input" type="checkbox" checked={checked} onchange={this._onChange} disabled={disabled} />
        );
        this._el.label = <span>{label}</span>;
        this._el.help = this._ui_help();
        this._el.error = this._ui_error();

        return (
            <div className={className}>
                <div className="form-check">
                    {this._el.input}
                    <label for={this.labelFor} className="form-check-label fw-bold">
                        {this._el.label}
                        {mandatory && <span className="text-danger">&nbsp;*</span>}
                    </label>
                </div>
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
