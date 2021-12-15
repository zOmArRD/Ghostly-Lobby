<?php
/*
 * Created by PhpStorm.
 *
 * User: zOmArRD
 * Date: 12/12/2021
 *
 * Copyright © 2021 Ghostly Network - All Rights Reserved.
 */
declare(strict_types=1);

namespace zomarrd\ghostly\network\player\lang;

final class TranslationsKeys
{
    /* @global string */
    public const ERROR = 'message.error';
    public const NO_PERMS = 'message.noperms';

    /* @form buttons section */
    public const BUTTON_CLOSE = 'form.button.close';
    public const BUTTON_BACK = 'form.button.back';
    public const BUTTON_JOIN = 'form.button.join';
    public const BUTTON_ENABLE = 'form.button.enable';
    public const BUTTON_DISABLE = 'form.button.disable';
    public const BUTTON_LOCKED = 'form.button.locked';
    public const BUTTON_UNLOCKED = 'form.button.unlocked';

    /* @lang section */
    public const FORM_TITLE_LANG = 'form.title.lang.selector';
    public const LANG_SET_DONE = 'message.lang.set.done';
    public const LANG_SET_FAIL = 'message.lang.set.fail';

    /* @command section */
    public const COMMAND_NOEXIST = 'command.noexist';

    public const ONLY_PLAYER = PREFIX . 'This function can only be performed by player';
}