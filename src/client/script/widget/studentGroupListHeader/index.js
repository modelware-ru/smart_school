import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Button from '../../atom/button';
import SelectMenuItem from '../../atom/select_menu_item';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentGroupListHeader {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, serieList, lessonId, groupId } = settings;

        this._prop = {
            langId,
            serieList,
            lessonId,
            groupId,
        };

        this._state = {
            serieId: 0,
            markedClassSerieList: new Set(),
            markedHomeSerieList: new Set(),
        };

        this._stateAddSerieButton = {};
        this._atm.addSerieButton = (
            <Button
                className="btn btn-success position-relative"
                onClick={this._onAddSerieButtonClick}
                icon={'bi-plus-circle'}
                title={i18n(langId, 'TTL_TO_ADD_SERIE')}
            />
        );

        this._stateRemoveSerieButton = {};
        this._atm.removeSerieButton = (
            <Button
                className="btn btn-danger position-relative"
                onClick={this._onRemoveSerieButtonClick}
                icon={'bi-trash'}
                title={i18n(langId, 'TTL_TO_REMOVE_SERIE')}
            />
        );
        this._calcStateSerieButtons();

        commonEventManager.subscribe('changedMarkedClassSerieList', this._onChangedMarkedClassSerieList);
        commonEventManager.subscribe('changedMarkedHomeSerieList', this._onChangedMarkedHomeSerieList);

        this.el = this._ui_render();
    }

    _onAddSerieButtonClick = () => {
        this._fetch('addSerieToLesson');
    };

    _onRemoveSerieButtonClick = () => {
        this._fetch('removeSerieFromLesson');
    };

    _fetch = async (action) => {
        const { markedClassSerieList, markedHomeSerieList } = this._state;
        const { lessonId, groupId } = this._prop;
        const { serieId } = this._state;

        const payload = {
            lessonId,
            serieId,
            groupId,
            studentClassList: [...markedClassSerieList],
            studentHomeList: [...markedHomeSerieList],
        };

        commonEventManager.dispatch('showSpinner');

        try {
            const resp = await fetcher(action, payload);

            if (resp.status === 'ok') {
                location.reload(true);
                return;
            }
            commonEventManager.dispatch('hideSpinner');
        } catch (e) {
            debugger;
            commonEventManager.dispatch('hideSpinner');
        }
    };

    _onChangedMarkedClassSerieList = (data) => {
        this._state['markedClassSerieList'] = data;
        this._calcStateSerieButtons();
    };

    _onChangedMarkedHomeSerieList = (data) => {
        this._state['markedHomeSerieList'] = data;
        this._calcStateSerieButtons();
    };

    _calcStateSerieButtons = () => {
        const { serieId, markedClassSerieList, markedHomeSerieList } = this._state;

        const enabled = serieId !== 0 && (markedClassSerieList.length > 0 || markedHomeSerieList.length > 0);

        this._updateStateSerieButton('add', {
            disabled: !enabled,
        });
        this._updateStateSerieButton('remove', {
            disabled: !enabled,
        });
    };

    _updateStateSerieButton = (entity, state) => {
        const { disabled = null, title = null, titleArgs = [''] } = state;
        const { langId } = this._prop;

        let stateChangeButton;
        let changeButton;
        switch (entity) {
            case 'add':
                stateChangeButton = this._stateAddSerieButton;
                changeButton = this._atm.addSerieButton;
                break;
            case 'remove':
                stateChangeButton = this._stateRemoveSerieButton;
                changeButton = this._atm.removeSerieButton;
                break;
            default:
                return;
        }

        stateChangeButton['disable'] = disabled ?? stateChangeButton.disabled;
        stateChangeButton['title'] = title ?? stateChangeButton.title;
        stateChangeButton['titleArgs'] = titleArgs;

        if (disabled !== null) {
            changeButton.updateProp('disabled', disabled);
        }

        if (title !== null) {
            changeButton.updateProp('title', i18n(langId, title, titleArgs));
        }
    };

    _onChange = (args) => {
        const {
            value: { newValue },
        } = args;

        this._state.serieId = parseInt(newValue);
        this._calcStateSerieButtons();
    };

    _ui_render = () => {
        const { langId, serieList } = this._prop;
        const { serieId } = this._state;

        return (
            <div className="d-flex flex-fill flex-wrap gap-1 gap-md-3">
                <SelectMenuItem
                    className="flex-fill"
                    value={serieId}
                    content={serieList}
                    status={'none'}
                    hasError={'unknown'}
                    key={0}
                    onChange={this._onChange}
                />
                {this._atm.addSerieButton}
                {this._atm.removeSerieButton}
            </div>
        );
    };
}
