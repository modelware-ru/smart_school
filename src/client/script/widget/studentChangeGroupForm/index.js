import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';
import Textarea from '../../atom/textarea';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentChangeClassForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, studentIdList, groupList } = settings;

        this._prop = {
            langId,
            studentIdList,
            groupList,
        };

        this._state = {};

        this._stateStartDateInput = {};
        this._atm.startDateInput = (
            <Input type="date" className="col-12" label={i18n(langId, 'TTL_DATE')} value={new Date().toISOString().substring(0, 10)} mandatory />
        );
        this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateGroupSelect = {};
        this._atm.groupSelect = (
            <Select className="col-12" label={i18n(langId, 'TTL_GROUP')} value={'0'} optionData={groupList} mandatory />
        );
        this._updateStateGroupSelect({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateReasonTextarea = {};
        this._atm.reasonTextarea = <Textarea className="col-12" label={i18n(langId, 'TTL_REASON')} value={''} />;
        this._updateStateReasonTextareaInput({
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
        const startDate = this._atm.startDateInput.getState('value');
        const groupId = parseInt(this._atm.groupSelect.getState('value'));
        const reason = this._atm.reasonTextarea.getState('value');

        const { hasError, data } = this._validateFormData(startDate, groupId);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { studentIdList } = this._prop;

            this._callChangeGroup({ startDate, groupId, reason, studentIdList });
        }
    };

    _onCancelButtonClick = () => {
        openSiteURL('student-list.php');
    };

    _validateFormData = (startDate, groupId) => {
        let data = {};
        let hasError = false;

        if (startDate.length === 0) {
            data[ID.CGF_INPUT_START_DATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (groupId === 0) {
            data[ID.CGF_SELECT_GROUP_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateGroupSelect({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateGroupSelect({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.CGF_INPUT_START_DATE_ID] !== 'undefined') {
            this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, { disabled: false, hasError: 'yes', error: data[ID.CGF_INPUT_START_DATE_ID] });
        } else {
            this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.CGF_SELECT_GROUP_ID] !== 'undefined') {
            this._updateStateGroupSelect({ disabled: false, hasError: 'yes', error: data[ID.CGF_SELECT_GROUP_ID] });
        } else {
            this._updateStateGroupSelect({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallChangeGroup = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateInput(ID.CGF_INPUT_START_DATE_ID, { disabled: true });
        this._updateStateGroupSelect({ disabled: true });
    };

    _afterCallChangeGroup = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callChangeGroup = async (payload) => {
        this._beforeCallChangeGroup();
        try {
            const resp = await fetcher('changeGroup', payload);

            if (resp.status === 'ok') {
                openSiteURL('student-list.php');
            }

            this._afterCallChangeGroup({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallChangeGroup({ status: 'error' });
        }
    };

    _updateStateInput = (entity, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateNameInput;
        let nameInput;
        switch (entity) {
            case ID.CGF_INPUT_START_DATE_ID:
                stateNameInput = this._stateStartDateInput;
                nameInput = this._atm.startDateInput;
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

    _updateStateGroupSelect = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateGroupSelect = {
            disabled: disabled ?? this._stateGroupSelect.disabled,
            hasError: hasError ?? this._stateGroupSelect.hasError,
            error: error ?? this._stateGroupSelect.error,
        };

        if (disabled !== null) {
            this._atm.groupSelect.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.groupSelect.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.groupSelect.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.groupSelect.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateReasonTextareaInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateReasonTextarea = {
            disabled: disabled ?? this._stateReasonTextarea.disabled,
            hasError: hasError ?? this._stateReasonTextarea.hasError,
            error: error ?? this._stateReasonTextarea.error,
        };

        if (disabled !== null) {
            this._atm.reasonTextarea.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.reasonTextarea.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.reasonTextarea.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.reasonTextarea.updateProp('error', i18n(langId, error.code, error.args));
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
        const { activeTeacherList, teacherListInGroup } = this._prop;

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.startDateInput}
                    {this._atm.groupSelect}
                    {this._atm.reasonTextarea}
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
