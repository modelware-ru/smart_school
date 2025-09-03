import { mount, el } from '../node_modules/redom/dist/redom.es';

import TaskForm from './widget/taskForm/index';
import TaskFormRemove from './widget/taskFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const task = window.app.task;
const topicList = window.app.topicList;
const topicSubtopicList = window.app.topicSubtopicList;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? (
            <TaskFormRemove langId={langId} task={task}/>
        ) : (
            <TaskForm langId={langId} task={task} topicList={topicList} topicSubtopicList={topicSubtopicList}/>
        )}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
