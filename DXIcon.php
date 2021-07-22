<?php
namespace GDO\DevExtreme;

use GDO\UI\GDT_IconUTF8;

/**
 * DevExtreme Icon provider.
 * 
 * @author gizmore
 * @license DevExtreme
 */
final class DXIcon
{
    public static $MAP = [
        'account' => 'user',
        'add' => 'add',
//         'alert' => '!',
//         'all' => '▤',
//         'arrow_down' => '▼',
//         'arrow_left' => '←',
//         'arrow_right' => '‣',
//         'arrow_up' => '▲',
//         'audio' => '🎵',
//         'back' => '↶',
//         'bank' => '🏦',
//         'bars' => '☰',
//         'birthday' => '🎂',
//         'block' => '✖',
//         'book' => '📖',
//         'bulb' => '💡',
//         'calendar' => '📅',
//         'captcha' => '♺',
//         'caret' => '⌄',
//         'country' => '⚑',
//         'check' => '✔',
//         'create' => '✚',
//         'credits' => '¢',
//         'cut' => '✂',
//         'delete' => '✖',
//         'download' => '⇩',
//         'edit' => '✎',
//         'email' => '✉',
//         'error' => '⚠',
//         'face' => '☺',
//         'female' => '♀',
//         'file' => '🗎',
//         'flag' => '⚑',
//         'folder' => '📁',
//         'font' => 'ᴫ',
//         'gender' => '⚥',
//         'group' => '😂',
//         'guitar' => '🎸',
//         'help' => '💡',
//         'image' => '📷',
//         'language' => '⚐',
//         'level' => '🏆',
//         'like' => '❤',
//         'link' => '🔗',
//         'list' => '▤',
//         'lock' => '🔒',
//         'male' => '♂',
//         'message' => '☰',
//         'minus' => '-',
//         'money' => '💰',
//         'password' => '⚷',
//         'pause' => '⏸',
//         'phone' => '📞',
//         'plus' => '+',
//         'quote' => '↶',
//         'remove' => '✕',
//         'reply' => '☞',
//         'schedule' => '☷',
//         'search' => '🔍',
//         'settings' => '⚙',
//         'star' => '★',
//         'table' => '☷',
//         'tag' => '⛓',
//         'thumbs_up' => '👍',
//         'thumbs_down' => '👎',
//         'thumbs_none' => '👉',
//         'time' => '⌚',
//         'title' => 'T',
//         'trophy' => '🏆',
//         'unicorn' => '🦄',
//         'upload' => '⇧',
//         'url' => '🌐',
//         'user' => '☺',
//         'users' => '😂',
//         'view' => '👁',
//         'wait' => '◴',
    ];
    
    public static function iconS($icon, $iconText, $style)
    {
        if (!isset(self::$MAP[$icon]))
        {
            return GDT_IconUTF8::iconS($icon, $iconText, $style);
        }
        return sprintf('<i class="gdo-icon dx-icon-%s" %s title="%s"></i>',
            self::$MAP[$icon], $style, html($iconText));
    }

}
