<?php
require_once(APPPATH.'libraries/php-excel.class.php');
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Items extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('items');
	}

	function index()
	{
		$config['base_url'] = site_url('items/index');
		$config['total_rows'] = $this->Item->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_items_manage_table($this->Item->get_all($config['per_page'], $this->uri->segment(3)),$this);
		$this->load->view('items/manage',$data);
	}
	
	function find_item_info()
	{
		$item_number=$this->input->post('scan_item_number');
		echo json_encode($this->Item->find_item_info($item_number));
	}

	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_items_manage_table_data_rows($this->Item->search($search,$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20),$this);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest_category()
	{
		$suggestions = $this->Item->get_category_suggestions($this->input->get('term'));
		echo json_encode($suggestions);
	}

	function get_row()
	{
		$item_id = $this->input->post('row_id');
		$data_row=get_item_data_row($this->Item->get_info($item_id),$this);
		echo $data_row;
	}
	
	function get_info($item_id=-1)
	{
		echo json_encode($this->Item->get_info($item_id));
	}
	
	function view($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_tax_info']=$this->Item_taxes->get_info($item_id);
		$suppliers = array('' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
		}

		$data['suppliers']=$suppliers;
		$data['selected_supplier'] = $this->Item->get_info($item_id)->supplier_id;
		$data['default_tax_1_rate']=($item_id==-1) ? $this->Appconfig->get('default_tax_1_rate') : '';
		$data['default_tax_2_rate']=($item_id==-1) ? $this->Appconfig->get('default_tax_2_rate') : '';
		$data['default_tax_2_cumulative']=($item_id==-1) ? $this->Appconfig->get('default_tax_2_cumulative') : '';
		$this->load->view("items/form",$data);
	}
	
	//Ramel Inventory Tracking
	function inventory($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/inventory",$data);
	}
	
	function count_details($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/count_details",$data);
	} //------------------------------------------- Ramel

	function generate_barcodes($item_ids)
	{
		$result = array();

		$item_ids = explode('~', $item_ids);
		foreach ($item_ids as $item_id)
		{
			$item_info = $this->Item->get_info($item_id);

			$result[] = array('name' =>$item_info->name.': '.to_currency($item_info->unit_price), 'id'=> number_pad($item_id, 11));
		}

		$data['items'] = $result;
		$data['scale'] = 2;
		$this->load->view("barcode_sheet", $data);
	}
	
	function generate_barcode_labels($item_ids)
	{
		$result = array();

		$item_ids = explode('~', $item_ids);
		foreach ($item_ids as $item_id)
		{
			$item_info = $this->Item->get_info($item_id);

			$result[] = array('name' =>$item_info->name, 'id'=> number_pad($item_id, 11),'price'=>to_currency($item_info->unit_price));
		}

		$data['items'] = $result;
		$data['scale'] = 1;
		$this->load->view("barcode_labels", $data);
	}

	function bulk_edit()
	{
		$data = array();
		$suppliers = array('' => lang('items_do_nothing'), '-1' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name']. ' ('.$row['first_name'] .' '. $row['last_name'].')';
		}
		$data['suppliers'] = $suppliers;
		$data['allow_alt_desciption_choices'] = array(
			''=>lang('items_do_nothing'), 
			1 =>lang('items_change_all_to_allow_alt_desc'),
			0 =>lang('items_change_all_to_not_allow_allow_desc'));
				
		$data['serialization_choices'] = array(
			''=>lang('items_do_nothing'), 
			1 =>lang('items_change_all_to_serialized'),
			0 =>lang('items_change_all_to_unserialized'));
		$this->load->view("items/form_bulk", $data);
	}

	function save($item_id=-1)
	{
		$item_data = array(
		'name'=>$this->input->post('name'),
		'description'=>$this->input->post('description'),
		'category'=>$this->input->post('category'),
		'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
		'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
		'cost_price'=>$this->input->post('cost_price'),
		'unit_price'=>$this->input->post('unit_price'),
		'quantity'=>$this->input->post('quantity'),
		'reorder_level'=>$this->input->post('reorder_level'),
		'location'=>$this->input->post('location'),
		'allow_alt_description'=>$this->input->post('allow_alt_description'),
		'is_serialized'=>$this->input->post('is_serialized')
		);
		
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);


		if($this->Item->save($item_data,$item_id))
		{
			//New item
			if($item_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_adding').' '.
				$item_data['name'],'item_id'=>$item_data['item_id']));
				$item_id = $item_data['item_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_updating').' '.
				$item_data['name'],'item_id'=>$item_id));
			}
			
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item_id,
				'trans_user'=>$employee_id,
				'trans_comment'=>lang('items_manually_editing_of_quantity'),
				'trans_inventory'=>$cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity')
			);
			$this->Inventory->insert($inv_data);
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_cumulatives = $this->input->post('tax_cumulatives');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
				}
			}
			$this->Item_taxes->save($items_taxes_data, $item_id);
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}

	}
	
	//Ramel Inventory Tracking
	function save_inventory($item_id=-1)
	{	
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$inv_data = array
		(
			'trans_date'=>date('Y-m-d H:i:s'),
			'trans_items'=>$item_id,
			'trans_user'=>$employee_id,
			'trans_comment'=>$this->input->post('trans_comment'),
			'trans_inventory'=>$this->input->post('newquantity')
		);
		$this->Inventory->insert($inv_data);
		
		//Update stock quantity
		$item_data = array(
		'quantity'=>$cur_item_info->quantity + $this->input->post('newquantity')
		);
		if($this->Item->save($item_data,$item_id))
		{			
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_updating').' '.
			$cur_item_info->name,'item_id'=>$item_id));
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('items_error_adding_updating').' '.
			$cur_item_info->name,'item_id'=>-1));
		}

	}//---------------------------------------------------------------------Ramel

	function bulk_update()
	{
		$items_to_update=$this->input->post('item_ids');
		$item_data = array();

		foreach($_POST as $key=>$value)
		{
			if ($key == 'submit')
			{
				continue;
			}
			
			//This field is nullable, so treat it differently
			if ($key == 'supplier_id')
			{
				if ($value!='')
				{
					$item_data["$key"]=$value == '-1' ? null : $value;
				}
			}
			elseif($value!='' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents', 'tax_cumulatives'))))
			{
				$item_data["$key"]=$value;
			}
		}

		//Item data could be empty if tax information is being updated
		if(empty($item_data) || $this->Item->update_multiple($item_data,$items_to_update))
		{
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			$tax_cumulatives = $this->input->post('tax_cumulatives');

			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0' );
				}
			}
			
			if (!empty($items_taxes_data))
			{
				$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);
			}
			
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_bulk_edit')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_updating_multiple')));
		}
	}

	function delete()
	{
		$items_to_delete=$this->input->post('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('items_successful_deleted').' '.
			count($items_to_delete).' '.lang('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_cannot_be_deleted')));
		}
	}
	
	function excel()
	{
		$data = file_get_contents("import_items.csv");
		$name = 'import_items.csv';
		force_download($name, $data);
	}
	
	/* added for excel expert */
	function excel_export() {
		$data = $this->Item->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang("items_item_number"), lang("items_name"), lang("items_category"), lang("items_supplier"), lang("items_cost_price"), lang("items_unit_price"), lang("items_tax_1"), lang("items_tax_percent"), lang("items_tax_2"), lang("items_tax_percent"), lang("common_cumulative"), lang("items_quantity"), lang("items_reorder_level"), lang("items_location"), lang("items_description"), lang("items_change_all_to_allow_alt_desc"), lang("items_is_serialized"));
		$rows[] = $row;
		foreach ($data as $r) {
			$taxdata = $this->Item_taxes->get_info($r->item_id);
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
				$r->item_number,
				$r->name,
				$r->category,
				$r->supplier_id,
				$r->cost_price,
				$r->unit_price,
				$r->taxn,
				$r->taxp,
				$r->taxn1,
				$r->taxp1,
				$r->cumulative,
				$r->quantity,
				$r->reorder_level,
				$r->location,
				$r->description,
				$r->allow_alt_description,
				$r->is_serialized ? 'y' : ''
			);
			$rows[] = $row;
		}
		$title = "danh sach mat hang";
                $excelXml = new Excel_XML();
                $excelXml->setWorksheetTitle($title);
		$excelXml->addArray($rows);
                $excelXml->generateXML($title);
                exit;
