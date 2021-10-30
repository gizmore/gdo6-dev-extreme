<?php
namespace GDO\DevExtreme;

use GDO\Core\GDO_Module;
use GDO\DB\GDT_EnumNoI18n;
use GDO\Language\Trans;
use GDO\UI\GDT_Icon;
use GDO\DB\GDT_Checkbox;
use GDO\UI\GDT_Message;

/**
 * Dev extreme bindings for gdo6.
 * - dxGrid for tables
 * - optional dxHTMLEditor as Message Provider
 * - optional dxIcons (todo and not recommended as only a few icons)
 * 
 * @author busch
 * @version 6.10.6
 */
final class Module_DevExtreme extends GDO_Module
{
    public $module_priority = 25;
    public $module_license = "DevExtreme";
    
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
            GDT_Checkbox::make('devextreme_compact')->initial('0'),
            GDT_Checkbox::make('devextreme_icons')->initial('0'),
            GDT_Checkbox::make('devextreme_editor')->initial('1'),
        ];
    }
    
    public function cfgTheme() { return $this->getConfigVar('devextreme_theme'); }
    public function cfgCompact() { return $this->getConfigVar('devextreme_compact'); }
    public function cfgIcons() { return $this->getConfigVar('devextreme_icons'); }
    public function cfgEditor() { return $this->getConfigVar('devextreme_editor'); }
    
    public function cfgExtremeTheme()
    {
        $theme = $this->cfgTheme();
        if ($this->cfgCompact())
        {
            $theme .= '.compact';
        }
        return $theme;
    }
    
    public function extremeThemes()
    {
        return [
            'carmine',
            'contrast',
            'dark',
            'darkmoon',
            'darkviolet',
            'darkmoon',
            'light',
            'material.blue.dark',
            'material.blue.light',
            'softblue',
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
        return in_array($iso, $locales, true) ? $iso : 'en';
    }
    
    public function onInit()
    {
        if ($this->cfgIcons())
        {
            # Set icon provider.
            $method = [DXIcon::class, 'iconS'];
            GDT_Icon::$iconProvider = $method;
        }
        if ($this->cfgEditor())
        {
            GDT_Message::$EDITOR_NAME = 'DevX';
        }
    }
    
    public function onIncludeScripts()
    {
        # css
        $this->addBowerCSS('devextreme/dist/css/dx.common.css');
        $this->addBowerCSS('devextreme/dist/css/dx.'.$this->cfgExtremeTheme().'.css');

        # gdo
        $this->addJavascript('js/gdo6-dx.js');
        $this->addCSS('css/gdo6-dx.css');
        if ($this->cfgEditor())
        {
            # quill
            $this->addBowerJavascript('devextreme-quill/dist/dx-quill.min.js');
            $this->addBowerCSS('devextreme-quill/dist/dx-quill.core.css');
            # gdo
            $this->addJavascript('js/gdo6-dx-editor.js');
        }

        # now dx
        $this->addBowerJavascript('devextreme/dist/js/dx.all.debug.js');
        $this->addBowerJavascript('devextreme/dist/js/localization/dx.messages.'.$this->getExtremeLocale().'.js');
    }
    
}
