<?php
require_once("report.php");
class Summary_sales extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		return array(
							array('data'=>lang('reports_date'), 'align'=>'left'), 
							array('data'=>lang('reports_subtotal'), 'align'=>'right'), 
							array('data'=>lang('reports_total'), 'align'=>'right'), 
							array('data'=>lang('reports_tax'), 'align'=>'right'), 
							array('data'=>lang('reports_profit'), 'align'=>'right'));
	}
	
	public function getData()
	{		
		$this->db->select('sale_date, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit');
		$this->db->from('sales_items_temp');
		if ($this->params['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($this->params['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where('deleted', 0);
		$this->db->group_by('sale_date');
		$this->db->order_by('sale_date');
		return $this->db->get()->result_array();
	}
	
	public function getSummaryData()
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit');
		$this->db->from('sales_items_temp');
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