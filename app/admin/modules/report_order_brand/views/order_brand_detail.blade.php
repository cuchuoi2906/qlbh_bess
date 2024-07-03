@extends('module-master')

@section('content')
	<div class="modal-header">
	Số lượng thương hiệu: <?php echo $v_so_luong_thuong_hieu; ?>
	</div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Tên thương hiệu</th>
            <th>Doanh thu theo thương hiệu</td>
            <th>Tỷ trọng doanh thu</th>
        </tr>
        </thead>
        <tbody>
            <?php
			$tong_doanh_thu = 0;
			$tong_rate = 0;
            foreach($v_arr_data as $items){
				$tong_doanh_thu += $items['money'];
				$tong_rate += $items['rate_brand'];
            ?>
                <tr>
                    <td><?php echo $items['pro_code']; ?></td>
                    <td>
                        <?php echo number_format($items['money']); ?> đ
                    </td>
                    <td>
                        <?php echo number_format($items['rate_brand'],2); ?>%
                    </td>
                </tr>
            <?php 
            }?>
			<tr>
			<td>&nbsp;</td>
			<td class="field-total"><?php echo number_format($tong_doanh_thu); ?> đ</td>
			<td class="field-total"><?php echo $tong_rate; ?>%</td>
			</tr>
        </tbody>
    </table>
@stop