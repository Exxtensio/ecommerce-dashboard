import {createI18n} from 'vue-i18n';

import en from '../locale/en.json'
import ru from '../locale/ru.json'
import ua from '../locale/ua.json'
import es from '../locale/es.json'

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('sellexx_language') || 'en',
    fallbackLocale: 'en',
    messages: {
        en: en,
        ru: ru,
        ua: ua,
        es: es,
    }
})

export default i18n
