import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Button from '../../atom/button';
import Textarea from '../../atom/textarea';
import LabelList from '../../widget/labelList/index';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class SerieForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, serie } = settings;

        this._prop = {
            langId,
            serieId: serie.id,
            serieTaskList: serie.taskList,
        };

        this._state = {};

        this._stateNameInput = {};
        this._atm.nameInput = <Input className="col-12" label={i18n(langId, 'TTL_SERIE_NAME')} value={serie.name} mandatory maxLength={100} />;
        this._updateStateNameInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateMaxValueClassInput = {};
        this._atm.maxValueClassInput = <Input className="col-3" label={i18n(langId, 'TTL_MAX_VALUE_CLASS')} value={serie.maxValueClass} mandatory />;
        this._updateStateMaxValueClassInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateMaxValueHomeInput = {};
        this._atm.maxValueHomeInput = <Input className="col-3 offset-1" label={i18n(langId, 'TTL_MAX_VALUE_HOME')} value={serie.maxValueHome} mandatory />;
        this._updateStateMaxValueHomeInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._stateNewTaskListTextarea = {};
        this._atm.newTaskListTextarea = (
            <Textarea
                className="col-12"
                label={i18n(langId, serie.id === 0 ? 'TTL_TASK_LIST' : 'TTL_NEW_TASK_LIST')}
                value={''}
                help={'В качестве разделителя названий задач используйте запятую (,)'}
            />
        );
        this._updateStateNewTaskListTextareaInput({
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

        if (serie.id === 0) {
            this._el.taskList = null;
        } else {
            this._el.taskList = <LabelList className="col-12" labelList={serie.taskList} title={'TTL_TASK_LIST'} />;
        }

        this.el = this._ui_render();
    }

    _onSaveButtonClick = () => {
        const { serieId } = this._prop;
        const name = this._atm.nameInput.getState('value');
        const maxValueClass = parseInt(this._atm.maxValueClassInput.getState('value'));
        const maxValueHome = parseInt(this._atm.maxValueHomeInput.getState('value'));
        const removedTaskIdList = serieId === 0 ? [] : this._el.taskList.getRemovedLabelIdList();
        const newTaskListStr = this._atm.newTaskListTextarea.getState('value');
        const newTaskList = newTaskListStr.split(',').reduce((curry, item) => {
            const task = item.trim();
            if (task.length > 0 && !curry.includes(task)) {
                curry.push(task);
            }
            return curry;
        }, []);

        const { hasError, data } = this._validateFormData(name, maxValueClass, maxValueHome);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { serieId } = this._prop;

            this._callSaveSerie({ id: serieId, name, maxValueClass, maxValueHome, removedTaskIdList, newTaskList });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (name, maxValueClass, maxValueHome) => {
        let data = {};
        let hasError = false;

        if (name.length === 0) {
            data[ID.SF_INPUT_NAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        if (!Number.isInteger(maxValueClass)) {
            data[ID.SF_INPUT_MAX_VALUE_CLASS] = { code: 'MSG_FIELD_VALUE_IS_NOT_VALID', args: [] };
            hasError = true;
        }

        if (!Number.isInteger(maxValueHome)) {
            data[ID.SF_INPUT_MAX_VALUE_HOME] = { code: 'MSG_FIELD_VALUE_IS_NOT_VALID', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateNameInput({ disabled: false, hasError: 'no', error: null });
            this._updateStateMaxValueClassInput({ disabled: false, hasError: 'no', error: null });
            this._updateStateMaxValueHomeInput({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateNameInput({ disabled: false, hasError: 'undefine', error: null });
            this._updateStateMaxValueClassInput({ disabled: false, hasError: 'undefine', error: null });
            this._updateStateMaxValueHomeInput({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.SF_INPUT_NAME_ID] !== 'undefined') {
            this._updateStateNameInput({ disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_NAME_ID] });
        } else {
            this._updateStateNameInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SF_INPUT_MAX_VALUE_CLASS] !== 'undefined') {
            this._updateStateMaxValueClassInput({ disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_MAX_VALUE_CLASS] });
        } else {
            this._updateStateMaxValueClassInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SF_INPUT_MAX_VALUE_HOME] !== 'undefined') {
            this._updateStateMaxValueHomeInput({ disabled: false, hasError: 'yes', error: data[ID.SF_INPUT_MAX_VALUE_HOME] });
        } else {
            this._updateStateMaxValueHomeInput({ disabled: false, hasError: 'undefined', error: null });
        }

        if (typeof data[ID.SF_TEXTAREA_TASK_LIST_ID] !== 'undefined') {
            this._updateStateNewTaskListTextareaInput({ disabled: false, hasError: 'yes', error: data[ID.SF_TEXTAREA_TASK_LIST_ID] });
        } else {
            this._updateStateNewTaskListTextareaInput({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveSerie = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateNameInput({ disabled: true });
        this._updateStateMaxValueClassInput({ disabled: true });
    };

    _afterCallSaveSerie = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveSerie = async (payload) => {
        this._beforeCallSaveSerie();
        try {
            const resp = await fetcher('saveSerie', payload);

            if (resp.status === 'ok') {
                openSiteURL('serie-list.php');
            }

            this._afterCallSaveSerie({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveSerie({ status: 'error' });
        }
    };

    _updateStateNameInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateNameInput = {
            disabled: disabled ?? this._stateNameInput.disabled,
            hasError: hasError ?? this._stateNameInput.hasError,
            error: error ?? this._stateNameInput.error,
        };

        if (disabled !== null) {
            this._atm.nameInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.nameInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.nameInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.nameInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateMaxValueClassInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateMaxValueClassInput = {
            disabled: disabled ?? this._stateMaxValueClassInput.disabled,
            hasError: hasError ?? this._stateMaxValueClassInput.hasError,
            error: error ?? this._stateMaxValueClassInput.error,
        };

        if (disabled !== null) {
            this._atm.maxValueClassInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.maxValueClassInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.maxValueClassInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.maxValueClassInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateMaxValueHomeInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateMaxValueHomeInput = {
            disabled: disabled ?? this._stateMaxValueHomeInput.disabled,
            hasError: hasError ?? this._stateMaxValueHomeInput.hasError,
            error: error ?? this._stateMaxValueHomeInput.error,
        };

        if (disabled !== null) {
            this._atm.maxValueHomeInput.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.maxValueHomeInput.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.maxValueHomeInput.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.maxValueHomeInput.updateProp('error', i18n(langId, error.code, error.args));
        }
    };

    _updateStateNewTaskListTextareaInput = (state) => {
        const { disabled = null, hasError = null, error = null } = state;
        const { langId } = this._prop;

        this._stateNewTaskListTextarea = {
            disabled: disabled ?? this._stateNewTaskListTextarea.disabled,
            hasError: hasError ?? this._stateNewTaskListTextarea.hasError,
            error: error ?? this._stateNewTaskListTextarea.error,
        };

        if (disabled !== null) {
            this._atm.newTaskListTextarea.updateProp('disabled', disabled);
        }
        if (hasError !== null) {
            this._atm.newTaskListTextarea.updateProp('hasError', hasError);
        }
        if (error !== null && this._atm.newTaskListTextarea.getProp('error') !== i18n(langId, error.code, error.args)) {
            this._atm.newTaskListTextarea.updateProp('error', i18n(langId, error.code, error.args));
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
                    <div className='d-flex'>
                    {this._atm.maxValueClassInput}
                    {this._atm.maxValueHomeInput}
                    </div>
                    {this._el.taskList}
                    {this._atm.newTaskListTextarea}
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
