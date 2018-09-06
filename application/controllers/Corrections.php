<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('Secure_Controller.php');

class Corrections extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('sales');

		$this->load->model('Correction');
		$this->load->helper('correction');

		$CI =& get_instance();
	}

	function correct_total()
	{
		$new_total = parse_decimals($this->input->post("value", TRUE));
		$date = parse_date($this->input->post("date", TRUE));
		if (is_numeric($new_total)) {
			$this->db->trans_start();
			$result = correct_total($date, $new_total);
			$this->db->trans_complete();
		}
		echo isset($result) ? $result["new_total"] : $new_total;
	}

	function get_summary_totals_sales($start_date, $end_date = null, $sale_type=0)
	{
		$end_date = urldecode($end_date ? $end_date : $start_date);
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$summary = $model->getSummaryData(array(
			'start_date'=>$start_date,
			'end_date'=>$end_date,
			'sale_type' => $sale_type,
			'location_id' => 'all'));
		echo get_sales_summary_totals($summary);
	}

	// just return a result for one date!!
	function get_summary_sales_row()
	{
		$date = $this->input->get('date');
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$tabular_data = array();
		$report_data = $model->getData(array('start_date'=>parse_date($date), 'end_date'=>parse_date($date), 'sale_type' => 0, 'location_id' => 'all'));

		foreach($report_data as $row)
		{
			$tabular_data[] = array(to_date(strtotime($row['sale_date'])), $row['quantity_purchased'], to_currency($row['subtotal']), to_currency($row['total']), to_currency($row['tax']),to_currency($row['cost']), to_currency($row['profit']));
		}
		echo json_encode($tabular_data[0]);
	}

}
?>