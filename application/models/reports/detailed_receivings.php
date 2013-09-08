<?php
require_once("report.php");
class Detailed_receivings extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array('summary' => array(array('data'=>lang('reports_receiving_id'), 'align'=>'left'), array('data'=>lang('reports_date'), 'align'=>'left'), array('data'=>lang('reports_items_received'), 'align'=>'left'), array('data'=>lang('reports_received_by'), 'align'=>'left'), array('data'=>lang('reports_supplied_by'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'right'), array('data'=>lang('reports_payment_type'), 'align'=>'left'), array('data'=>lang('reports_comments'), 'align'=>'left')),
					'details' => array(array('data'=>lang('reports_name'), 'align'=>'left'), array('data'=>lang('reports_category'), 'align'=>'left'), array('data'=>lang('reports_quantity_purchased'), 'align'=>'left'), array('data'=>lang('reports_total'), 'align'=>'right'), array('data'=>lang('reports_discount'), 'align'=>'left'))
		);		
	}
	
	public function getData()
	{
		$this->db->select('receiving_id, receiving_date, sum(quantity_purchased) as items_purchased, CONCAT(employee.first_name," ",employee.last_name) as employee_name, CONCAT(supplier.first_name," ",supplier.last_name) as supplier_name, sum(total) as total, sum(profit) as profit, payment_type, comment', false);
		$this->db->from('receivings_items_temp');
		$this->db->join('people as employee', 'receivings_items_temp.employee_id = employee.person_id');
		$this->db->join('people as supplier', 'receivings_items_temp.supplier_id = supplier.person_id', 'left');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->where('deleted', 0);
		$this->db->group_by('receiving_id');
		$this->db->order_by('receiving_date');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('name, category, quantity_purchased, serialnumber,total, discount_percent');
			$this->db->from('receivings_items_temp');
			$this->db->join('items', 'receivings_items_temp.item_id = items.item_id');
			$this->db->where('receiving_id = '.$value['receiving_id']);
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(total) as total');
		$this->db->from('receivings_items_temp');
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