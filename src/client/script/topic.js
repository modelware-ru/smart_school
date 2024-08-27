import { mount, el } from '../node_modules/redom/dist/redom.es';

import TopicForm from './widget/topicForm/index';
import TopicFormRemove from './widget/topicFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const topic = window.app.topic;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <TopicFormRemove langId={langId} topic={topic} /> : <TopicForm langId={langId} topic={topic} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
