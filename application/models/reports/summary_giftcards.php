<?php
require_once("report.php");
class Summary_giftcards extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array(array('data'=>lang('giftcards_giftcard_number'), 'align'=>'left'), array('data'=>lang('giftcards_card_value'), 'align'=> 'left'), array('data'=>lang('giftcards_customer_name'), 'align'=> 'left'));
	}
	
	public function getData()
	{
		$this->db->select('giftcard_number, value, CONCAT(first_name, " ",last_name) as customer_name', false);
		$this->db->from('giftcards');
		$this->db->where('deleted', 0);
		$this->db->join('people', 'giftcards.customer_id = people.person_id', 'left');
		$this->db->order_by('giftcard_number');

		return $this->db->get()->result_array();		
	}
	
	public function getSummaryData()
	{
		return array();
	}
}
?>