<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/28/2019
 * Time: 9:30 AM
 */

namespace AppView\Controllers\Api;


class SettingsWebsiteController extends ApiController
{
    public function getSettings()
    {
        $data = model('settings_website/index')->load();

        $settings = [];
        foreach ($data['vars'] ?? [] as $setting) {
            $settings[$setting['key']] = $setting['value'];
        }

        return $settings;
    }

    public function getBankAccounts()
    {
        $data = model('settings_website/index')->load();

        $settings = [];
        $count = 0;
        foreach ($data['vars'] ?? [] as $setting) {
            if (false !== strpos($setting['key'], 'bank_account_')) {
                $settings[$setting['key']] = $setting['value'];
            }
            if (false !== strpos($setting['key'], 'bank_account_name_')) {
                $count++;
            }
        }

        $response = [];
        for ($i = 1; $i <= $count; $i++) {
            $response[] = [
                'account_number' => $settings['bank_account_number_' . $i],
                'name' => $settings['bank_account_bank_name_' . $i],
                'account_name' => $settings['bank_account_name_' . $i],
                'branch' => $settings['bank_account_branch_' . $i]

            ];
        }

        return $response;
    }

    public function getStatus($setting_code)
    {

        $result = model('settings_website/get_by_key')->load([
            'key' => $setting_code
        ]);

        return [
            'status' => (bool)($result['vars']['value'] ?? false)
        ];
    }
}