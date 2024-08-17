import { mount, el } from '../node_modules/redom/dist/redom.es';

// import ParallelForm from './widget/parallelForm/index';
// import ParallelFormRemove from './widget/parallelFormRemove/index';
import Notificator from './widget/notificator';
import SearchInput from './widget/searchInput';
import PermissionManager from './shared/permissionManager';

const langId = window.app.langId;
// const parallel = window.app.parallel;
// const action = window.app.action;
PermissionManager.setPermissionList(window.app.permission);

// mount(
//     document.getElementById('main'),
//     <main className="d-flex flex-column">
//         {action === 'remove' ? <ParallelFormRemove langId={langId} parallel={parallel} /> : <ParallelForm langId={langId} parallel={parallel} />}
//     </main>
// );
mount(document.getElementById('search-input'), <SearchInput langId={langId} />);
// mount(document.getElementById('main'), <Notificator langId={langId} show={false} />);
