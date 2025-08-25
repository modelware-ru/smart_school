import { mount, el } from '../node_modules/redom/dist/redom.es';

import LessonForm from './widget/lessonForm/index';
import LessonFormRemove from './widget/lessonFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const lesson = window.app.lesson;
const date = window.app.date;
const subjectList = window.app.subjectList;
const groupList = window.app.groupList;
const serieList = window.app.serieList;
const serieListInLesson = window.app.serieListInLesson;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? (
            <LessonFormRemove
                langId={langId}
                lesson={lesson}
                groupList={groupList}
                subjectList={subjectList}
                serieList={serieList}
                serieListInLesson={serieListInLesson}
            />
        ) : (
            <LessonForm
                langId={langId}
                lesson={lesson}
                date={date}
                groupList={groupList}
                subjectList={subjectList}
                serieList={serieList}
                serieListInLesson={serieListInLesson}
            />
        )}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
