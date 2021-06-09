<?php
namespace GDO\DevExtreme;

use GDO\Core\GDO_Module;

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
    
    public function onIncludeScripts()
    {
        $this->addJavascript('assets/dx.all.js');
        $this->addJavascript('assets/dx.messages.de.js');
        $this->addCSS('assets/dx.common.css');
        $this->addCSS('assets/dx.light.css');
    }
    
}
