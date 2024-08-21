import { el, mount } from '../../../node_modules/redom/dist/redom.es';
import { clsx } from '../../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../../shared/i18n/index';
import TableRow from '../../atom/table_row';
import Button from '../../atom/button';
import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

import { commonEventManager } from '../../shared/eventManager';

export default class TeacherListTable {
    _el = {};
    _atm = {};

    _key = 1;
    constructor(settings = {}) {
        const { langId, teacherList } = settings;

        this._prop = {
            langId,
            teacherList,
        };

        let rowList = teacherList.map((item, key) => {
            const blockBtn = this._ui_blocked_btn(item['canBeBlocked'], key, item['id']);
            const blockBtnParent = (
                <div className="d-flex gap-2">
                    {blockBtn}
                    {item['canBeRemoved'] && (
                        <button
                            className="btn btn-outline-danger btn-sm"
                            onclick={(e) => {
                                e.stopPropagation();
                                this._onAction({ key, id: item['id'], action: 'remove' });
                            }}
                        >
                            <i className="bi bi-trash"></i>
                        </button>
                    )}
                </div>
            );

            return {
                key,
                blockBtn,
                blockBtnParent,
                id: item['id'],
                item: (
                    <TableRow
                        className={clsx('align-middle', { 'table-danger': !item['canBeBlocked'] })}
                        content={[
                            {
                                className: 'text-end text-nowrap',
                                value: <strong>{key + 1}</strong>,
                            },
                            {
                                value: item['name'],
                            },
                            {},
                            {
                                className: 'p-1',
                                value: blockBtnParent,
                            },
                        ]}
                        key={key}
                        onRowClick={this._onRowClick}
                    />
                ),
            };
        });

        this._state = {
            rowList,
        };

        this.el = this._ui_render();
    }

    _ui_blocked_btn = (canBeBlocked, key, id) => {
        if (canBeBlocked) {
            return (
                <Button
                    className="btn btn-outline-danger btn-sm"
                    icon="bi-lock-fill"
                    onClickData={{ key, id, action: 'block' }}
                    onClick={this._onAction}
                />
            );
        } else {
            return (
                <Button
                    className="btn btn-outline-success btn-sm"
                    icon="bi-unlock-fill"
                    onClickData={{ key, id, action: 'unblock' }}
                    onClick={this._onAction}
                />
            );
        }
    };

    _onRowClick = (key) => {
        const { rowList } = this._state;
        const teacher = rowList.find((item) => item.key === key);
        openSiteURL('teacher.php', { id: teacher['id'] });
    };

    _onAction = async (data) => {
        const { key, id, action } = data;

        if (action === 'remove') {
            openSiteURL('teacher.php', { id, action: 'remove' });
            return;
        }

        const { rowList } = this._state;

        const teacher = rowList.find((item) => item.key === key);

        const payload = { id, action };

        this._beforeCallBlockTeacher(teacher);
        try {
            const resp = await fetcher('blockTeacher', payload);

            if (resp.status === 'ok') {
                teacher.item.updateProp('className', clsx('align-middle', { 'table-danger': action === 'block' }));
                teacher.blockBtn.updateProp('iconSpin', false);
                teacher.blockBtn.updateProp('className', action === 'block' ? 'btn btn-outline-success btn-sm' : 'btn btn-outline-danger btn-sm');
                teacher.blockBtn.updateProp('icon', action === 'block' ? 'bi-unlock-fill' : 'bi-lock-fill');
                teacher.blockBtn.updateProp('iconSpin', false);
                teacher.blockBtn.updateProp('onClickData', { key, id, action: action === 'block' ? 'unblock' : 'block' });
                return;
            }

            this._afterFailCallBlockTeacher(teacher, action);
        } catch (e) {
            debugger;
            this._afterFailCallBlockTeacher(teacher, action);
        }
    };

    _beforeCallBlockTeacher = (teacher) => {
        commonEventManager.dispatch('hideMessage');
        teacher.blockBtn.updateProp('disabled', true);
        teacher.blockBtn.updateProp('iconSpin', true);
        teacher.blockBtn.updateProp('icon', 'bi-arrow-repeat');
    };

    _afterSuccessCallBlockTeacher = (teacher, action) => {
        teacher.item.updateProp('className', clsx('align-middle', { 'table-danger': action === 'block' }));
        teacher.blockBtn.updateProp('iconSpin', false);
        teacher.blockBtn.updateProp('className', action === 'block' ? 'btn btn-outline-success btn-sm' : 'btn btn-outline-danger btn-sm');
        teacher.blockBtn.updateProp('icon', action === 'block' ? 'bi-unlock-fill' : 'bi-lock-fill');
        teacher.blockBtn.updateProp('disabled', false);
    };

    _afterFailCallBlockTeacher = (teacher, action) => {
        teacher.blockBtn.updateProp('iconSpin', false);
        teacher.blockBtn.updateProp('icon', action === 'block' ? 'bi-lock-fill' : 'bi-unlock-fill');
        teacher.blockBtn.updateProp('disabled', false);
    };

    _ui_render = () => {
        const { langId, teacherList } = this._prop;

        if (teacherList.length === 0) {
            return (
                <div className="alert alert-info rounded-0 my-3" role="alert">
                    <div>
                        <p className="m-0">Не найден ни один преподаватель.</p>
                    </div>
                </div>
            );
        }

        const { rowList } = this._state;

        return (
            <table className="table table-hover table-bordered clickable-rows my-3">
                <thead>
                    <tr className="table-active border-dark-subtle">
                        <th scope="col" className="text-end fit">
                            #
                        </th>
                        <th scope="col">ФИО</th>
                        <th scope="col">Группы</th>
                        <th scope="col" className="fit">
                            Действия
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {rowList.map((item) => {
                        return item['item'];
                    })}
                </tbody>
            </table>
        );
    };
}
