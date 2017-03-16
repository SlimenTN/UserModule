<?php
/**
 * Configuration file of UserModule
 */

return [
    //-default route after login
    'after_login_route' => 'domain_chooser',

    /**
     * Encryption method
     * Available:
     *    - md5
     *    - sha1
     */
    'encryption' => 'md5',
];