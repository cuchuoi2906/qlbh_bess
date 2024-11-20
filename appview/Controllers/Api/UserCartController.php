<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 12:47
 */

namespace AppView\Controllers\Api;


class UserCartController extends ApiController
{

    public function getIndex()
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);
		if(!check_array($result['vars'])){
			$_SESSION['cartTotalProduct'] = 0;
		}else{
			$_SESSION['cartTotalProduct'] = $result['vars']['meta']['total_product_cart'];
		}

        return $result['vars'];
    }

    public function postAdd()
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $product_id = getValue('product_id', 'int', 'POST', 0, 0);
        $quantity = getValue('quantity', 'int', 'POST', 0, 0);
        $is_add_more = getValue('is_add_more', 'int', 'POST', 0, 0);
		
		$result = model('user_cart/index')->load([
            'user_id' => $_SESSION['userIdFe']
        ]);
		if(!check_array($result['vars']) || $is_add_more == 3){
			$is_add_more = 0;
		}else{
			//pre($result['vars']);die;
			$is_add_more = 0;
			for($i=0;$i<20;$i++){
				if(isset($result['vars'][$i]) && $result['vars'][$i]['product']['id'] == $product_id){
					$is_add_more = 1;
					break;
				}
			}
		}
        model('user_cart/add')->load([
            'user_id' => $_SESSION['userIdFe'],
            'product_id'=>$product_id,
            'quantity'=>$quantity,
            'is_add_more'=>$is_add_more
        ] + $this->input);

        return $this->getIndex();
    }
	public function postDelete()
    {
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }
        $product_id = getValue('product_id', 'int', 'POST', 0, 0);

        $result = model('user_cart/add')->load([
            'user_id' => $_SESSION['userIdFe'],
            'product_id'=>$product_id,
            'is_add_more'=>2
        ] + $this->input);
		$this->getIndex();
		
        return $result['vars'];
    }
    function postDeleteAll(){
        if (!checkLoginFe()) {
            return redirect(url('login'));
        }

        $result = model('user_cart/add')->load([
            'user_id' => $_SESSION['userIdFe'],
            'product_id'=>1,
            'is_add_more'=>4
        ] + $this->input);
		$this->getIndex();
		
        return $result['vars'];
    }

}