import { el, mount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';

//TODO: Проверить, что help отрисовывается при отсутствии ошибок (error)
export default class Textarea extends Atom {

  labelFor = 'l' + new Date().getTime();

  constructor(settings = {}) {
    super();
    const {
      className = '',
      label = '',
      placeholder = '',
      value = '',
      help = '',
      hasError = 'unknown', // 'yes', 'no', 'unknown'
      error = '',
      maxLength = null,
      rows = 3,
      resizable = false,
      disabled = false,
      mandatory = false,
      onTest,
    } = settings;

    this._prop = {
      className,
      label,
      placeholder,
      value, // initial value
      help,
      hasError,
      error,
      maxLength,
      rows,
      resizable,
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
    let { textarea: _ui_textarea, label: _ui_label, error: _ui_error, errorParent: _ui_errorParent } = this._el;

    switch (name) {
      case 'label':
        _ui_label.innerText = value;
        break;
      case 'placeholder':
        _ui_textarea.placeholder = value;
        break;
      case 'hasError':
      case 'error':
        this._el.error = mount(_ui_errorParent, this._ui_error(), _ui_error, true);
        _ui_textarea.className = this._textareaClassName();
        break;
      case 'disabled':
        if (value) {
          _ui_textarea.setAttribute('disabled', '');
        } else {
          _ui_textarea.removeAttribute('disabled');
        }
        break;
      default:
        return;
    }
  };

  _renderState = (name, value) => {
    const { textarea: _ui_textarea, counter: _ui_counter } = this._el;

    switch (name) {
      case 'value':
        _ui_textarea.value = value;
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

  _textareaClassName = () => {
    const { hasError, resizable } = this._prop;
    return clsx('form-control', hasError === 'yes' && 'is-invalid', hasError === 'no' && 'is-valid', !resizable && 'textarea-no-resizable');
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
    const { className, label, placeholder, maxLength, rows, disabled, mandatory } = this._prop;
    const { value, availableCount } = this._state;

    this._el.textarea = (
      <textarea
        className={this._textareaClassName()}
        id={this.labelFor}
        placeholder={placeholder}
        value={value}
        rows={rows}
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
        {this._el.textarea}
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
