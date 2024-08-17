import { el, mount, unmount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import SelectMenuItem from '../../atom/select_menu_item';

import { commonEventManager } from '../../shared/eventManager';

export default class SelectMenu {
    _el = {};
    _atm = {};

    _key = 1;
    constructor(settings = {}) {
        const { langId, itemContent, itemList } = settings;

        this._prop = {
            langId,
            itemContent,
            itemList,
        };

        let selectMenuItemList = itemList.map((item, key) => {
            return {
                key,
                item: (
                    <SelectMenuItem
                        className="col-12"
                        value={item['id']}
                        content={[
                            {
                                value: item['id'],
                                name: item['name'],
                            },
                        ]}
                        status={'done'}
                        hasError={'no'}
                        key={key}
                        onAction={this._onAction}
                    />
                ),
            };
        });

        const nextKey = selectMenuItemList.length;
        selectMenuItemList.push({
            key: nextKey,
            item: <SelectMenuItem className="col-12" value={'0'} content={itemContent} key={nextKey} onAction={this._onAction} />,
        });

        this._key = selectMenuItemList.length;

        this._state = {
            selectMenuItemList,
        };

        this.el = this._ui_render();
    }

    getState = (name) => {
        switch (name) {
            case 'itemList':
                const { selectMenuItemList } = this._state;

                const res = selectMenuItemList.reduce((carry, item) => {
                    const value = item['item'].getState('value');
                    const status = item['item'].getProp('status');
                    if (status === 'done' && !carry.includes(value)) {
                        carry.push(value);
                    }
                    return carry;
                }, []);
                return res;
        }
    };

    _onAction = (args) => {
        const { key, status } = args;
        const { itemContent } = this._prop;
        const { selectMenuItemList } = this._state;
        let { lastItem: _ui_lastItem, menu: _ui_menu } = this._el;

        if (status === 'new') {
            const newKey = this._key++;
            const newItem = <SelectMenuItem className="col-12" value={'0'} content={itemContent} key={newKey} onAction={this._onAction} />;
            selectMenuItemList.push({ key: newKey, item: newItem });
            mount(_ui_menu, newItem, _ui_lastItem);

            const i = selectMenuItemList.find((item) => item.key === key);
            i['item'].updateProp('status', 'done');
        }

        if (status === 'done') {
            const index = selectMenuItemList.findIndex((item) => item.key === key);
            unmount(_ui_menu, selectMenuItemList[index]['item']);
            selectMenuItemList.splice(index, 1);
        }
    };

    _ui_render = () => {
        const { langId } = this._prop;
        const { selectMenuItemList } = this._state;

        return (this._el.menu = (
            <div className="d-flex flex-column gap-3">
                {selectMenuItemList.map((item) => {
                    return item['item'];
                })}
                {(this._el.lastItem = <div />)}
            </div>
        ));
    };
}
