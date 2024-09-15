import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';
import Checkbox from '../../atom/checkbox';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class SchoolYearForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, schoolYear } = settings;

        this._prop = {
            langId,
            schoolYearId: schoolYear.id,
            schoolYearCount: schoolYear.count,
            schoolYearCurrentId: schoolYear.currentId,
        };

        this._state = {};

        this._stateNameInput = {};
        this._atm.nameInput = (
            <Input className="col-12" label={i18n(langId, 'TTL_SCHOOLYEAR_NAME')} value={schoolYear.name} mandatory maxLength={100} />
        );
        this._updateStateInput(ID.SYF_INPUT_NAME_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateStartDateInput = {};
        this._atm.startDateInput = (
            <Input type={'date'} className="flex-grow-1" label={i18n(langId, 'TTL_SCHOOLYEAR_START_DATE')} value={schoolYear.startDate} mandatory />
        );
        this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        this._stateFinishDateInput = {};
        this._atm.finishDateInput = (
            <Input type={'date'} className="flex-grow-1" label={i18n(langId, 'TTL_SCHOOLYEAR_FINISH_DATE')} value={schoolYear.finishDate} mandatory />
        );
        this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, {
            disabled: false,
            hasError: 'unknown',
        });

        let isCurrentDisabled = false;
        let isCurrentValue = schoolYear.isCurrent;
        if (schoolYear.count === 0) {
            isCurrentValue = true;
        }

        if (isCurrentValue) {
            isCurrentDisabled = true;
        }

        this._stateIsCurrentCheckbox = {};
        this._atm.isCurrentCheckbox = <Checkbox className="col-12" label={i18n(langId, 'TTL_SCHOOLYEAR_IS_CURRENT')} checked={isCurrentValue} />;
        this._updateStateIsCurrentCheckbox({
            disabled: isCurrentDisabled,
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
        const name = this._atm.nameInput.getState('value');
        const startDate = this._atm.startDateInput.getState('value');
        const finishDate = this._atm.finishDateInput.getState('value');
        const isCurrent = this._atm.isCurrentCheckbox.getState('checked');

        const { hasError, data } = this._validateFormData(name, startDate, finishDate);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { schoolYearId } = this._prop;

            this._callSaveSchoolYear({ id: schoolYearId, name, startDate, finishDate, isCurrent });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (name, startDate, finishDate) => {
        let data = {};

        let hasError = false;
        if (name.length === 0) {
            data[ID.SYF_INPUT_NAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (startDate.length === 0) {
            data[ID.SYF_INPUT_START_DATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (finishDate.length === 0) {
            data[ID.SYF_INPUT_FINISH_DATE_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (startDate.length > 0 && finishDate.length > 0) {
            const startTime = new Date(startDate).getTime();
            const finishTime = new Date(finishDate).getTime();

            if (startTime > finishTime) {
                data[ID.SYF_INPUT_START_DATE_ID] = { code: 'MSG_FIELD_START_DATE_IS_GREAT_THAN_FINISH_DATE', args: [] };
                hasError = true;
            }
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateInput(ID.SYF_INPUT_NAME_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, { disabled: false, hasError: 'no', error: null });
            this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, { disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateInput(ID.SYF_INPUT_NAME_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, { disabled: false, hasError: 'undefine', error: null });
            this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, { disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.SYF_INPUT_NAME_ID] !== 'undefined') {
            this._updateStateInput(ID.SYF_INPUT_NAME_ID, { disabled: false, hasError: 'yes', error: data[ID.SYF_INPUT_NAME_ID] });
        } else {
            this._updateStateInput(ID.SYF_INPUT_NAME_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SYF_INPUT_START_DATE_ID] !== 'undefined') {
            this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, { disabled: false, hasError: 'yes', error: data[ID.SYF_INPUT_START_DATE_ID] });
        } else {
            this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, { disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SYF_INPUT_FINISH_DATE_ID] !== 'undefined') {
            this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, { disabled: false, hasError: 'yes', error: data[ID.SYF_INPUT_FINISH_DATE_ID] });
        } else {
            this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, { disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveSchoolYear = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateInput(ID.SYF_INPUT_NAME_ID, { disabled: true });
        this._updateStateInput(ID.SYF_INPUT_START_DATE_ID, { disabled: true });
        this._updateStateInput(ID.SYF_INPUT_FINISH_DATE_ID, { disabled: true });
        this._updateStateInput(ID.SYF_CHECKBOX_IS_CURRENT_ID, { disabled: true });
    };

    _afterCallSaveSchoolYear = (payload) => {
        const { schoolYearCount, schoolYearCurrentId, schoolYearId } = this._prop;

        if (schoolYearCount > 0 && schoolYearCurrentId !== schoolYearId) {
            this._updateStateInput(ID.SYF_CHECKBOX_IS_CURRENT_ID, { disabled: false });
        }

        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveSchoolYear = async (payload) => {
        this._beforeCallSaveSchoolYear();
        try {
            const resp = await fetcher('saveSchoolYear', payload);

            if (resp.status === 'ok') {
                openSiteURL('schoolyear-list.php');
            }

            this._afterCallSaveSchoolYear({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveSchoolYear({ status: 'error' });
        }
    };

    _updateStateInput = (entity, state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        let stateNameInput;
        let nameInput;
        switch (entity) {
            case ID.SYF_INPUT_NAME_ID:
                stateNameInput = this._stateNameInput;
                nameInput = this._atm.nameInput;
                break;
            case ID.SYF_INPUT_START_DATE_ID:
                stateNameInput = this._stateStartDateInput;
                nameInput = this._atm.startDateInput;
                break;
            case ID.SYF_INPUT_FINISH_DATE_ID:
                stateNameInput = this._stateFinishDateInput;
                nameInput = this._atm.finishDateInput;
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

    _updateStateIsCurrentCheckbox = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateIsCurrentCheckbox = {
            disabled: disabled ?? this._stateIsCurrentCheckbox.disabled,
            hasError: hasError ?? this._stateIsCurrentCheckbox.hasError,
            error: error ?? this._stateIsCurrentCheckbox.error,
        };

        if (disabled !== null) {
            this._atm.isCurrentCheckbox.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.isCurrentCheckbox.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.isCurrentCheckbox.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.isCurrentCheckbox.updateProp('error', i18n(langId, error.code, error.args));
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
            <div className="mt-0 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.nameInput}
                    <div className="d-flex flex-wrap gap-3">
                        {this._atm.startDateInput}
                        {this._atm.finishDateInput}
                    </div>
                    {this._atm.isCurrentCheckbox}
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
