import { mount, el } from '../node_modules/redom/dist/redom.es';
import i18n from './shared/i18n/index';

import DynamicList from './atom/dynamicList';
import { factory as twoBindSelectFactory } from './widget/twoBindSelect/index';

import Button from './atom/button';

const langId = 'ru';

const optionDataFirst = [
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

const optionDataSecond = {
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

const data = {
    optionData: {
        first: optionDataFirst,
        second: optionDataSecond,
    },
    label1: {
        first: 'TTL_TOPIC_NAME',
        second: 'TTL_SUBTOPIC_NAME',
    },
};

const defaultValue = {
    first: '0',
    second: '0',
};

const value = [
    {
        first: 'a1',
        second: 'a11',
    },
    {
        first: 'a1',
        second: 'a12',
    },
    {
        first: 'a2',
        second: 'a21',
    },
];

const dl = (
    <DynamicList
        langId={langId}
        factory={{
            data,
            creator: twoBindSelectFactory,
        }}
        defaultValue={defaultValue}
        value={value}
    />
);

mount(
    document.getElementById('main'),
    <div className="w-100">
        {dl}
        <Button title="OK" className="btn btn-success" onClick={() => {
            console.log(dl.state());
        }} />
    </div>
);
