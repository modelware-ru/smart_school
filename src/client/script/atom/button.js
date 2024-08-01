import { el, mount } from '../../node_modules/redom/dist/redom.es';
import Atom from './atom';

export default class Button extends Atom {
  constructor(settings = {}) {
    super();
    const { title = '', className = 'btn', isLoading = false, disabled = false, onClick = null } = settings;

    this._prop = {
      title,
      className,
      isLoading,
      disabled,
    };
    this._callback = {
      onClick,
    };

    this.el = this._ui_render();
  }

  _renderProp = (name, value) => {
    let { btn: _ui_btn, btnTitle: _ui_btnTitle, spinner: _ui_spinner } = this._el;

    switch (name) {
      case 'disabled': {
        if (value) {
          _ui_btn.setAttribute('disabled', '');
        } else {
          _ui_btn.removeAttribute('disabled');
        }
        break;
      }
      case 'title': {
        _ui_btnTitle.innerText = value;
        break;
      }
      case 'isLoading': {
        this._el.spinner = mount(_ui_btn, this._ui_spinner(), _ui_spinner, true);
        break;
      }
      default:
        return;
    }
  };

  _onClick = (e) => {
    const { onClick } = this._callback;
    onClick && onClick();
  };

  _ui_spinner = () => {
    const { isLoading } = this._prop;
    return isLoading ? <span className="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> : <span></span>;
  };

  _ui_render = () => {
    const { title, className, isLoading, disabled } = this._prop;

    this._el.btn = (
      <button type="button" className={className} disabled={disabled} onclick={this._onClick}>
        {(this._el.spinner = this._ui_spinner(isLoading))}
        {(this._el.btnTitle = <span role="status">{title}</span>)}
      </button>
    );
    return this._el.btn;
  };
}
