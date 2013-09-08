<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Item_kits extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('item_kits');
	}

	function index()
	{
		$config['base_url'] = site_url('item_kits/index');
		$config['total_rows'] = $this->Item_kit->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_item_kits_manage_table( $this->Item_kit->get_all($config['per_page'], $this->uri->segment(3)),$this);
		$this->load->view('item_kits/manage',$data);
	}
	

	/* added for excel expert */
	function excel_export() {
		$data = $this->Item_kit->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array("UPC/EAN/ISBN", "Item Kit Name", "Category", "Cost Price", "Unit Price", "Tax1 Name" , "Tax1 Value", "Tax2 Name", "Tax2 Value", "Cumulative?", "Description");
		$rows[] = $row;
		
		foreach ($data as $r) {
			$taxdata = $this->Item_kit_taxes->get_info($r->item_kit_id);
			if (sizeof($taxdata) >= 2) {
				$r->taxn = $taxdata[0]['name'];
				$r->taxp = $taxdata[0]['percent'];
				$r->taxn1 = $taxdata[1]['name'];
				$r->taxp1 = $taxdata[1]['percent'];
				$r->cumulative = $taxdata[1]['cumulative'] ? 'y' : '';
			} else if (sizeof($taxdata) == 1) {
				$r->taxn = $taxdata[0]['name'];
				$r->taxp = $taxdata[0]['percent'];
				$r->taxn1 = '';
				$r->taxp1 = '';
				$r->cumulative = '';
			} else {
				$r->taxn = '';
				$r->taxp = '';
				$r->taxn1 = '';
				$r->taxp1 = '';
				$r->cumulative = '';
			}
			
			$row = array(
				$r->item_kit_number,
				$r->name,
				$r->category,
				$r->cost_price,
				$r->unit_price,
				$r->taxn,
				$r->taxp,
				$r->taxn1,
				$r->taxp1,
				$r->cumulative,
				$r->description
			);
			
			$rows[] = $row;		
		}
		
		$content = array_to_csv($rows);
		force_download('itemkits_export' . '.csv', $content);
		exit;
	}

	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_item_kits_manage_table_data_rows($this->Item_kit->search($search,$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20),$this);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item_kit->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function get_row()
	{
		$item_kit_id = $this->input->post('row_id');
		$data_row=get_item_kit_data_row($this->Item_kit->get_info($item_kit_id),$this);
		echo $data_row;
	}

	function view($item_kit_id=-1)
	{
		$data['item_kit_info']=$this->Item_kit->get_info($item_kit_id);
		$data['item_kit_tax_info']=$this->Item_kit_taxes->get_info($item_kit_id);
		$data['default_tax_1_rate']=($item_kit_id==-1) ? $this->Appconfig->get('default_tax_1_rate') : '';
		$data['default_tax_2_rate']=($item_kit_id==-1) ? $this->Appconfig->get('default_tax_2_rate') : '';
		$data['default_tax_2_cumulative']=($item_kit_id==-1) ? $this->Appconfig->get('default_tax_2_cumulative') : '';
		$this->load->view("item_kits/form",$data);
	}
	
	function save($item_kit_id=-1)
	{
		$item_kit_data = array(
		'item_kit_number'=>$this->input->post('item_kit_number')=='' ? null:$this->input->post('item_kit_number'),
		'name'=>$this->input->post('name'),
		'category'=>$this->input->post('category'),
		'unit_price'=>$this->input->post('unit_price')=='' ? null:$this->input->post('unit_price'),
		'cost_price'=>$this->input->post('cost_price')=='' ? null:$this->input->post('cost_price'),
		'description'=>$this->input->post('description')
		);
		
		if($this->Item_kit->save($item_kit_data,$item_kit_id))
		{
			//New item kit
			if($item_kit_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_adding').' '.
				$item_kit_data['name'],'item_kit_id'=>$item_kit_data['item_kit_id']));
				$item_kit_id = $item_kit_data['item_kit_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_updating').' '.
				$item_kit_data['name'],'item_kit_id'=>$item_kit_id));
			}
			
			if ($this->input->post('item_kit_item'))
			{
				$item_kit_items = array();
				foreach($this->input->post('item_kit_item') as $item_id => $quantity)
				{
					$item_kit_items[] = array(
						'item_id' => $item_id,
						'quantity' => $quantity
						);
				}
			
				$this->Item_kit_items->save($item_kit_items, $item_kit_id);
			}
			
			$item_kits_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_cumulatives = $this->input->post('tax_cumulatives');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$item_kits_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
				}
			}
			$this->Item_kit_taxes->save($item_kits_taxes_data, $item_kit_id);
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('item_kits_error_adding_updating').' '.
			$item_kit_data['name'],'item_kit_id'=>-1));
		}

	}
	
	function delete()
	{
		$item_kits_to_delete=$this->input->post('ids');

		if($this->Item_kit->delete_list($item_kits_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('item_kits_successful_deleted').' '.
			count($item_kits_to_delete).' '.lang('item_kits_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('item_kits_cannot_be_deleted')));
		}
	}
	
	function generate_barcodes($item_kit_ids)
	{
		$result = array();

		$item_kit_ids = explode('~', $item_kit_ids);
		foreach ($item_kit_ids as $item_kid_id)
		{
			$item_kit_info = $this->Item_kit->get_info($item_kid_id);

			$result[] = array('name' =>$item_kit_info->name.': '.to_currency($item_kit_info->unit_price), 'id'=> 'KIT '.number_pad($item_kid_id, 7));
		}

		$data['items'] = $result;
		$data['scale'] = 2;
		$this->load->view("barcode_sheet", $data);
	}
	
	function generate_barcode_labels($item_kit_ids)
	{
		$result = array();

		$item_kit_ids = explode('~', $item_kit_ids);
		foreach ($item_kit_ids as $item_kid_id)
		{
			$item_kit_info = $this->Item_kit->get_info($item_kid_id);

			$result[] = array('name' =>$item_kit_info->name.': '.to_currency($item_kit_info->unit_price), 'id'=> 'KIT '.number_pad($item_kid_id, 7));
		}

		$data['items'] = $result;
		$data['scale'] = 1;
		$this->load->view("barcode_labels", $data);
	}
	
	
	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 550;
	}
}
?>