import RU from './i18n.ru';
import EN from './i18n.en';

export default (langId, code, args = []) => {
  if (code == null || code.length === 0) return '';

  if (!['ru', 'en'].includes(langId)) {
    langId = 'ru';
  }

  if (langId === 'ru' && RU[code]) return RU[code](...args);
  if (langId === 'en' && EN[code]) return EN[code](...args);

  return code;
};
