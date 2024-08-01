import { el, mount } from '../../node_modules/redom/dist/redom.es';

import i18n from '../shared/i18n/index';
import { commonEventManager, EventManager } from '../shared/eventManager';

export default class Notificator {
  _el = {};

  constructor(settings = {}) {
    const { langId = 'ru', message = null, show = false } = settings;

    this._prop = {
      langId,
    };

    this._state = {
      message,
      show,
    };

    this.el = this._ui_render();

    this._updateState(this._state);

    commonEventManager.subscribe('changeLang', this._changeLang);
    commonEventManager.subscribe('showMessage', this._showMessage);
    commonEventManager.subscribe('hideMessage', this._hideMessage);
  }

  _changeLang = (langId) => {
    this._prop.langId = langId;
    this._updateState(this._state);
  };

  _showMessage = (message) => {
    if (this._state.show) {
      this._updateState({ message });
    } else {
      this._updateState({ show: true, message });
    }
  };

  _hideMessage = () => {
    if (!this._state.show) return;

    this._updateState({ show: false });
  };

  _updateState = (state) => {
    const { message = null, show = null } = state;
    const { langId } = this._prop;

    this._state = {
      message: message ?? this._state.message,
      show: show ?? this._state.show,
    };

    if (message !== null) {
      const text = i18n(langId, message.code, message.args);
      if (typeof text === 'string') {
        this._el.content.innerText = i18n(langId, message.code, message.args);
      } else {
        this._el.content = mount(this._el.contentParent, text, this._el.content, true);
      }
    }

    if (show !== null) {
      if (show) {
        this._el.widget.classList.remove('d-none');
      } else {
        this._el.widget.classList.add('d-none');
      }
    }
  };

  _ui_render = () => {
    this._el.contentParent = <div>{(this._el.content = <p class="m-0"></p>)}</div>;

    this._el.widget = (
      <div class="alert alert-danger rounded-0 text-center m-0" role="alert">
        {this._el.contentParent}
      </div>
    );

    return this._el.widget;
  };
}
