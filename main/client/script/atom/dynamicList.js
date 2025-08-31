import { el, mount, unmount } from '../../node_modules/redom/dist/redom.es';
import { clsx } from '../../node_modules/clsx/dist/clsx.mjs';

import Atom from './atom';
import Button from './button';

export default class DynamicList extends Atom {
    // start "constructor"
    constructor(settings = {}) {
        super();
        const { langId, className = '', defaultValue, value = [], factory = {} } = settings;

        this._prop = {
            langId,
            className,
            defaultValue,
            factory,
        };

        let itemList = value.map((item, index) => {
            const btn = <Button className="btn btn-outline-danger bg-danger-subtlee ms-4" icon={'bi-trash'} onClick={this._onButtonClick(index)} />;
            const dli = new DynamicListItem({ index, onReady: this._onReady, langId, ...factory, value: item });
            return {
                state: 'done',
                btn,
                dli,
                item: (
                    <div className={this._css_className('done')}>
                        {dli}
                        {btn}
                    </div>
                ),
            };
        });

        const length = itemList.length;
        const btn = <Button className="d-none" icon={'bi-trash'} onClick={this._onButtonClick(length)} />;
        const dli = new DynamicListItem({ index: length, onReady: this._onReady, langId, ...factory, value: defaultValue });
        itemList.push({
            state: 'inprogress',
            btn,
            dli,
            item: (
                <div className={this._css_className('inprogress')}>
                    {dli}
                    {btn}
                </div>
            ),
        });

        this._state = {
            itemList,
        };

        this.el = this._ui_render();
    }
    // finish "constructor"

    state = () => {
        const { itemList } = this._state;
        const res = itemList.reduce((acc, item) => {
            if (item.state === 'done') {
                const value = item.dli.state();
                acc.push(value);
            }
            return acc;
        }, []);
        return res;
    };

    _css_className = (state) => {
        return clsx('d-flex justify-content-between p-2', { 'bg-success-subtle': state === 'done' });
    };

    _onButtonClick = (index) => {
        return (e) => {
            const { itemList } = this._state;
            unmount(this.el, itemList[index].item);
            itemList[index].state = 'deleted';
        };
    };

    _onReady = (index, ready) => {
        const { itemList } = this._state;
        const { langId, factory, defaultValue } = this._prop;

        if (ready) {
            itemList[index].state = 'done';
            itemList[index].btn.updateProp('className', 'btn btn-outline-danger bg-danger-subtlee ms-4');
            itemList[index].item.className = this._css_className('done');
        } else {
            itemList[index].state = 'inprogress';
            itemList[index].btn.updateProp('className', 'd-none');
            itemList[index].item.className = this._css_className('inprogress');
        }

        const length = itemList.length;

        if (index === length - 1 && ready) {
            const btn = <Button className="d-none" icon={'bi-trash'} onClick={this._onButtonClick(length)} />;
            const dli = new DynamicListItem({ index: length, onReady: this._onReady, langId, ...factory, value: defaultValue });
            const newItem = (
                <div className={this._css_className('inprogress')}>
                    {dli}
                    {btn}
                </div>
            );

            itemList.push({
                state: 'inprogress',
                btn,
                dli,
                item: newItem,
            });

            this._state.itemList = itemList;
            mount(this.el, newItem, this._el.lastItem);
        }
    };

    // start "_ui_render"
    _ui_render = () => {
        const { className } = this._prop;
        const { itemList } = this._state;

        return (
            <div className={clsx(className, 'd-flex flex-column gap-3')}>
                {itemList.map((item) => {
                    return item.item;
                })}
                {(this._el.lastItem = <div />)}
            </div>
        );
    };
    // finish "_ui_render"
}

class DynamicListItem extends Atom {
    // start "constructor"
    constructor(settings = {}) {
        super();

        const { langId, data, creator, value, index, onReady } = settings;

        this._prop = {
            index,
        };

        this._callback = {
            onReady,
        };

        this.el = creator({ onReady: this._onReady, langId, ...data, value });
    }
    // finish "constructor"

    state = () => {
        return this.el.state('value');
    };

    _onReady = (ready) => {
        const { index } = this._prop;
        const { onReady } = this._callback;

        onReady && onReady(index, ready);
    };
}
