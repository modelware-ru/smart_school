import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Select from '../../atom/select';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class TaskFormRemove {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, task } = settings;

        this._prop = {
            langId,
            taskId: task.id,
            topicSubtopicList: task.topicSubtopicList,
        };

        this._state = {};

        this._atm.nameInput = <Input className="col-12" label={i18n(langId, 'TTL_TASK_NAME')} value={task.name} disabled={true} />;

        this._stateRemoveButton = {};
        this._atm.removeButton = <Button className="btn btn-danger" onClick={this._onRemoveButtonClick} />;
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
            icon: 'bi-trash3',
        });

        this.el = this._ui_render();
    }

    _onRemoveButtonClick = () => {
        commonEventManager.dispatch('hideMessage');

        const { taskId } = this._prop;

        this._callRemoveTask({ id: taskId });
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _beforeCallRemoveTask = () => {
        this._updateStateRemoveButton({ disabled: true, isLoading: true, title: 'TTL_TO_REMOVE_IN_PROGRESS' });
    };

    _afterCallRemoveTask = (payload) => {
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
        });
    };

    _callRemoveTask = async (payload) => {
        this._beforeCallRemoveTask();
        try {
            const resp = await fetcher('removeTask', payload);

            if (resp.status === 'ok') {
                openSiteURL('task-list.php');
            }

            this._afterCallRemoveTask({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallRemoveTask({ status: 'error' });
        }
    };

    _updateStateRemoveButton = (state) => {
        const { disabled = null, title = null, isLoading = null, icon = null } = state;
        const { langId } = this._prop;

        this._stateRemoveButton = {
            disabled: disabled ?? this._stateRemoveButton.disabled,
            title: title ?? this._stateRemoveButton.title,
            isLoading: isLoading ?? this._stateRemoveButton.isLoading,
            icon: icon ?? this._stateRemoveButton.icon,
        };

        if (disabled !== null) {
            this._atm.removeButton.updateProp('disabled', disabled);
        }

        if (isLoading !== null) {
            this._atm.removeButton.updateProp('isLoading', isLoading);
        }

        if (title !== null) {
            this._atm.removeButton.updateProp('title', i18n(langId, title));
        }

        if (icon !== null) {
            this._atm.removeButton.updateProp('icon', icon);
        }
    };

    _ui_render = () => {
        const { langId, topicSubtopicList } = this._prop;

        return (
            <div className="mt-3 row gx-0 gy-3">
                <div className="bg-body-tertiary row border gy-3 m-0 pb-3">
                    {this._atm.nameInput}
                    <label className="form-label fw-bold">{i18n(langId, 'TTL_TOPIC_NAME_LIST')}</label>
                    <ul className="list-unstyled my-0">
                        {topicSubtopicList.map((item) => (
                            <li>{item['name']}</li>
                        ))}
                    </ul>
                </div>
                <div className="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    {this._atm.removeButton}
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
