import { mount, el } from '../node_modules/redom/dist/redom.es';

import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';
import Button from './atom/button';

import i18n from './shared/i18n/index';
import { fetcher } from './shared/fetcher';

const langId = window.app.langId;
const studentSerieId = window.app.studentSerieId;

PermissionManager.setPermissionList(window.app.permission);

const btn = (
    <Button
        className="btn btn-success align-self-start mb-3"
        title={i18n(langId, 'TTL_TO_SAVE')}
        icon={'bi-floppy'}
        disabled={false}
        onClick={async () => {
            const inputList = document.querySelectorAll('input');
            const taskList = [];
            inputList.forEach((item) => {
                taskList.push({
                    value: item.value,
                    solutionId: parseInt(item.dataset.solutionid),
                    serieTaskId: parseInt(item.dataset.serietaskid),
                });
            });

            const payload = {
                studentSerieId,
                taskList,
            };

            btn.updateProp('disabled', true);
            btn.updateProp('isLoading', true);

            try {
                const resp = await fetcher('saveStudentSolution', payload);

                if (resp.status === 'ok') {
                    btn.updateProp('icon', 'bi-check2-circle');
                    location.reload(true);
                }

                if (resp.status === 'error') {
                    btn.updateProp('disabled', false);
                }

                btn.updateProp('isLoading', false);
            } catch (e) {
                debugger;
                btn.updateProp('disabled', false);
                btn.updateProp('isLoading', false);
            }
        }}
    />
);

mount(document.getElementById('main'), btn);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
