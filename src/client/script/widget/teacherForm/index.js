import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';
import SelectMenu from '../../widget/selectMenu/index';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL, validateEmail } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class TeacherForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, teacher, roleStateList, groupList, groupListForTeacher } = settings;

        this._prop = {
            langId,
            teacherId: teacher.id,
            roleStateList,
            groupList,
            groupListForTeacher,
        };

        this._state = {};

        this._stateLoginInput = {};
        this._atm.loginInput = <Input className="col-12" label={i18n(langId, 'TTL_LOGIN')} value={teacher.login} mandatory maxLength={50} />;
        this._updateStateInput('login', {
            disabled: false,
            hasError: 'unknown',
        });

        this._statePasswordInput = {};
        this._atm.passwordInput = (
            <Input
                className="col-12"
                label={i18n(langId, 'TTL_PASSWORD')}
                value={teacher.password}
                mandatory={teacher.id === 0}
                maxLength={20}
                help={teacher.id !== 0 && i18n(langId, 'TTL_PASSWORD_HELP')}
            />
        );
        this._updateStateInput('password', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateFirstNameInput = {};
        this._atm.firstNameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_FIRST_NAME')} value={teacher.firstName} mandatory maxLength={100} />
        );
        this._updateStateInput('first', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateLastNameInput = {};
        this._atm.lastNameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_LAST_NAME')} value={teacher.lastName} mandatory maxLength={100} />
        );
        this._updateStateInput('last', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateMiddleNameInput = {};
        this._atm.middleNameInput = <Input className="col-12" label={i18n(langId, 'TTL_MIDDLE_NAME')} value={teacher.middleName} maxLength={100} />;
        this._updateStateInput('middle', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateEmailInput = {};
        this._atm.emailInput = <Input className="col-12" label={i18n(langId, 'TTL_EMAIL')} value={teacher.email} mandatory maxLength={100} />;
        this._updateStateInput('email', {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateRoleStateSelect = {};
        this._atm.roleStateSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_STATE')} value={teacher.roleStateId} optionData={roleStateList} mandatory />
        );
        this._updateStateRoleStateSelect({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateSaveButton = {};
        this._atm.saveButton = <Button className="btn btn-success" onClick={this._onSaveButtonClick} />;
        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
            icon: 'bi-floppy',
        });

        this.el = this._ui_render();
    }

    _onSaveButtonClick = () => {
        const login = this._atm.loginInput.getState('value');
        const firstName = this._atm.firstNameInput.getState('value');
        const lastName = this._atm.lastNameInput.getState('value');
        const middleName = this._atm.middleNameInput.getState('value');
        const email = this._atm.emailInput.getState('value');
        const password = this._atm.passwordInput.getState('value');
        const roleStateId = parseInt(this._atm.roleStateSelect.getState('value'));

        const { hasError, data } = this._validateFormData(firstName, lastName, roleStateId, login, email, password);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { teacherId } = this._prop;
            const groupList = this._el.selectMenu.getState('itemList').map((item) => parseInt(item));

            this._callSaveTeacher({ id: teacherId, firstName, lastName, middleName, roleStateId, groupList, login, email, password });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (firstName, lastName, roleStateId, login, email, password) => {
        const { teacherId } = this._prop;
        let data = {};
        let hasError = false;
        if (login.length === 0) {
            data[ID.TF_INPUT_LOGIN_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        if (firstName.length === 0) {
            data[ID.TF_INPUT_FIRSTNAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        if (lastName.length === 0) {
            data[ID.TF_INPUT_LASTNAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        if (!validateEmail(email)) {
            data[ID.TF_INPUT_EMAIL_ID] = { code: 'MSG_FIELD_EMAIL_INCORRECT', args: [] };
            hasError = true;
        }
        if (roleStateId === 0) {
            data[ID.TF_SELECT_ROLE_STATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        if (teacherId === 0 && password.length === 0) {
            data[ID.TF_INPUT_PASSWORD_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateInput('first', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('last', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('middle', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('login', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('email', { disabled: false, hasError: 'no', error: null });
            this._updateStateInput('password', { disabled: false, hasError: 'no', error: null });
            this._updateStateRoleStateSelect({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateInput('first', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('last', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('middle', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('login', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('email', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput('password', { disabled: false, hasError: 'undefine', error: null });
            this._updateStateRoleStateSelect({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.TF_INPUT_FIRSTNAME_ID] !== 'undefined') {
            this._updateStateInput('first', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_FIRSTNAME_ID] });
        } else {
            this._updateStateInput('first', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_INPUT_LASTNAME_ID] !== 'undefined') {
            this._updateStateInput('last', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_LASTNAME_ID] });
        } else {
            this._updateStateInput('last', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_INPUT_MIDDLENAME_ID] !== 'undefined') {
            this._updateStateInput('middle', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_MIDDLENAME_ID] });
        } else {
            this._updateStateInput('middle', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_SELECT_ROLE_STATE_ID] !== 'undefined') {
            this._updateStateRoleStateSelect({ disabled: false, hasError: 'yes', error: data[ID.TF_SELECT_ROLE_STATE_ID] });
        } else {
            this._updateStateRoleStateSelect({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_INPUT_LOGIN_ID] !== 'undefined') {
            this._updateStateInput('login', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_LOGIN_ID] });
        } else {
            this._updateStateInput('login', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_INPUT_EMAIL_ID] !== 'undefined') {
            this._updateStateInput('email', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_EMAIL_ID] });
        } else {
            this._updateStateInput('email', { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.TF_INPUT_PASSWORD_ID] !== 'undefined') {
            this._updateStateInput('password', { disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_PASSWORD_ID] });
        } else {
            this._updateStateInput('password', { disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveTeacher = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateInput('first', { disabled: true });
        this._updateStateInput('last', { disabled: true });
        this._updateStateInput('middle', { disabled: true });
        this._updateStateInput('login', { disabled: true });
        this._updateStateInput('email', { disabled: true });
        this._updateStateInput('password', { disabled: true });
        this._updateStateRoleStateSelect({ disabled: true });
    };

    _afterCallSaveTeacher = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveTeacher = async (payload) => {
        this._beforeCallSaveTeacher();
        try {
            const resp = await fetcher('saveTeacher', payload);

            if (resp.status === 'ok') {
                openSiteURL('teacher-list.php');
            }

            this._afterCallSaveTeacher({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveTeacher({ status: 'error' });
        }
    };

    _updateStateInput = (name, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateNameInput;
        let nameInput;
        switch (name) {
            case 'first':
                stateNameInput = this._stateFirstNameInput;
                nameInput = this._atm.firstNameInput;
                break;
            case 'last':
                stateNameInput = this._stateLastNameInput;
                nameInput = this._atm.lastNameInput;
                break;
            case 'middle':
                stateNameInput = this._stateMiddleNameInput;
                nameInput = this._atm.middleNameInput;
                break;
            case 'login':
                stateNameInput = this._stateLoginInput;
                nameInput = this._atm.loginInput;
                break;
            case 'email':
                stateNameInput = this._stateEmailInput;
                nameInput = this._atm.emailInput;
                break;
            case 'password':
                stateNameInput = this._statePasswordInput;
                nameInput = this._atm.passwordInput;
                break;
            default:
                return;
        }

        stateNameInput['disabled'] = disabled ?? stateNameInput.disabled;
        stateNameInput['hasError'] = hasError ?? stateNameInput.hasError;
        stateNameInput['error'] = error ?? stateNameInput.error;

        if (disabled !== null) {
            nameInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            nameInput.updateProp('hasError', hasError);
        }
        if (error !== null && nameInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            nameInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };
    _updateStateRoleStateSelect = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateRoleStateSelect = {
            disabled: disabled ?? this._stateRoleStateSelect.disabled,
            hasError: hasError ?? this._stateRoleStateSelect.hasError,
            error: error ?? this._stateRoleStateSelect.error,
        };

        if (disabled !== null) {
            this._atm.roleStateSelect.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.roleStateSelect.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.roleStateSelect.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.roleStateSelect.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateSaveButton = (state) => {
        const { disabled = null, title = null, isLoading = null, icon = null } = state;
        const { langId } = this._prop;

        this._stateSaveButton = {
            disabled: disabled ?? this._stateSaveButton.disabled,
            title: title ?? this._stateSaveButton.title,
            isLoading: isLoading ?? this._stateSaveButton.isLoading,
            icon: icon ?? this._stateSaveButton.icon,
        };

        if (disabled !== null) {
            this._atm.saveButton.updateProp('disabled', disabled);
        }

        if (isLoading !== null) {
            this._atm.saveButton.updateProp('isLoading', isLoading);
        }

        if (title !== null) {
            this._atm.saveButton.updateProp('title', i18n(langId, title));
        }

        if (icon !== null) {
            this._atm.saveButton.updateProp('icon', icon);
        }
    };

    _ui_render = () => {
        const { langId } = this._prop;
        const { groupList, groupListForTeacher } = this._prop;

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.loginInput}
                    {this._atm.passwordInput}
                    {this._atm.firstNameInput}
                    {this._atm.lastNameInput}
                    {this._atm.middleNameInput}
                    {this._atm.emailInput}
                    {this._atm.roleStateSelect}
                    <hr />
                    <label className="form-label fw-bold my-0">{i18n(langId, 'TTL_TEACHER_GROUPS')}:</label>
                    {(this._el.selectMenu = <SelectMenu itemContent={groupList} itemList={groupListForTeacher} />)}
                </div>
                <div className="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    {this._atm.saveButton}
                    <Button
                        className="btn btn-outline-secondary"
                        icon={'bi-x-circle'}
                        title={i18n(langId, 'TTL_TO_CANCEL')}
                        onClick={this._onCancelButtonClick}
                    />
                </div>
            </div>
        );
    };
}
