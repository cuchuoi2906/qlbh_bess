<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-08
 * Time: 09:50
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\UserMoneyLog;

class UserMoneyLogTransformer extends TransformerAbstract
{

    public function transform($item)
    {
        $types = UserMoneyLog::types();
        $sources = UserMoneyLog::source();
        $source = $sources[$item->source] ?? '';
        if ($item->source_type == UserMoneyLog::SOURCE_TYPE_ORDER) {
            $source = ($item->order ?? false) ? ('Đơn hàng: ' . $item->order->code) : '';
        }
        if($item->source_type == UserMoneyLog::SOURCE_TYPE_PAYMENT)
        {
            $source = ($item->paymentBank ?? false) ? ('Rút về ngân hàng: ' . $item->paymentBank->bank_name) : '';
        }

        return [
            'id' => (int)$item->id,
            'amount' => $item->amount,
            'amount_formatted' => number_format($item->amount),
            'current' => number_format($item->current),
            'note' => $item->note,
            'type_wallet' => $types[$item->type] ?? '',
            'source' => $source,
            'created_at' => new \DateTime($item->created_at),
        ];
    }

}