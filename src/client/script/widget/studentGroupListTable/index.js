import { el, mount } from '../../../node_modules/redom/dist/redom.es';
import { clsx } from '../../../node_modules/clsx/dist/clsx.mjs';

import i18n from '../../shared/i18n/index';
import TableRow from '../../atom/table_row';
import { openSiteURL } from '../../shared/utils';
import { fetcher } from '../../shared/fetcher';

import { commonEventManager } from '../../shared/eventManager';
import Button from '../../atom/button';

export default class StudentGroupListTable {
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
                                value: (
                                    <div className="" role="button">
                                        <label className="d-flex">
                                            <input type="checkbox" />
                                            <div className="d-flex flex-fill justify-content-start ms-3">
                                                <span className="text-nowrap">Изменить серию</span>
                                            </div>
                                        </label>
                                    </div>
                                ),
                                onClickData: { id: item['id'] },
                                onClick: this._onCellClassSerieClick,
                            },
                            {
                                value: (
                                    <div className="" role="button">
                                        <label className="d-flex">
                                            <input type="checkbox" />
                                            <div className="d-flex flex-fill justify-content-start ms-3">
                                                <span className="text-nowrap">Изменить серию</span>
                                            </div>
                                        </label>
                                    </div>
                                ),
                                onClickData: { id: item['id'] },
                                onClick: this._onCellHomeSerieClick,
                            },
                            // {
                            //     className: 'p-1',
                            //     value: (
                            //         <div className="d-flex gap-5">
                            //             {item['canBeRemoved'] && (
                            //                 <button
                            //                     className="btn btn-outline-danger btn-sm"
                            //                     onclick={(e) => {
                            //                         e.stopPropagation();
                            //                         this._onAction({ key, id: item['id'], action: 'remove' });
                            //                     }}
                            //                 >
                            //                     <i className="bi bi-trash"></i>
                            //                 </button>
                            //             )}
                            //             {item['canBeShowHistory'] && (
                            //                 <button
                            //                     className="btn btn-outline-primary btn-sm"
                            //                     onclick={(e) => {
                            //                         e.stopPropagation();
                            //                         this._onAction({ key, id: item['id'], action: 'showHistory' });
                            //                     }}
                            //                 >
                            //                     <i className="bi bi-file-text"></i>
                            //                 </button>
                            //             )}
                            //         </div>
                            //     ),
                            // },
                        ]}
                        key={key}
                        onRowClick={this._onRowClick}
                    />
                ),
            };
        });

        this._state = {
            rowList,
            markedClassSerieList: new Set(),
            markedHomeSerieList: new Set(),
        };

        this.el = this._ui_render();
    }

    _onCellClassSerieClick = (item, e) => {
        const { markedClassSerieList } = this._state;
        if (e.target.checked) {
            markedClassSerieList.add(item.id);
        } else {
            markedClassSerieList.delete(item.id);
        }
        this._state['markedClassSerieList'] = markedClassSerieList;
        commonEventManager.dispatch('changedMarkedClassSerieList', [...markedClassSerieList]);
    };

    _onCellGroupClick = (item, e) => {
        const { markedHomeSerieList } = this._state;

        if (e.target.checked) {
            markedHomeSerieList.add(item.id);
        } else {
            markedHomeSerieList.delete(item.id);
        }
        this._state['markedHomeSerieList'] = markedHomeSerieList;
        commonEventManager.dispatch('changedMarkedHomeSerieList', [...markedHomeSerieList]);
    };

    _onRowClick = (key) => {
        // const { rowList } = this._state;
        // const student = rowList.find((item) => item.key === key);
        // openSiteURL('student.php', { id: student['id'] });
    };

    _onAction = async (data) => {
        // const { key, id, action } = data;
        // if (action === 'remove') {
        //     openSiteURL('student.php', { id, action: 'remove' });
        //     return;
        // }
        // if (action === 'showHistory') {
        //     openSiteURL('student-class-group-history.php', { id });
        //     return;
        // }
    };

    _onAllClassSerieCheck = (action) => {
        console.log(action);
    };

    _onAllHomeSerieCheck = (action) => {
        console.log(action);
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
                        <th scope="col" className="text-end fit align-middle">
                            #
                        </th>
                        <th scope="col" className="align-middle">
                            ФИО
                        </th>
                        <th scope="col">
                            <div className="d-flex gap-3">
                                <Button className="btn btn-primary" icon="bi-check-square" onClick={(e) => this._onAllClassSerieCheck('check')} />
                                <Button className="btn btn-primary" icon="bi-square" onClick={(e) => this._onAllClassSerieCheck('uncheck')} />
                                <span className="text-nowrap align-content-center">Классные серии</span>
                            </div>
                        </th>
                        <th scope="col">
                            <div className="d-flex gap-3">
                                <Button className="btn btn-primary" icon="bi-check-square" onClick={(e) => this._onAllHomeSerieCheck('check')} />
                                <Button className="btn btn-primary" icon="bi-square" onClick={(e) => this._onAllHomeSerieCheck('uncheck')} />
                                <span className="text-nowrap align-content-center">Домашнии серии</span>
                            </div>
                        </th>
                        <th scope="col" className="fit align-middle">
                            Присутствие
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
