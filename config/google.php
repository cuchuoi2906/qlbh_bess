<?php
/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 12/2/16
 * Time: 11:20 PM
 */

return [
    'app_id' => env('GOOGLE_APP_ID', '202801604496-l9o40nbtckvecvmbt6c77thivrsotppc.apps.googleusercontent.com'),

    'app_secret' => env('GOOGLE_APP_SECRET', 'Xe52iz8LaS0P7DILe3yNVn4o'),

    'callback_url' => env('GOOGLE_CALLBACK_URL', '/auth/google/callback'),
];