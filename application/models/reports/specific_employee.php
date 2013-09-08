<?php
require_once("report.php");
class Specific_employee extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array('summary' => array(array('data'=>lang('reports_sale_id'), 'align'=>'left'), array('data'=>lang('reports_date'), 'align'=>'left'), array('data'=>lang('reports_items_purchased'), 'align'=>'left'), array('data'=>lang('reports_sold_to'), 'align'=>'left'), array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'), array('data'=>lang('reports_profit'), 'align'=>'left'), array('data'=>lang('reports_payment_type'), 'align'=>'left'), array('data'=>lang('reports_comments'), 'align'=>'left')),
					'details' => array(array('data'=>lang('reports_name'), 'align'=>'left'), array('data'=>lang('reports_category'), 'align'=>'left'), array('data'=>lang('reports_serial_number'), 'align'=>'left'), array('data'=>lang('reports_description'), 'align'=>'left'), array('data'=>lang('reports_quantity_purchased'), 'align'=>'left'), array('data'=>lang('reports_subtotal'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'left'), array('data'=>lang('reports_profit'), 'align'=>'left'),array('data'=>lang('reports_discount'), 'align'=>'left'))
		);		
	}
	
	public function getData()
	{
		$this->db->select('sale_id, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(first_name," ",last_name) as customer_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, payment_type, comment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people', 'sales_items_temp.customer_id = people.person_id', 'left');
		$this->db->where('employee_id = '.$this->params['employee_id']);
		
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_id');
		$this->db->order_by('sale_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('items.name as item_name, item_kits.name as item_kit_name, sales_items_temp.category, serialnumber, sales_items_temp.description, quantity_purchased, subtotal,total, tax, profit, discount_percent');
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id', 'left');
			$this->db->join('item_kits', 'sales_items_temp.item_kit_id = item_kits.item_kit_id', 'left');
			$this->db->where('sale_id = '.$value['sale_id']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->where('employee_id = '.$this->params['employee_id']);
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		return $this->db->get()->row_array();
	}
}
?>