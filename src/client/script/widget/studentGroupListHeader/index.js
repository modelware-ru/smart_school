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
        const { langId, serieList } = settings;

        this._prop = {
            langId,
            serieList,
        };

        this._state = {
            hasSerie: false,
            hasMarked: false,
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
        this._updateStateSerieButton('add', {
            disabled: !this._state.hasSerie && !this._state.hasMarked,
        });

        this._stateRemoveSerieButton = {};
        this._atm.removeSerieButton = (
            <Button
                className="btn btn-danger position-relative"
                onClick={this._onRemoveSerieButtonClick}
                icon={'bi-trash'}
                title={i18n(langId, 'TTL_TO_REMOVE_SERIE')}
            />
        );
        this._updateStateSerieButton('remove', {
            disabled: !this._state.hasSerie && !this._state.hasMarked,
        });

        commonEventManager.subscribe('changedMarkedClassSerieList', this._onChangedMarkedClassSerieList);
        commonEventManager.subscribe('changedMarkedHomeSerieList', this._onChangedMarkedHomeSerieList);

        this.el = this._ui_render();
    }

    _onAddSerieButtonClick = () => {
        const { markedClassSerieList, markedHomeSerieList } = this._state;
        const ids = 'ids=' + markedClassSerieList.join(',');
        // openSiteURL(`student-list-change-class.php?${ids}`);
    };

    _onRemoveSerieButtonClick = () => {
        const { markedClassSerieList, markedHomeSerieList } = this._state;
        const ids = 'ids=' + markedHomeSerieList.join(',');
        // openSiteURL(`student-list-change-group.php?${ids}`);
    };

    _onChangedMarkedClassSerieList = (data) => {
        this._state['markedClassSerieList'] = data;
    };

    _onChangedMarkedHomeSerieList = (data) => {
        this._state['markedHomeSerieList'] = data;

        // const l = data.length;
        // if (l > 0) {
        //     this._updateStateSerieButton('group', { disabled: false, title: 'TTL_TO_CHANGE_GROUP', titleArgs: [l] });
        // } else {
        //     this._updateStateSerieButton('group', { disabled: true, title: 'TTL_TO_CHANGE_GROUP' });
        // }
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

        this._state.hasSerie = parseInt(newValue) !== 0;
        console.log (this._state.hasSerie);
        this._updateStateSerieButton('add', {
            disabled: !this._state.hasSerie && !this._state.hasMarked,
        });
        this._updateStateSerieButton('remove', {
            disabled: !this._state.hasSerie && !this._state.hasMarked,
        });
    };

    _ui_render = () => {
        const { langId, serieList } = this._prop;

        return (
            <div className="d-flex flex-fill flex-wrap gap-1 gap-md-3">
                <SelectMenuItem
                    className="flex-fill"
                    value={0}
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
