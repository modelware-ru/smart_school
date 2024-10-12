import babel from '@rollup/plugin-babel';

const pages = [
    'message',
    'guest_guestIndex',
    'admin_parallel',
    'admin_group',
    'admin_teacherList',
    'admin_teacher',
    'admin_subject',
    'admin_studentList',
    'admin_student',
    'admin_studentListChangeClass',
    'admin_studentListChangeGroup',
    'admin_schoolYear',
    'admin_teacherGroup',
    'teacher_topic',
    'teacher_categoryTag',
    'teacher_serie',
    'teacher_lesson',
    'teacher_lessonJournal',
];

const export_page = pages.reduce((acc, item) => {
    acc.push({
        input: `./script/${item}.js`,
        output: {
            file: `../server/www/js/${item}_bundle.js`,
            format: 'cjs',
            sourcemap: 'inline',
        },
        plugins: [babel({ babelHelpers: 'bundled' })],
    });
    return acc;
}, []);

export default export_page;
