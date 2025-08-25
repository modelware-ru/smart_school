import { el, mount } from '../../../node_modules/redom/dist/redom.es';
import { clsx } from '../../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../../shared/i18n/index';
import TableRow from '../../atom/table_row';
import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

import { commonEventManager } from '../../shared/eventManager';

export default class StudentListTable {
    _el = {};
    _atm = {};

    _key = 1;
    constructor(settings = {}) {
        const { langId, studentList } = settings;

        this._prop = {
            langId,
            studentList,
        };

        const rowList = studentList.map((item, key) => {
            return {
                key,
                id: item['id'],
                item: (
                    <TableRow
                        className={'align-middle'}
                        content={[
                            {
                                className: 'text-end text-nowrap',
                                value: <strong>{key + 1}</strong>,
                            },
                            {
                                value: item['name'],
                            },
                            {
                                className: 'text-center position-relative',
                                value: (
                                    <div className="position-absolute w-100 h-100 top-0 end-0 p-2" role="button">
                                        <label className="d-flex justify-content-between w-100 h-100">
                                            <input type="checkbox" />
                                            <div className="d-flex flex-fill justify-content-start ms-3">
                                                <span className="text-nowrap">{item['class']}</span>
                                            </div>
                                        </label>
                                    </div>
                                ),
                                onClickData: { id: item['id'] },
                                onClick: this._onCellClassClick,
                            },
                            {
                                className: clsx('text-center position-relative', {
                                    'bg-danger-subtle': item['classParallelId'] !== item['groupParallelId'],
                                }),
                                value: item['classParallelId'] && (
                                    <div className="position-absolute w-100 h-100 top-0 end-0 p-2" role="button">
                                        <label className="d-flex justify-content-between w-100 h-100">
                                            <input type="checkbox" />
                                            <div className="d-flex flex-fill justify-content-start ms-3">
                                                <span className="text-nowrap">
                                                    {item['groupParallelNumber'] && `[${item['groupParallelNumber']}]`} {item['group']}
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                ),
                                onClickData: { id: item['id'] },
                                onClick: this._onCellGroupClick,
                            },
                            {
                                className: 'p-1',
                                value: (
                                    <div className="d-flex gap-5">
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
                                        {item['canBeShowHistory'] && (
                                            <button
                                                className="btn btn-outline-primary btn-sm"
                                                onclick={(e) => {
                                                    e.stopPropagation();
                                                    this._onAction({ key, id: item['id'], action: 'showHistory' });
                                                }}
                                            >
                                                <i className="bi bi-file-text"></i>
                                            </button>
                                        )}
                                    </div>
                                ),
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
            markedClassStudentList: new Set(),
            markedGroupStudentList: new Set(),
        };

        this.el = this._ui_render();
    }

    _onCellClassClick = (item, e) => {
        const { markedClassStudentList } = this._state;
        if (e.target.checked) {
            markedClassStudentList.add(item.id);
        } else {
            markedClassStudentList.delete(item.id);
        }
        this._state['markedClassStudentList'] = markedClassStudentList;
        commonEventManager.dispatch('changedMarkedClassStudentList', [...markedClassStudentList]);
    };

    _onCellGroupClick = (item, e) => {
        const { markedGroupStudentList } = this._state;

        if (e.target.checked) {
            markedGroupStudentList.add(item.id);
        } else {
            markedGroupStudentList.delete(item.id);
        }
        this._state['markedGroupStudentList'] = markedGroupStudentList;
        commonEventManager.dispatch('changedMarkedGroupStudentList', [...markedGroupStudentList]);
    };

    _onRowClick = (key) => {
        const { rowList } = this._state;
        const student = rowList.find((item) => item.key === key);
        openSiteURL('student.php', { id: student['id'] });
    };

    _onAction = async (data) => {
        const { key, id, action } = data;

        if (action === 'remove') {
            openSiteURL('student.php', { id, action: 'remove' });
            return;
        }
        if (action === 'showHistory') {
            openSiteURL('student-class-group-history.php', { id });
            return;
        }
    };

    _ui_render = () => {
        const { langId, studentList } = this._prop;

        if (studentList.length === 0) {
            return (
                <div className="alert alert-info rounded-0 my-3" role="alert">
                    <div>
                        <p className="m-0">Не найден ни один ученик.</p>
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
                        <th scope="col" className="text-center">
                            Класс
                        </th>
                        <th scope="col" className="text-center">
                            Группа
                        </th>
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
