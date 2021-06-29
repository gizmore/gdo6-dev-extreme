<?php
namespace GDO\DevExtreme;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_EnumNoI18n;
use GDO\Language\Trans;

/**
 * Dev extreme bindings for gdo6.
 * 
 * @author busch
 * @version 6.10.4
 */
final class Module_DevExtreme extends GDO_Module
{
    public $module_priority = 25;
    
    public function getTheme() { return 'dx'; }
    
    public function getDependencies()
    {
        return [
            'JQuery', 'BootstrapTheme',
        ];
    }
    
    public function onLoadLanguage()
    {
        return $this->loadLanguage('lang/dx');
    }
    
    public function getConfig()
    {
        return [
            GDT_EnumNoI18n::make('devextreme_theme')->enumValues(...$this->extremeThemes())->initial('light'),
        ];
    }
    
    public function cfgExtremeTheme()
    {
        return $this->getConfigVar('devextreme_theme');
    }
    
    public function extremeThemes()
    {
        return [
            'android5.light',
            'carmine.compact',
            'carmine',
            'contrast.compact',
            'contrast',
            'dark.compact',
            'dark',
            'light',
            # TODO add more,
        ];
    }
    
    public function extremeLocales()
    {
        return [
            'cs', 'de', 'el', 'en', 'es', 'fi', 'fr', 'hu',
            'it', 'ja', 'nl', 'pt', 'ru', 'sl', 'sv', 'tr',
            'vi', 'zh',
        ];
    }
    
    public function getExtremeLocale()
    {
        $iso = Trans::$ISO;
        $locales = $this->extremeLocales();
        return in_array($iso, $locales) ? $iso : 'en';
    }
    
    public function onIncludeScripts()
    {
        $this->addBowerJavascript('devextreme/js/dx.all.js');
        $this->addBowerJavascript('devextreme/js/localization/dx.messages.'.$this->getExtremeLocale().'.js');
        $this->addBowerCSS('devextreme/css/dx.common.css');
        $this->addBowerCSS('devextreme/css/dx.'.$this->cfgExtremeTheme().'.css');
    }
    
}
