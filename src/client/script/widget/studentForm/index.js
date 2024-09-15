import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL, validateEmail } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, student } = settings;

        this._prop = {
            langId,
            studentId: student.id,
        };

        this._state = {};

        this._stateFirstNameInput = {};
        this._atm.firstNameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_FIRST_NAME')} value={student.firstName} mandatory maxLength={100} />
        );
        this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateLastNameInput = {};
        this._atm.lastNameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_LAST_NAME')} value={student.lastName} mandatory maxLength={100} />
        );
        this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateMiddleNameInput = {};
        this._atm.middleNameInput = <Input className="col-12" label={i18n(langId, 'TTL_MIDDLE_NAME')} value={student.middleName} maxLength={100} />;
        this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, {
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
        const firstName = this._atm.firstNameInput.getState('value');
        const lastName = this._atm.lastNameInput.getState('value');
        const middleName = this._atm.middleNameInput.getState('value');

        const { hasError, data } = this._validateFormData(firstName, lastName);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { studentId } = this._prop;

            this._callSaveStudent({ id: studentId, firstName, lastName, middleName });
        }
    };

    _onCancelButtonClick = () => {
        openSiteURL('student-list.php');
    };

    _validateFormData = (firstName, lastName) => {
        let data = {};
        let hasError = false;

        if (firstName.length === 0) {
            data[ID.SF_INPUT_FIRSTNAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }
        if (lastName.length === 0) {
            data[ID.SF_INPUT_LASTNAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, { disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, { disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.SF_INPUT_FIRSTNAME_ID] !== 'undefined') {
            this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, { disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_FIRSTNAME_ID] });
        } else {
            this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SF_INPUT_LASTNAME_ID] !== 'undefined') {
            this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, { disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_LASTNAME_ID] });
        } else {
            this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SF_INPUT_MIDDLENAME_ID] !== 'undefined') {
            this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, { disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_MIDDLENAME_ID] });
        } else {
            this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, { disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveStudent = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateInput(ID.SF_INPUT_FIRSTNAME_ID, { disabled: true });
        this._updateStateInput(ID.SF_INPUT_LASTNAME_ID, { disabled: true });
        this._updateStateInput(ID.SF_INPUT_MIDDLENAME_ID, { disabled: true });
    };

    _afterCallSaveStudent = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveStudent = async (payload) => {
        this._beforeCallSaveStudent();
        try {
            const resp = await fetcher('saveStudent', payload);

            if (resp.status === 'ok') {
                openSiteURL('student-list.php');
            }

            this._afterCallSaveStudent({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveStudent({ status: 'error' });
        }
    };

    _updateStateInput = (name, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateNameInput;
        let nameInput;
        switch (name) {
            case ID.SF_INPUT_FIRSTNAME_ID:
                stateNameInput = this._stateFirstNameInput;
                nameInput = this._atm.firstNameInput;
                break;
            case ID.SF_INPUT_LASTNAME_ID:
                stateNameInput = this._stateLastNameInput;
                nameInput = this._atm.lastNameInput;
                break;
            case ID.SF_INPUT_MIDDLENAME_ID:
                stateNameInput = this._stateMiddleNameInput;
                nameInput = this._atm.middleNameInput;
                break;
            default:
                return;
        }

        stateNameInput = {
            disabled: disabled ?? stateNameInput.disabled,
            hasError: hasError ?? stateNameInput.hasError,
            error: error ?? stateNameInput.error,
        };

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

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.lastNameInput}
                    {this._atm.firstNameInput}
                    {this._atm.middleNameInput}
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
