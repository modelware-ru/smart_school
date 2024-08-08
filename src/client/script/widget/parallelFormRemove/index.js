import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import ID from './ids';
import i18n from '../../shared/i18n/index';
import Input from '../../atom/input';
import Checkbox from '../../atom/checkbox';
import Button from '../../atom/button';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class ParallelFormRemove {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, parallel } = settings;

        this._prop = {
            langId,
            parallelId: parallel.id,
        };

        this._state = {};

        this._atm.nameInput = <Input className="col-12" label={i18n(langId, 'TTL_PARALLEL_NAME')} value={parallel.name} disabled={true} />;
        this._atm.numberInput = <Input className="col-12" label={i18n(langId, 'TTL_PARALLEL_NUMBER')} value={parallel.number} disabled={true} />;
        this._atm.showInGroupCheckbox = (
            <Checkbox className="col-12" label={i18n(langId, 'TTL_PARALLEL_SHOW_IN_GROUP')} checked={parallel.showInGroup} disabled={true} />
        );

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

        const { parallelId } = this._prop;

        this._callRemoveParallel({ id: parallelId });
    };

    _onCancelButtonClick = () => {
        history.back();
    };

    _beforeCallRemoveParallel = () => {
        this._updateStateRemoveButton({ disabled: true, isLoading: true, title: 'TTL_TO_REMOVE_IN_PROGRESS' });
    };

    _afterCallRemoveParallel = (payload) => {
        this._updateStateRemoveButton({
            disabled: false,
            title: 'TTL_TO_REMOVE',
            isLoading: false,
        });
    };

    _callRemoveParallel = async (payload) => {
        this._beforeCallRemoveParallel();
        try {
            const resp = await fetcher('removeParallel', payload, 'api/v1');

            if (resp.status === 'ok') {
                openSiteURL('parallel-list.php');
            }

            this._afterCallRemoveParallel({ status: resp.status, data: resp.data });
        } catch (e) {
            debugger;
            this._afterCallRemoveParallel({ status: 'error' });
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
        const { langId } = this._prop;

        return (
            <form class="mt-3 row gx-0 gy-3">
                <div class="bg-body-tertiary row border gy-3 m-0 py-3">
                    {this._atm.nameInput}
                    {this._atm.numberInput}
                    {this._atm.showInGroupCheckbox}
                </div>
                <div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
                    {this._atm.removeButton}
                    <Button
                        className="btn btn-outline-secondary"
                        icon={'bi-x-circle'}
                        title={i18n(langId, 'TTL_TO_CANCEL')}
                        onClick={this._onCancelButtonClick}
                    />
                </div>
            </form>
        );
    };
}
