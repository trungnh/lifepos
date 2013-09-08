<?php
require_once("report.php");
class Detailed_register_log extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(
			array('data'=>lang('reports_employee'), 'align'=> 'left'), 
			array('data'=>lang('reports_shift_start'), 'align'=>'left'),
			array('data'=>lang('reports_shift_end'), 'align'=>'left'),
			array('data'=>lang('reports_open_amount'), 'align'=>'left'),
			array('data'=>lang('reports_close_amount'), 'align'=>'left'),
			array('data'=>lang('reports_cash_sales'), 'align'=>'left'),
			array('data'=>lang('reports_difference'), 'align'=>'left')
		);		
	}
	
	public function getData()
	{
		$between = 'between "' . $this->params['start_date'] . ' 00:00:00" and "' . $this->params['end_date'] . ' 23:59:59"';
		$this->db->select("people.first_name, people.last_name, register_log.*, (register_log.close_amount - register_log.open_amount - register_log.cash_sales_amount) as difference");
		$this->db->from('register_log as register_log');
		$this->db->join('people as people', 'register_log.employee_id=people.person_id');
		$this->db->where('register_log.shift_start ' . $between);
		$this->db->or_where('register_log.shift_end ' . $between);
	
		$data['summary'] = $this->db->get()->result_array();
		
		$data['details'] = array();
		
		return $data;
	}
	
	public function getSummarydata() {}
}
?>