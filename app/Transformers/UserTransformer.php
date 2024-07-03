<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:06
 */

namespace App\Transformers;


use App\Models\Users\Users;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'banks',
        'wallet',
        'parent',
        'addresses'
    ];

    /**
     * @param Users $user
     * @return array
     */
    public function transform($user)
    {

        if(!$user)
        {
            return [];
        }
//        $user->birthdays = isset($user->birthdays) ? (($user->birthdays && $user->birthdays != 'null') ? $user->birthdays : '') : '';
        //Lấy số commission kiếm được
        $total_commission = $user->getTotalCommissionForUpLevel();
        $owner_commission = $user->getOwnerCommission();
        //Get next level
        $next_level = $user->getNextLevel();
        $setting_level = $user->getSettingLever();
        //Lấy số tiền để lên next level
        $next_level_commission = setting('user_up_level_' . $next_level . '_threshold');
        $current_level_commission = setting('user_up_level_' . $user->level . '_threshold');

        $data = [
            'id' => (int)$user->id,
            'active' => (int)$user->active,
            'name' => (string)($user->name ?? ''),
            'phone' => (string)($user->phone ?? ''),
            'avatar' => (string)($user->avatar ? (url() . '/upload/images/' . $user->avatar) : ''),
            'gender' => $user->getGender(),
            'birthdays' => (string)$user->birthdays,
            'address' => (string)($user->address ?? ''),
            'email' => (string)($user->email ?? ''),
            'identity' => [
                'number' => $user->identity_number ?? '',
                'front_image' => $user->identity_front_image ? (url() . '/upload/images/' . $user->identity_front_image) : '',
                'back_image' => $user->identity_back_image ? (url() . '/upload/images/' . $user->identity_back_image) : '',
            ],
            'total_refer' => (int)$user->total_refer,
            'total_direct_refer' => (int)$user->total_direct_refer,
            'level' => (int)$user->level,
            'level_config' => (int)$setting_level,
            'view_id' => ((int)$user->level <(int)$setting_level) ? 1 : 0,
            'level_progress' => [
                'level' => (int)$user->level,
                'next_level' => $next_level,
                'current_commission' => (floor($total_commission / 1000)),
                'next_level_commission' => floor($next_level_commission / 1000),
                'current_level_commission' => ((int)$current_level_commission / 1000)
            ],
            'created_at' => $user->created_at,
            'total_order' => (int)$user->total_order,
            'total_order_success' => (int)$user->total_order_success,
            'team_commission' => number_format((int)floor($user->use_commission / 1000)),
            'owner_commission' => number_format((int)floor($owner_commission / 1000)),
            'is_family' => (int)$user->family, //Luôn luôn được mua với mức chiết khấu đầu tiên
            'premium' => (int)$user->premium, //User đặc biệt, được nhận % hoa hồng theo cấu hình premium_commission không phụ thuộc vào level
            'premium_commission' => (int)$user->premium_commission,
            'is_seller' => (int)$user->is_seller, //Luôn luôn được mua hàng với mức chiết khấu cao nhất
            'referer_code' => $user->referer_code,
            'referer_link' => url('invite', ['base64' => base64_encode($user->id)]),
            'f' => compare_user($user->all_level, app('auth')->u_id ?? 0, $user->id),
            'total_ord_amount' => (int)$user->total_ord_amount,
        ];

        return $data;
    }

    public function includeBanks($user)
    {
        $banks = $user->banks ?? collect([]);

        return $this->collection($banks, new UserBankTransformer());
    }

    public function includeAddresses($user)
    {
        $address = $user->addresses ?? collect([]);

        return $this->collection($address, new UserAddressTransformer());
    }

    public function includeWallet($user)
    {
        $wallet = $user->wallet ?? collect([]);

        return $this->item($wallet, new UserWalletTransformer());
    }

    public function includeParent($user)
    {
        $parent = $user->parent ?? new Users();

        return $this->item($parent, new static());
    }

    public function includeStatisticOrder($user)
    {

    }

}