//		$content = array_to_csv($rows);
//		force_download('items_export.csv', $content);
//		exit;
	}
	
	function excel_import()
	{
		$this->load->view("items/excel_import", null);
	}

	function do_excel_import()
	{
		$this->db->trans_start();

                var_dump($_FILES['file_path']['tmp_name']);
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = lang('items_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);

				while (($data = fgetcsv($handle)) !== FALSE) 
				{
					$item_data = array(
					'name'			=>	$data[1],
					'description'	=>	$data[14],
					'location'		=>	$data[13],
					'category'		=>	$data[2],
					'cost_price'	=>	$data[4],
					'unit_price'	=>	$data[5],
					'quantity'		=>	$data[11],
					'reorder_level'	=>	$data[12],
					'supplier_id'	=>  $this->Supplier->exists($data[3]) ? $data[3] : $this->Supplier->find_supplier_id($data[3]),
					'allow_alt_description'=> $data[15] != '' ? '1' : '0',
					'is_serialized'=>$data[16] != '' ? '1' : '0'
					);
					$item_number = $data[0];
					
					if ($item_number != "")
					{
						$item_data['item_number'] = $item_number;
					}
					
					if($this->Item->save($item_data)) 
					{
						$items_taxes_data = null;
						//tax 1
						if( is_numeric($data[7]) && $data[6]!='' )
						{
							$items_taxes_data[] = array('name'=>$data[6], 'percent'=>$data[7], 'cumulative' => '0');
						}

						//tax 2
						if( is_numeric($data[9]) && $data[8]!='' )
						{
							$items_taxes_data[] = array('name'=>$data[8], 'percent'=>$data[9], 'cumulative'=> $data[10] != '' ? '1' : '0', );
						}

						// save tax values
						if(count($items_taxes_data) > 0)
						{
							$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
						}
						
							$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
							$emp_info=$this->Employee->get_info($employee_id);
							$comment ='Qty CSV Imported';
							$excel_data = array
								(
								'trans_items'=>$item_data['item_id'],
								'trans_user'=>$employee_id,
								'trans_comment'=>$comment,
								'trans_inventory'=>$data[11]
								);
								$this->db->insert('inventory',$excel_data);
						//------------------------------------------------Ramel
					}
					else//insert or update item failure
					{
						echo json_encode( array('success'=>false,'message'=>lang('items_duplicate_item_ids')));
						return;
					}
				}
			}
			else 
			{
				echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
				return;
			}
		}

		$this->db->trans_complete();
		echo json_encode(array('success'=>true,'message'=>lang('items_import_successful')));
	}

	function cleanup()
	{
		$this->Item->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('items_cleanup_sucessful')));
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