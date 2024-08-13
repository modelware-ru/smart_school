import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import SelectMenuItem from '../../atom/select_menu_item';

import { commonEventManager } from '../../shared/eventManager';

export default class SelectMenu {
    _el = {};
    _atm = {};

    constructor(settings = {}) {
        const { langId, optionData } = settings;

        this._prop = {
            langId,
            optionData,
        };

        this._state = {};

        this.el = this._ui_render();
    }

    _ui_render = () => {
        const { langId, optionData } = this._prop;

        return (
            <div className='d-flex flex-column gap-3'>
                <SelectMenuItem className="col-12" value={1} optionData={optionData} status={'done'} />
                <SelectMenuItem className="col-12" value={1} optionData={optionData} />
            </div>
        );
    };
}
