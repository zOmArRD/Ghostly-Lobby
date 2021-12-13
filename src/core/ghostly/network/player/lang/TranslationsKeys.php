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

namespace core\ghostly\network\player\lang;

final class TranslationsKeys
{
    /* @global string */
    const ERROR = "message.error";
    const NO_PERMS = "message.noperms";

    /* @form buttons section */
    const BUTTON_CLOSE = "form.button.close";
    const BUTTON_BACK = "form.button.back";
    const BUTTON_JOIN = "form.button.join";
    const BUTTON_ENABLE = "form.button.enable";
    const BUTTON_DISABLE = "form.button.disable";
    const BUTTON_LOCKED = "form.button.locked";
    const BUTTON_UNLOCKED = "form.button.unlocked";

    /* @lang section */
    const FORM_TITLE_LANG = "form.title.lang.selector";
    const LANG_SET_DONE = "message.lang.set.done";
    const LANG_SET_FAIL = "message.lang.set.fail";

    /* @command section */
    const COMMAND_NOEXIST = "command.noexist";

    const ONLY_PLAYER = PREFIX . "This function can only be performed by player";
}