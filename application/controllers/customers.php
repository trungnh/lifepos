<?php
require_once(APPPATH.'libraries/php-excel.class.php');
require_once ("person_controller.php");
class Customers extends Person_controller
{
	function __construct()
	{
		parent::__construct('customers');
	}
	
	function index()
	{
		$config['base_url'] = site_url('customers/index');
		$config['total_rows'] = $this->Customer->count_all();
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_people_manage_table($this->Customer->get_all($config['per_page'], $this->uri->segment(3)),$this);
		$this->load->view('people/manage',$data);
	}
	
	/*
	Returns customer table data rows. This will be called with AJAX.
	*/
	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_people_manage_table_data_rows($this->Customer->search($search,$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20),$this);
		echo $data_rows;
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Customer->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}
	
	/*
	Loads the customer edit form
	*/
	function view($customer_id=-1)
	{
		$data['person_info']=$this->Customer->get_info($customer_id);
		$this->load->view("customers/form",$data);
	}
	
	/*
	Inserts/updates a customer
	*/
	function save($customer_id=-1)
	{
		$person_data = array(
		'first_name'=>$this->input->post('first_name'),
		'last_name'=>$this->input->post('last_name'),
		'email'=>$this->input->post('email'),
		'phone_number'=>$this->input->post('phone_number'),
		'address_1'=>$this->input->post('address_1'),
		'address_2'=>$this->input->post('address_2'),
		'city'=>$this->input->post('city'),
		'state'=>$this->input->post('state'),
		'zip'=>$this->input->post('zip'),
		'country'=>$this->input->post('country'),
		'comments'=>$this->input->post('comments')
		);
		$customer_data=array(
		'company_name' => $this->input->post('company_name'),
		'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
		'taxable'=>$this->input->post('taxable')=='' ? 0:1,
		);
		if($this->Customer->save($person_data,$customer_data,$customer_id))
		{
			if ($this->config->item('mailchimp_api_key'))
			{
				$this->Person->update_mailchimp_subscriptions($this->input->post('email'), $this->input->post('first_name'), $this->input->post('last_name'), $this->input->post('mailing_lists'));
			}
			//New customer
			if($customer_id==-1)
			{
				echo json_encode(array('success'=>true,'message'=>lang('customers_successful_adding').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$customer_data['person_id']));
			}
			else //previous customer
			{
				echo json_encode(array('success'=>true,'message'=>lang('customers_successful_updating').' '.
				$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>$customer_id));
			}
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>lang('customers_error_adding_updating').' '.
			$person_data['first_name'].' '.$person_data['last_name'],'person_id'=>-1));
		}
	}
	
	/*
	This deletes customers from the customers table
	*/
	function delete()
	{
		$customers_to_delete=$this->input->post('ids');
		
		if($this->Customer->delete_list($customers_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('customers_successful_deleted').' '.
			count($customers_to_delete).' '.lang('customers_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('customers_cannot_be_deleted')));
		}
	}
	
	function excel()
	{
		$data = file_get_contents("import_customers.csv");
		$name = 'import_customers.csv';
		force_download($name, $data);
	}
	
	function excel_import()
	{
		$this->load->view("customers/excel_import", null);
	}
	
	/* added for excel expert */
	function excel_export() {
		$data = $this->Customer->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('customers_first_name'), lang('customers_last_name'), lang('common_email'), lang('common_phone_number'), lang('common_address_1'), lang('common_address_2'), lang('common_city'), lang('common_state'), lang('common_zip'), lang('common_country'), lang('common_comments'), lang('customers_account_number'), lang('customers_taxable'), lang('config_company'));
                $rows[] = $row;
		foreach ($data as $r) {
			$row = array(
				$r->first_name,
				$r->last_name,
				$r->email,
				$r->phone_number,
				$r->address_1,
				$r->address_2,
				$r->city,
				$r->state,
				$r->zip,
				$r->country,
				$r->comments,
				$r->account_number,
				$r->taxable ? 'y' : '',
				$r->company_name
			);
			$rows[] = $row;
		}
                $title = "danh sach khach hang";
                $excelXml = new Excel_XML();
                $excelXml->setWorksheetTitle($title);
		$excelXml->addArray($rows);
                $excelXml->generateXML($title);
                exit;
//		$content = array_to_csv($rows);
//		force_download('customers_export' . '.csv', $content);
//		exit;
	}

	function do_excel_import()
	{
		$this->db->trans_start();
		
		$msg = 'do_excel_import';
		$failCodes = array();
		if($_FILES['file_path']['error']!= UPLOAD_ERR_OK){
                    $msg= lang('items_excel_import_failed');
                    echo json_encode(array('success'=>false,'message'=>$msg));
                    return ;
                }
                else {
                    $excelXml = new Excel_XML();                    
                    if($datas = $excelXml->readXLs($_FILES['file_path']['tmp_name'])){
                        foreach ($datas as $data){
                            $person_data= array(
                                'first_name'=>$data[0],
                                'last_name'=>$data[1],
                                'email'=>$data[2],
                                'phone_number'=>$data[3],
                                'address_1'=>$data[4],
                                'address_2'=>$data[5],
                                'city'=>$data[6],
                                'state'=>$data[7],
                                'zip'=>$data[8],
                                'country'=>$data[9],
                                'comments'=>$data[10]
                            );

                            $customer_data=array(
                                'account_number'=>$data[11]=='' ? null:$data[11],
                                'taxable'=>$data[12]=='' ? 0:1,
                                'company_name' => $data[13],
                            );
                            
                            if(!$this->Customer->save($person_data,$customer_data)) {
                                echo json_encode( array('success'=>false,'message'=>lang('customers_duplicate_account_id')));
                                return;
                            }
                        }
                    }
                    else{
                        echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
                        return;
                    }
                }
                $this->db->trans_complete();
                echo json_encode(array('success'=>true,'message'=>lang('customers_import_successfull')));
	}
	
	function cleanup()
	{
		$this->Customer->cleanup();
		echo json_encode(array('success'=>true,'message'=>lang('customers_cleanup_sucessful')));
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