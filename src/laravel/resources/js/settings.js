import variables from '@/styles/element-variables.scss';

export default {
  /**
   * @type {String}
   */
  title: process.env.MIX_SETTING_TITLE ?? 'Laravel',
  theme: variables.theme,

  /**
   * @type {boolean} true | false
   * @description Whether show the settings right-panel
   */
  showSettings: process.env.MIX_SETTING_SHOW_SETTINGS_PANEL ?? true,

  /**
   * @type {boolean} true | false
   * @description Whether need tagsView
   */
  tagsView: process.env.MIX_SETTING_TAGSVIEW ?? true,

  /**
   * @type {boolean} true | false
   * @description Whether fix the header
   */
  fixedHeader: process.env.MIX_SETTING_FIXEDHEADER ?? false,

  /**
   * @type {boolean} true | false
   * @description Whether show the logo in sidebar
   */
  sidebarLogo: process.env.MIX_SETTING_SIDEBARLOGO ?? false,

  /**
   * @type {string | array} 'production' | ['production','development']
   * @description Need show err logs component.
   * The default is only used in the production env
   * If you want to also use it in dev, you can pass ['production','development']
   */
  errorLog: ['production','development'],
};
