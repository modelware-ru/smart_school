import { mount, el } from '../node_modules/redom/dist/redom.es';
import i18n from './shared/i18n/index';

import TwoBindSelect from './widget/twoBindSelect/index';

const langId = 'ru';

const opt1 = [
    {
        value: '0',
        name: 'Выберите тему',
        disabled: true,
    },
    {
        value: 'a1',
        name: 'name1',
    },
    {
        value: 'a2',
        name: 'name2',
    },
    {
        value: 'a3',
        name: 'name3',
    },
];

const opt2 = {
    0: [
        {
            value: '0',
            name: 'Тема не выбрана',
            disabled: true,
        },
    ],
    a1: [
        {
            value: '0',
            name: 'Выберите подтему',
            disabled: true,
        },
        {
            value: 'a11',
            name: 'name11',
        },
        {
            value: 'a12',
            name: 'name12',
        },
        {
            value: 'a13',
            name: 'name13',
        },
    ],
    a2: [
        {
            value: '0',
            name: 'Выберите подтему',
            disabled: true,
        },
        {
            value: 'a21',
            name: 'name21',
        },
        {
            value: 'a22',
            name: 'name22',
        },
        {
            value: 'a23',
            name: 'name23',
        },
        {
            value: 'a24',
            name: 'name24',
        },
    ],
    a3: [
        {
            value: '0',
            name: 'Выберите подтему',
            disabled: true,
        },
        {
            value: 'a31',
            name: 'name31',
        },
        {
            value: 'a32',
            name: 'name32',
        },
    ],
};

const onReady = () => {
    console.log('Ready');
}

mount(
    document.getElementById('main'),
    <div className="w-100">
        <TwoBindSelect
            langId={langId}
            label={{
                first: i18n(langId, 'TTL_TOPIC_NAME'),
                second: i18n(langId, 'TTL_SUBTOPIC_NAME'),
            }}
            value={{
                first: '0',
                second: '0',
            }}
            optionData={{
                first: opt1,
                second: opt2,
            }}
            onReady={onReady}
        />
    </div>
);
