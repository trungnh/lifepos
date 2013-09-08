<?php
require_once("report.php");
class Summary_taxes extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('reports_tax_percent'), 'align'=>'left'), array('data'=>lang('reports_tax'), 'align'=>'right'));
	}
	
	public function getData()
	{
		$this->db->select('sale_id, item_id, item_kit_id, line');
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
		
		$taxes_data = array();
		foreach($this->db->get()->result_array() as $row)
		{
			if ($row['item_id'])
			{
				$this->getTaxesForItems($row['sale_id'], $row['item_id'], $row['line'], $taxes_data);
			}
			else
			{
				$this->getTaxesForItemKits($row['sale_id'], $row['item_kit_id'], $row['line'], $taxes_data);			
			}
		}
		
		return $taxes_data;
	}
	
	function getTaxesForItems($sale_id, $item_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT percent, cumulative, item_unit_price, item_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_items_taxes').' 
		JOIN '.$this->db->dbprefix('sales_items'). ' USING(sale_id, item_id, line) WHERE '.
		$this->db->dbprefix('sales_items_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.item_id = '.$item_id.' and '.
		$this->db->dbprefix('sales_items_taxes').'.line = '.$line. ' ORDER BY cumulative');
		
		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = ($row['item_unit_price']*$row['quantity_purchased']-$row['item_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['percent']]))
			{
				$taxes_data[$row['percent']] = array('percent' => $row['percent'] . ' %', 'tax' => 0);
			}
			
			$taxes_data[$row['percent']]['tax'] += $tax;
		}
		
	}
	
	function getTaxesForItemKits($sale_id, $item_kit_id, $line, &$taxes_data)
	{
		$query = $this->db->query("SELECT percent, cumulative, item_kit_unit_price, item_kit_cost_price, quantity_purchased, discount_percent FROM ".$this->db->dbprefix('sales_item_kits_taxes').' 
		JOIN '.$this->db->dbprefix('sales_item_kits'). ' USING(sale_id, item_kit_id, line) WHERE '.
		$this->db->dbprefix('sales_item_kits_taxes').'.sale_id = '.$sale_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.item_kit_id = '.$item_kit_id.' and '.
		$this->db->dbprefix('sales_item_kits_taxes').'.line = '.$line. ' ORDER BY cumulative');

		$tax_result = $query->result_array();
		for($k=0;$k<count($tax_result);$k++)
		{
			$row = $tax_result[$k];
			if ($row['cumulative'])
			{
				$previous_tax = $tax;
				$subtotal = ($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = ($subtotal + $tax) * ($row['percent'] / 100);
			}
			else
			{
				$subtotal = ($row['item_kit_unit_price']*$row['quantity_purchased']-$row['item_kit_unit_price']*$row['quantity_purchased']*$row['discount_percent']/100);
				$tax = $subtotal * ($row['percent'] / 100);
			}
			
			if (empty($taxes_data[$row['percent']]))
			{
				$taxes_data[$row['percent']] = array('percent' => $row['percent'] . ' %', 'tax' => 0);
			}
			
			$taxes_data[$row['percent']]['tax'] += $tax;
		}
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