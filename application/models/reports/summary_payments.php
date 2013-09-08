<?php
require_once("report.php");
class Summary_payments extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_payment_type'), 'align'=> 'left'), array('data'=>lang('reports_total'), 'align'=> 'right'));
	}
	
	public function getData()
	{
		$sales_totals = array();
		
		$this->db->select('sale_id, SUM(total) as total', false);
		$this->db->from('sales_items_temp');
		$this->db->group_by('sale_id');
			
		foreach($this->db->get()->result_array() as $sale_total_row)
		{
			$sales_totals[$sale_total_row['sale_id']] = $sale_total_row['total'];
		}

		$this->db->select('sales_payments.sale_id, sales_payments.payment_type, payment_amount', false);
		$this->db->from('sales_payments');
		$this->db->join('sales', 'sales.sale_id=sales_payments.sale_id');
		$this->db->where('date(sale_time) BETWEEN "'. $this->params['start_date']. '" and "'. $this->params['end_date'].'"');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('payment_amount > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('payment_amount < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales').'.deleted', 0);
		$this->db->order_by('sale_id, payment_type');
		$sales_payments = $this->db->get()->result_array();
		
		foreach($sales_payments as $row)
		{
        	$payments_by_sale[$row['sale_id']][] = $row;
		}
		
		$payment_data = array();
		
		foreach($payments_by_sale as $sale_id => $payment_rows)
		{
			$total_sale_balance = $sales_totals[$sale_id];
			
			foreach($payment_rows as $row)
			{
				$payment_amount = $row['payment_amount'] <= $total_sale_balance ? $row['payment_amount'] : $total_sale_balance;
				
				if (!isset($payment_data[$row['payment_type']]))
				{
					$payment_data[$row['payment_type']] = array('payment_type' => $row['payment_type'], 'payment_amount' => 0 );
				}
				
				if ($total_sale_balance != 0)
				{
					$payment_data[$row['payment_type']]['payment_amount'] += $payment_amount;
				}
				
				$total_sale_balance-=$payment_amount;
			}
		}
		
		return $payment_data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where($this->db->dbprefix('sales_items_temp').'.deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>