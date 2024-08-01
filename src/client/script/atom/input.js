import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

//TODO: Проверить, что help отрисовывается при отсутствии ошибок (error)
export default class Input extends Atom {

  labelFor = 'l' + new Date().getTime();

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
      onTest,
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

  _renderProp = (name, value) => {
    let { input: _ui_input, label: _ui_label, error: _ui_error, errorParent: _ui_errorParent } = this._el;

    switch (name) {
      case 'label':
        _ui_label.innerText = value;
        break;
      case 'placeholder':
        _ui_input.placeholder = value;
        break;
      case 'hasError':
      case 'error':
        this._el.error = mount(_ui_errorParent, this._ui_error(), _ui_error, true);
        _ui_input.className = this._inputClassName();
        break;
      case 'disabled':
        if (value) {
          _ui_input.setAttribute('disabled', '');
        } else {
          _ui_input.removeAttribute('disabled');
        }
        break;
      default:
        return;
    }
  };

  _renderState = (name, value) => {
    const { input: _ui_input, counter: _ui_counter } = this._el;

    switch (name) {
      case 'value':
        _ui_input.value = value;
        break;
      case 'availableCount':
        _ui_counter.innerText = value;
        break;
      default:
        return;
    }
  };

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

  _inputClassName = () => {
    const { hasError } = this._prop;
    return clsx('form-control', hasError === 'yes' && 'is-invalid', hasError === 'no' && 'is-valid');
  };

  _ui_help = () => {
    const { hasError, help } = this._prop;
    return <span className='text-secondary'>{hasError !== 'yes' && help}</span>;
  };

  _ui_error = () => {
    const { hasError, error } = this._prop;
    return <span className='text-danger'>{hasError === 'yes' && error}</span>;
  };

  _ui_render = () => {
    const { className, label, type, placeholder, maxLength, disabled, mandatory } = this._prop;
    const { value, availableCount } = this._state;

    this._el.input = (
      <input
        type={type}
        className={this._inputClassName()}
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
    this._el.counter = maxLength && <span className='text-secondary fw-bold'>{availableCount}</span>;

    return (
      <div className={className}>
        <label for={this.labelFor} className='form-label'>
          {this._el.label}
          {mandatory && <span className='text-danger'>&nbsp;*</span>}
        </label>
        {this._el.input}
        <div className='d-flex justify-content-between'>
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
}
