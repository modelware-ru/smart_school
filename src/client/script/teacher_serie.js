import { mount, el } from '../node_modules/redom/dist/redom.es';

import SerieForm from './widget/serieForm/index';
import SerieFormRemove from './widget/serieFormRemove/index';
import Notificator from './widget/notificator';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
const serie = window.app.serie;
const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

mount(
    document.getElementById('main'),
    <main className="d-flex flex-column">
        {action === 'remove' ? <SerieFormRemove langId={langId} serie={serie} /> : <SerieForm langId={langId} serie={serie} />}
    </main>
);
mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
