import { el, mount } from '../../../node_modules/redom/dist/redom.es';
import { clsx } from '../../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../../shared/i18n/index';
import Label from '../../atom/label';

import { commonEventManager } from '../../shared/eventManager';

export default class TagList {
    _el = {};
    _atm = {};

    _key = 1;
    constructor(settings = {}) {
        const { langId, tagList, className } = settings;

        this._prop = {
            langId,
            tagList,
            className,
        };

        const tagStatusList = tagList.map((item) => {
            return {
                id: item['id'],
                isRemoved: false,
            };
        });
        this._state = {
            tagStatusList,
        };

        this.el = this._ui_render();
    }

    getRemovedTagIdList = () => {
        const { tagStatusList } = this._state;
        const removedTagIdList = tagStatusList.reduce((carry, item) => {
            if (item['isRemoved']) {
                carry.push(item['id']);
            }
            return carry;
        }, []);
        return removedTagIdList;
    };

    _onClick = (data) => {
        const { tagStatusList } = this._state;
        const tag = tagStatusList.find((item) => item.id === data.id);
        const tagUI = this._atm.tagList.find((item) => item.id === data.id);

        if (data.isRemoved) {
            tag.isRemoved = false;
            tagUI.ui.updateProp('className', 'bg-success-subtle');
            // tagUI.ui.updateProp('iconClassName', 'text-danger');
            // tagUI.ui.updateProp('icon', 'bi-dash-circle');
            tagUI.ui.updateProp('onClickData', { id: data['id'], isRemoved: false });
        } else {
            tag.isRemoved = true;
            tagUI.ui.updateProp('className', 'bg-danger-subtle text-decoration-line-through');
            // tagUI.ui.updateProp('iconClassName', 'text-success');
            // tagUI.ui.updateProp('icon', 'bi-plus-circle');
            tagUI.ui.updateProp('onClickData', { id: data['id'], isRemoved: true });
        }
    };

    _ui_render = () => {
        const { langId, className, tagList } = this._prop;

        if (tagList.length === 0) {
            return (
                <div className={className}>
                    <div className="alert alert-info rounded-0" role="alert">
                        <div>
                            <p className="m-0">Не добавлено ни одного тега.</p>
                        </div>
                    </div>
                </div>
            );
        }

        this._atm.tagList = tagList.map((item) => {
            return {
                id: item['id'],
                ui: (
                    <Label
                        className="bg-success-subtle"
                        iconClassName="text-danger"
                        title={item['name']}
                        // icon="bi-dash-circle"
                        onClick={this._onClick}
                        onClickData={{ id: item['id'], isRemoved: false }}
                    />
                ),
            };
        });

        return (
            <div className={className}>
                <label className="form-label fw-bold">
                    <span>{i18n(langId, 'TTL_TAG_LIST')}</span>
                </label>
                <div className="d-flex flex-wrap gap-3">
                    {this._atm.tagList.map((item) => {
                        return item.ui;
                    })}
                </div>
            </div>
        );
    };
}
