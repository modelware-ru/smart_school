import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';
import DynamicList from '../../atom/dynamicList';

import { factory as twoBindSelectFactory } from '../twoBindSelect/index';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class TaskForm {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, task, topicList, topicSubtopicList } = settings;

        this._prop = {
            langId,
            taskId: task.id,
            data: {
                optionData: {
                    first: topicList,
                    second: topicSubtopicList,
                },
            },
        };

        this._state = {
            value: task.topicSubtopicList,
        };

        this._stateNameInput = {};
        this._atm.nameInput = <Input className="col-12" label={i18n(langId, 'TTL_TASK_NAME')} value={task.name} mandatory maxLength={100} />;
        this._updateStateNameInput({
            disabled: false,
            hasError: 'unknown',
        });

        this._atm.topicSubtopicListSelect = (
            <DynamicList
                langId={langId}
                factory={{
                    data : this._prop.data,
                    creator: twoBindSelectFactory,
                }}
                defaultValue={{
                    first: '0',
                    second: '0',
                }}
                value={this._state.value}
            />
        );

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
        const topicSubtopicList = this._atm.topicSubtopicListSelect.state();

        const subtopicList = topicSubtopicList.reduce( (acc, item) => {
            if (!acc.includes(parseInt(item['second']))) {
                acc.push(parseInt(item['second']));
            }
            return acc;
        }, []);

        const { hasError, data } = this._validateFormData(name);

        this._showError({ status: hasError ? 'fail' : 'ok', data });

        commonEventManager.dispatch('hideMessage');

        if (!hasError) {
            const { taskId } = this._prop;

            this._callSaveTask({ id: taskId, name, subtopicList });
        }
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _validateFormData = (name) => {
        let data = {};
        let hasError = false;
        if (name.length === 0) {
            data[ID.TF_INPUT_NAME_ID] = { code: 'MSG_FIELD_IS_REQUIRED', args: [] };
            hasError = true;
        }

        return { hasError, data };
    };

    _showError = ({ status, data }) => {
        if (status === 'ok') {
            this._updateStateNameInput({ disabled: false, hasError: 'no', error: null });
            return;
        }

        if (status === 'error') {
            this._updateStateNameInput({ disabled: false, hasError: 'undefine', error: null });
            return;
        }

        if (typeof data[ID.TF_INPUT_NAME_ID] !== 'undefined') {
            this._updateStateNameInput({ disabled: false, hasError: 'yes', error: data[ID.TF_INPUT_NAME_ID] });
        } else {
            this._updateStateNameInput({ disabled: false, hasError: 'undefined', error: null });
        }
    };

    _beforeCallSaveTask = () => {
        this._updateStateSaveButton({ disabled: true, isLoading: true, title: 'TTL_TO_SAVE_IN_PROGRESS' });
        this._updateStateNameInput({ disabled: true });
    };

    _afterCallSaveTask = (payload) => {
        this._showError(payload);

        this._updateStateSaveButton({
            disabled: false,
            title: 'TTL_TO_SAVE',
            isLoading: false,
        });
    };

    _callSaveTask = async (payload) => {
        this._beforeCallSaveTask();
        try {
            const resp = await fetcher('saveTask', payload);

            if (resp.status === 'ok') {
                openSiteURL('task-list.php');
            }

            this._afterCallSaveTask({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallSaveTask({ status: 'error' });
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

    // _updateStateTopicSelect = (state) => {
    //     const { disabled = null, hasError = null, error = null } = state;
    //     const { langId } = this._prop;

    //     this._stateTopicSelect = {
    //         disabled: disabled ?? this._stateTopicSelect.disabled,
    //         hasError: hasError ?? this._stateTopicSelect.hasError,
    //         error: error ?? this._stateTopicSelect.error,
    //     };

    //     if (disabled !== null) {
    //         this._atm.topicSelect.updateProp('disabled', disabled);
    //     }
    //     if (hasError !== null) {
    //         this._atm.topicSelect.updateProp('hasError', hasError);
    //     }
    //     if (error !== null && this._atm.topicSelect.getProp('error') !== i18n(langId, error.code, error.args)) {
    //         this._atm.topicSelect.updateProp('error', i18n(langId, error.code, error.args));
    //     }
    // };

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
                    {this._atm.nameInput}
                    <label className="form-label fw-bold">{i18n(langId, 'TTL_TOPIC_NAME_LIST')}</label>
                    {this._atm.topicSubtopicListSelect}
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
