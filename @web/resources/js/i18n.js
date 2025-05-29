import Vue from 'vue';
import VueI18n from 'vue-i18n';

import {dateTimeFormats} from '../lang/formats/dateTimeFormats';
import {pluralizationRules} from '../lang/formats/pluralization';

Vue.use(VueI18n);

import nl from '../lang/nl.json';
import en from '../lang/en.json';
import de from '../lang/de.json';
import es from '../lang/es.json';
import fr from '../lang/fr.json';

export function loadLocaleMessages() {
  return {
    nl: nl,
    en: en,
    de: de,
    es: es,
    fr: fr,
  }
}

export const defaultLanguage = 'nl';
export const languages = Object.getOwnPropertyNames(loadLocaleMessages());
export const selectedLocale = navigator.language.split('-')[0] || defaultLanguage;

export default new VueI18n({
  dateTimeFormats,
  pluralizationRules,
  locale: selectedLocale,
  fallbackLocale: 'en',
  messages: loadLocaleMessages(),
  silentTranslationWarn: true
});
