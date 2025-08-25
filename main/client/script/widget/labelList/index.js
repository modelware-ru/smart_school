import { el, mount } from '../../../node_modules/redom/dist/redom.es';

import i18n from '../../shared/i18n/index';
import Label from '../../atom/label';

import { commonEventManager } from '../../shared/eventManager';

export default class LabelList {
    _el = {};
    _atm = {};

    _key = 1;
    constructor(settings = {}) {
        const { langId, title, labelList, className } = settings;

        this._prop = {
            langId,
            labelList,
            className,
            title,
        };

        const labelStatusList = labelList.map((item) => {
            return {
                id: item['id'],
                isRemoved: false,
            };
        });
        this._state = {
            labelStatusList,
        };

        this.el = this._ui_render();
    }

    getRemovedLabelIdList = () => {
        const { labelStatusList } = this._state;
        const removedLabelIdList = labelStatusList.reduce((carry, item) => {
            if (item['isRemoved']) {
                carry.push(item['id']);
            }
            return carry;
        }, []);
        return removedLabelIdList;
    };

    _onClick = (data) => {
        const { labelStatusList } = this._state;
        const label = labelStatusList.find((item) => item.id === data.id);
        const labelUI = this._atm.labelList.find((item) => item.id === data.id);

        if (data.isRemoved) {
            label.isRemoved = false;
            labelUI.ui.updateProp('className', 'bg-success-subtle');
            // labelUI.ui.updateProp('iconClassName', 'text-danger');
            // labelUI.ui.updateProp('icon', 'bi-dash-circle');
            labelUI.ui.updateProp('onClickData', { id: data['id'], isRemoved: false });
        } else {
            label.isRemoved = true;
            labelUI.ui.updateProp('className', 'bg-danger-subtle text-decoration-line-through');
            // labelUI.ui.updateProp('iconClassName', 'text-success');
            // labelUI.ui.updateProp('icon', 'bi-plus-circle');
            labelUI.ui.updateProp('onClickData', { id: data['id'], isRemoved: true });
        }
    };

    _ui_render = () => {
        const { langId, className, labelList, title } = this._prop;

        if (labelList.length === 0) {
            return (
                <div className={className}>
                    <div className="alert alert-info rounded-0" role="alert">
                        <div>
                            <p className="m-0">Не добавлено ни одного элемента.</p>
                        </div>
                    </div>
                </div>
            );
        }

        this._atm.labelList = labelList.map((item) => {
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
                    <span>{i18n(langId, title)}</span>
                </label>
                <div className="d-flex flex-wrap gap-3">
                    {this._atm.labelList.map((item) => {
                        return item.ui;
                    })}
                </div>
            </div>
        );
    };
}
