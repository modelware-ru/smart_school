import { mount, el } from '../node_modules/redom/dist/redom.es';

import SubjectForm from './widget/subjectForm/index';
import SubjectFormRemove from './widget/subjectFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const subject = window.app.subject;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <SubjectFormRemove langId={langId} subject={subject} /> : <SubjectForm langId={langId} subject={subject} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
