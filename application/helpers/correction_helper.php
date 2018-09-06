<?php

define("DEFAULT_DISCOUNT", 60);
define("DEFAULT_DISCOUNT_DECR", 10);

function calc_discount($discount_percent)
{
	$discount = bcdiv($discount_percent, 100, 2);
	return bcsub(1, $discount, 2);
}

function calc_total_diff($unit_price, $old_discount_percent, $new_discount_percent)
{
	$old_price = calc_discounted_price($unit_price, $old_discount_percent);
	$new_price = calc_discounted_price($unit_price, $new_discount_percent);
	$difference = bcsub($old_price, $new_price, 2);
	return array("old_price" => $old_price, "new_price" => $new_price, "difference" => $difference);
}

function calc_discounted_price($unit_price, $discount_percent)
{
	$percentage = calc_discount($discount_percent);
	return bcmul($unit_price, $percentage, 2);
}

function calc_cost_price($unit_price, $discount_percent)
{
	$discounted_price = calc_discounted_price($unit_price, $discount_percent);
	return bcdiv($discounted_price, 1.21, 2);
}

function correct_total($date, $new_total)
{
	$CI =& get_instance();
	$total = $CI->Correction->get_totals($date)->row_array();

	$difference = bcsub($total["SUM(payment_amount)"], $new_total, 2);
	$payments = $CI->Correction->get_payments_by_day($total["sale_time"]);
	$output_entry = array("sale_time" => $total["sale_time"],
		"actual_total" => $total["SUM(payment_amount)"],
		"new_total" => $new_total);
	$payed_amounts = 0;
	foreach($payments as $payment) {
		$items = $CI->Sale->get_sale_items($payment['sale_id'])->result_array();
		$payment_amount = $payment['payment_amount'];
		foreach ($items as $item ) {
			$discount_percent = $item['discount'];
			$quantity = $item['quantity_purchased'];
			$unit_price = $item['item_unit_price'];
			log_message('debug', 'new total');
			if ($discount_percent < DEFAULT_DISCOUNT && $quantity == 1) {
				$disc_diff = $difference;
				$new_discount_percent = DEFAULT_DISCOUNT + DEFAULT_DISCOUNT_DECR;
				while($disc_diff >= $difference && $new_discount_percent -= DEFAULT_DISCOUNT_DECR > 0) {
					$diff = calc_total_diff($unit_price, $discount_percent, $new_discount_percent);
					$disc_diff = $diff["difference"];
				}
				if ($disc_diff < $difference) {
					$cost_price = calc_cost_price($unit_price, $new_discount_percent);
					$CI->Correction->update_sale_item($item['sale_id'], $item['item_id'],
						array('item_cost_price' => $cost_price, 'discount' => $new_discount_percent));
					$difference = bcsub($difference, $disc_diff, 2);
					$payment_amount = bcsub($payment_amount, $disc_diff, 2);
					$output_entry[$item['item_id']] = array(
						"discount_difference" => $diff["difference"],
						"old_price" => $diff["old_price"],
						"new_price" => $diff["new_price"],
						"remaining" => $difference);
				}
			}
			$CI->Correction->update_sale_payment($payment['sale_id'],
				array('payment_amount' => $payment_amount));
		}
	}
	return $output_entry;
}

function get_sales_summary_totals($summary)
{
	$CI =& get_instance();
	$table_data_row = "<div id=\"report_summary\">";
	foreach ($summary as $name => $value) {
		$table_data_row .= "<div class=\"summary_row\">";
		$table_data_row .= $CI->lang->line('reports_' . $name);
		$table_data_row .= ": ";
		$table_data_row .= to_currency($value);
		$table_data_row .= "</div>";
	}
	$table_data_row .= "</div>";
	return $table_data_row;
}


function get_sales_summary_data_row($sale)
{
	$table_data_row='<tr>';
	foreach($sale as $row)
	{
		foreach($row as $cell)
		{
			$table_data_row.='<td>';
			$table_data_row.=$cell;
			$table_data_row.='</td>';
		}
	}
	$table_data_row.='</tr>';

	return $table_data_row;
}

?>