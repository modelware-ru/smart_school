import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Button from '../../atom/button';
import Input from '../../atom/input';
import SelectMenuItem from '../../atom/select_menu_item';

import { commonEventManager } from '../../shared/eventManager';

import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

export default class StudentSerieGroupListHeader {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, serieList, studentId, groupId, schoolYear } = settings;

        this._prop = {
            langId,
            serieList,
            studentId,
            groupId,
            schoolYear,
        };

        this._state = {
            serieId: 0,
            date: new Date().toISOString().substring(0, 10),
        };

        this._stateDateInput = {};
        this._atm.dateInput = <Input type="date" className="d-inline-flex" value={this._state.date} onTest={this._onChangeDate}/>;
        
        this._stateAddSerieButton = {};
        this._atm.addSerieButton = (
            <Button
                className="btn btn-success position-relative"
                onClick={this._onAddSerieButtonClick}
                icon={'bi-plus-circle'}
                title={i18n(langId, 'TTL_TO_ADD_SERIE')}
            />
        );

        this._calcStateSerieButtons();

        this.el = this._ui_render();
    }

    _onChangeDate = (value) => {
        this._atm.dateInput.updateProp('hasError', 'unknown');
        this._state.date = value;
        this._calcStateSerieButtons();
        return true;
    };

    _onAddSerieButtonClick = () => {
        this._fetch('addHomeSerieToStudent');
    };

    _fetch = async (action) => {
        const { studentId, groupId, schoolYear } = this._prop;
        const { serieId, date } = this._state;

        const payload = {
            serieId,
            studentId,
            groupId,
            date,
        };

        // check
        const schoolYearStartDate = Date.parse(schoolYear.startDate);
        const schoolYearFinishDate = Date.parse(schoolYear.finishDate);
        const serieDate = Date.parse(date);

        if (serieDate > schoolYearFinishDate || serieDate < schoolYearStartDate) {
            this._atm.dateInput.updateProp('hasError', 'yes');
            return;
        }

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

    _calcStateSerieButtons = () => {
        const { serieId, date } = this._state;

        this._updateStateSerieButton('add', {
            disabled: serieId === 0 || date.length === 0,
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
                {this._atm.dateInput}
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
            </div>
        );
    };
}
