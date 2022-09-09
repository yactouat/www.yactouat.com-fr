<?php

namespace App;

final class Conf {

    /**
     * checks if dev PHP conf. corresponds to what's expected
     * 
     * - `display_errors` is supposed to be on
     * - `display_startup_errors` is supposed to be on
     *
     * @return void
     */
    public static function checkDevConf(): bool {
        return ini_get("display_errors") == 1 && ini_get("display_startup_errors") == 1;
    }

    /**
     * checks if shared PHP conf. corresponds to what's expected
     * 
     * - `error_reporting` is supposed to be `E_ALL`
     * - `log_errors` is supposed to be on
     *
     * @return void
     */
    public static function checkSharedConf(): bool {
        return ini_get("error_reporting") == E_ALL && ini_get("log_errors") == 1;
    }

}