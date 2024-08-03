import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class SignInForm {
  _el = {};
  _atm = {};

  constructor(settings = {}) {
    const { langId } = settings;

    this._prop = {
      langId,
    };

    this._state = {
    };

    this._stateLoginInput = {};
    this._atm.loginInput = <Input className="col-12" label={i18n(langId, 'TTL_LOGIN')} mandatory />;
    this._updateStateLoginInput({
      disabled: false,
      hasError: 'unknown',
    });

    this._statePasswordInput = {};
    this._atm.passwordInput = <Input className="col-12" label={i18n(langId, 'TTL_PASSWORD')} mandatory />;
    this._updateStatePasswordInput({
      disabled: false,
      hasError: 'unknown',
    });

    this._stateAuthButton = {};
    this._atm.authButton = <Button className="w-100 btn btn-primary" onClick={this._onAuthButtonClick} />;
    this._updateStateAuthButton({
      disabled: false,
      title: 'TTL_TO_SIGN_IN',
      isLoading: false,
      icon: 'bi-box-arrow-in-right'
    });

    this.el = this._ui_render();
  }

  _onAuthButtonClick = () => {
    const login = this._atm.loginInput.getState('value');
    const password = this._atm.passwordInput.getState('value');

    const { hasError, data } = this._validateFormData(login, password);

    this._showError({ status: hasError ? 'fail' : 'ok', data });

    if (!hasError) {
      this._callSignIn({ login, password });
    }
  };

  _validateFormData = (login, password) => {
    let data = {};
    let hasError = false;
    if (login.length === 0) {
      data[ID.SIF_INPUT_LOGIN_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
      hasError = true;
    }

    if (password.length === 0) {
      data[ID.SIF_INPUT_PASSWORD_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
      hasError = true;
    }
    return { hasError, data };
  };

  _showError = ({ status, data }) => {
    if (status === 'ok') {
      this._updateStateLoginInput({ disabled: false, hasError: 'no', error: null });
      this._updateStatePasswordInput({ disabled: false, hasError: 'no', error: null });
      return;
    }

    if (status === 'error') {
      this._updateStateLoginInput({ disabled: false, hasError: 'undefine', error: null });
      this._updateStatePasswordInput({ disabled: false, hasError: 'undefine', error: null });
      return;
    }

    if (typeof data[ID.SIF_INPUT_LOGIN_ID] !== 'undefined') {
      this._updateStateLoginInput({ disabled: false, hasError: 'yes', error: data[ID.SIF_INPUT_LOGIN_ID] });
    } else {
      this._updateStateLoginInput({ disabled: false, hasError: 'no', error: null });
    }

    if (typeof data[ID.SIF_INPUT_PASSWORD_ID] !== 'undefined') {
      this._updateStatePasswordInput({ disabled: false, hasError: 'yes', error: data[ID.SIF_INPUT_PASSWORD_ID] });
    } else {
      this._updateStatePasswordInput({ disabled: false, hasError: 'no', error: null });
    }
  };

  _beforeCallSignIn = () => {
    this._updateStateAuthButton({ disabled: true, isLoading: true, title: 'TTL_TO_AUTHENTICATE', icon: '' });
    this._updateStateLoginInput({ disabled: true });
    this._updateStatePasswordInput({ disabled: true });
  }

  _afterCallSignIn = (payload) => {
    const { status } = payload;

    this._showError(payload);

    if (status !== 'ok') {
      this._updateStateAuthButton({
        disabled: false,
        title: 'TTL_TO_SIGN_IN',
        isLoading: false,
        icon: 'bi-box-arrow-in-right'
      });
    }
  };

  _callSignIn = async (payload) => {

    this._beforeCallSignIn();
    try {
      const resp = await fetcher('signin', payload, 'api/v1');

      this._afterCallSignIn({ status: resp.status, data: resp.data });

      if (resp.status === 'ok') {
        openSiteURL('index.php');
      }
    } catch (e) {
      debugger;
      this._afterCallSignIn({ status: 'error' });
    }
  };

  _updateStateLoginInput = (state) => {
    const { disabled = null, hasError = null, error = null } = state;
    const { langId } = this._prop;

    this._stateLoginInput = {
      disabled: disabled ?? this._stateLoginInput.disabled,
      hasError: hasError ?? this._stateLoginInput.hasError,
      error: error ?? this._stateLoginInput.error,
    };

    if (disabled !== null) {
      this._atm.loginInput.updateProp('disabled', disabled);
    }
    if (hasError !== null) {
      this._atm.loginInput.updateProp('hasError', hasError);
    }
    if (error !== null && this._atm.loginInput.getProp('error') !== i18n(langId, error.code, error.args)) {
      this._atm.loginInput.updateProp('error', i18n(langId, error.code, error.args));
    }
  };

  _updateStatePasswordInput = (state) => {
    const { disabled = null, hasError = null, error = null } = state;
    const { langId } = this._prop;

    this._statePasswordInput = {
      disabled: disabled ?? this._statePasswordInput.disabled,
      hasError: hasError ?? this._statePasswordInput.hasError,
      error: error ?? this._statePasswordInput.error,
    };

    if (disabled !== null) {
      this._atm.passwordInput.updateProp('disabled', disabled);
    }
    if (hasError !== null) {
      this._atm.passwordInput.updateProp('hasError', hasError);
    }
    if (error !== null && this._atm.passwordInput.getProp('error') !== i18n(langId, error.code, error.args)) {
      this._atm.passwordInput.updateProp('error', i18n(langId, error.code, error.args));
    }
  };

  _updateStateAuthButton = (state) => {
    const { disabled = null, title = null, isLoading = null, icon = null } = state;
    const { langId } = this._prop;

    this._stateAuthButton = {
      disabled: disabled ?? this._stateAuthButton.disabled,
      title: title ?? this._stateAuthButton.title,
      isLoading: isLoading ?? this._stateAuthButton.isLoading,
      icon: icon ?? this._stateAuthButton.icon,
    };

    if (disabled !== null) {
      this._atm.authButton.updateProp('disabled', disabled);
    }

    if (isLoading !== null) {
      this._atm.authButton.updateProp('isLoading', isLoading);
    }

    if (title !== null) {
      this._atm.authButton.updateProp('title', i18n(langId, title));
    }

    if (icon !== null) {
      this._atm.authButton.updateProp('icon', icon);
    }

  };

  _ui_render = () => {
    const { langId } = this._prop;

    return (
      <div>
        <div className="py-3 text-center">
          <h2>{i18n(langId, 'TTL_SIGN_IN')}</h2>
        </div>
        <form class="mt-3 row gx-0 gy-3">
          {this._atm.loginInput}
          {this._atm.passwordInput}
          {this._atm.authButton}
          <a href="recoveryPassword.php" class="link-primary text-center">{i18n(langId, 'TTL_RECOVERY_PASSWORD')}</a>
        </form>
      </div>
    );
  };
}


{/* <form class="mt-3 row gx-0 gy-3">
@@include('../atom/input.html', {
  "class": "col-12",
  "label": "Логин",
  "placeholder": "login"
})

@@include('../atom/input.html', {
  "class": "col-12",
  "label": "Пароль",
  "placeholder": "*****"
})

@@include('../atom/button.html', {
  "class": "w-100 btn btn-primary",
  "isLoading": false,
  "icon": '',
  "title": "Войти"
})

@@include('../atom/button.html', {
  "class": "w-100 btn btn-primary disabled",
  "isLoading": true,
  "icon": '',
  "title": "Вход..."
})

<a href="recoveryPassword.html" class="link-primary text-center">Восстановление пароля</a>

</form>
</div>       */}

