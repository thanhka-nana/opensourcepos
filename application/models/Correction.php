<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Correction extends CI_Model
{

	function get_day_total($sale_time)
	{
		$this->db->select("SUM(ROUND((item_unit_price*quantity_purchased-item_unit_price*quantity_purchased*discount_percent/100),2)) as total", FALSE);
		$this->db->from('sales');
		$this->db->join('sales_items', "sales_items.sale_id = sales.sale_id");
		$this->db->where("DATE_FORMAT(sale_time, '%Y-%m-%d') = '" .  date('Y-m-d', strtotime($sale_time)) . "'");
		return $this->db->get()->result_array();
	}

	function get_totals($from, $to=NULL)
	{
		$this->db->select("SUM(payment_amount)");
		$this->db->select('sale_time');
		$this->db->from('sales');
		$this->db->join('sales_payments', "sales_payments.sale_id = sales.sale_id");
		if (isset($to))
		{
			$this->db->where("sale_time BETWEEN DATE(" . $this->db->escape($from) . ") AND DATE(" . $this->db->escape($to) . ")");
			$this->db->group_by("DAY(sale_time)");
		}
		else
		{
			$this->db->where("DATE(sale_time) = DATE(" . $this->db->escape($from) . ")");
		}
		return $this->db->get();
	}

	function get_payments_by_day($sale_time)
	{
		$this->db->from('sales');
		$this->db->join('sales_payments', "sales_payments.sale_id = sales.sale_id");
		$this->db->where("DATE_FORMAT(sale_time,'%Y-%m-%d') = " . $this->db->escape(date('Y-m-d', strtotime($sale_time))));
		$this->db->where('payment_type', $this->lang->line('sales_cash'));
		$this->db->where('invoice_number IS NULL');
		$this->db->or_where('invoice_number', '');
		return $this->db->get()->result_array();
	}

	function update_sale_item($sale_id, $item_id, $sale_item)
	{
		$this->db->where('sale_id', $sale_id);
		$this->db->where('item_id', $item_id);
		$this->db->update('sales_items', $sale_item);
	}

	function update_sale_payment($sale_payment_id, $sale_payment)
	{
		$this->db->where('sale_id', $sale_payment_id);
		$this->db->where('payment_type', 'Contant');
		$this->db->update('sales_payments', $sale_payment);
	}

}

?>