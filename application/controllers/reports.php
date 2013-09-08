<?php
require_once ("secure_area.php");
class Reports extends Secure_area 
{	
	function __construct()
	{
		parent::__construct('reports');
		$this->load->helper('report');		
	}
		
	//Initial report listing screen
	function index()
	{
		$this->load->view("reports/listing",array());	
	}
	
	function _get_common_report_data()
	{
		$data = array();
		$data['report_date_range_simple'] = get_simple_date_ranges();
		$data['months'] = get_months();
		$data['days'] = get_days();
		$data['years'] = get_years();
		$data['selected_month']=date('m');
		$data['selected_day']=date('d');
		$data['selected_year']=date('Y');	
	
		return $data;
	}
	
	//Input for reports that require only a date range and an export to excel. (see routes.php to see that all summary reports route here)
	function date_input_excel_export()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input_excel_export",$data);	
	}
	
	/** added for register log */
	function date_input_excel_export_register_log()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input_excel_register_log.php",$data);	
	}
	
	/** also added for register log */
	
	function detailed_register_log($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Detailed_register_log');
		$model = $this->Detailed_register_log;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		$overallSummaryData = array(
			'total_cash_sales'=>0,
			'total_shortages'=>0,
			'total_overages'=>0,
			'total_difference'=>0
		);
		
		foreach($report_data['summary'] as $row)
		{
			$summary_data[] = array(
				array('data'=>$row['first_name'] . ' ' . $row['last_name'], 'align'=>'left'), 
				array('data'=>date(get_date_format(), strtotime($row['shift_start'])) .' '.date(get_time_format(), strtotime($row['shift_start'])), 'align'=>'left'), 
				array('data'=>date(get_date_format(), strtotime($row['shift_end'])) .' '.date(get_time_format(), strtotime($row['shift_end'])), 'align'=>'left'), 
				array('data'=>to_currency($row['open_amount']), 'align'=>'right'), 
				array('data'=>to_currency($row['close_amount']), 'align'=>'right'), 
				array('data'=>to_currency($row['cash_sales_amount']), 'align'=>'right'),
				array('data'=>to_currency($row['difference']), 'align'=>'right')
			);
			
			$overallSummaryData['total_cash_sales'] += $row['cash_sales_amount'];
			if ($row['difference'] > 0) {
				$overallSummaryData['total_overages'] += $row['difference'];
			} else {
				$overallSummaryData['total_shortages'] += $row['difference'];
			}
			
			$overallSummaryData['total_difference'] += $row['difference'];
		}

		$data = array(
			"title" =>lang('reports_register_log_title'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $summary_data,
			"details_data" => array(),
			"summary_data" => $overallSummaryData,
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular", $data);
	}
	
	//Summary sales report
	function summary_sales($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(
										array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=>'left'), 
										array('data'=>to_currency($row['subtotal']), 'align'=>'right'), 
										array('data'=>to_currency($row['total']), 'align'=>'right'), 
										array('data'=>to_currency($row['tax']), 'align'=> 'right'),
										array('data'=>to_currency($row['profit']), 'align'=>'right'));
		}

		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary categories report
	function summary_categories($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['category'], 'align' => 'left'), array('data'=>to_currency($row['subtotal']), 'align' => 'right'), array('data'=>to_currency($row['total']), 'align' => 'right'), array('data'=>to_currency($row['tax']), 'align' => 'right'),array('data'=>to_currency($row['profit']), 'align' => 'right'));
		}

		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary customers report
	function summary_customers($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['customer'], 'align' => 'left'), array('data'=>to_currency($row['subtotal']), 'align' => 'right'), array('data'=>to_currency($row['total']), 'align' => 'right'), array('data'=>to_currency($row['tax']), 'align' => 'right'),array('data'=>to_currency($row['profit']), 'align' => 'right'));
		}

		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary suppliers report
	function summary_suppliers($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['supplier'], 'align' => 'left'), array('data'=>to_currency($row['subtotal']), 'align' => 'right'), array('data'=>to_currency($row['total']), 'align' => 'right'), array('data'=>to_currency($row['tax']), 'align' => 'right'),array('data'=>to_currency($row['profit']), 'align' => 'right'));
		}

		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary items report
	function summary_items($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['name'], 'align' => 'left'), array('data'=>$row['quantity_purchased'], 'align' => 'left'), array('data'=>to_currency($row['subtotal']), 'align' => 'right'), array('data'=>to_currency($row['total']), 'align' => 'right'), array('data'=>to_currency($row['tax']), 'align' => 'right'),array('data'=>to_currency($row['profit']), 'align' => 'right'));
		}

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);
		
		$this->load->view("reports/tabular",$data);
	}
	
	//Summary item kits report
	function summary_item_kits($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['name'], 'align' => 'left'), array('data'=>$row['quantity_purchased'], 'align' => 'left'), array('data'=>to_currency($row['subtotal']), 'align' => 'right'), array('data'=>to_currency($row['total']), 'align' => 'right'), array('data'=>to_currency($row['tax']), 'align' => 'right'),array('data'=>to_currency($row['profit']), 'align' => 'right'));
		}

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);
		
		$this->load->view("reports/tabular",$data);
	}
	
	//Summary employees report
	function summary_employees($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['employee'], 'align'=>'left'), array('data'=>to_currency($row['subtotal']), 'align'=>'right'), array('data'=>to_currency($row['total']), 'align'=>'right'), array('data'=>to_currency($row['tax']), 'align'=>'right'),array('data'=>to_currency($row['profit']), 'align'=>'right'));
		}

		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary taxes report
	function summary_taxes($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['percent'], 'align'=>'left'), array('data'=>to_currency($row['tax']), 'align'=>'right'));
		}

		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Summary discounts report
	function summary_discounts($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['discount_percent'], 'align'=>'left'),array('data'=>$row['count'], 'align'=>'left'));
		}

		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	function summary_payments($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$tabular_data = array();
		$report_data = $model->getData();
		
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['payment_type'], 'align'=>'left'),array('data'=>to_currency($row['payment_amount']), 'align'=>'right'));
		}

		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);
	}
	
	//Input for reports that require only a date range. (see routes.php to see that all graphical summary reports route here)
	function date_input()
	{
		$data = $this->_get_common_report_data();
		$this->load->view("reports/date_input",$data);	
	}
	
	//Graphical summary sales report
	function graphical_summary_sales($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_sales_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_sales_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_sales');
		$model = $this->Summary_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[strtotime($row['sale_date'])]= $row['total'];
		}

		$data = array(
			"title" => lang('reports_sales_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/line",$data);

	}
	
	//Graphical summary items report
	function graphical_summary_items($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_items_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_items_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_items');
		$model = $this->Summary_items;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_items_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}
	
	//Graphical summary item kits report
	function graphical_summary_item_kits($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_item_kits_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_item_kits_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_item_kits');
		$model = $this->Summary_item_kits;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['name']] = $row['total'];
		}

		$data = array(
			"title" => lang('reports_item_kits_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}
	
	//Graphical summary customers report
	function graphical_summary_categories($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_categories_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_categories_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_categories');
		$model = $this->Summary_categories;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['category']] = $row['total'];
		}
		
		$data = array(
			"title" => lang('reports_categories_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}
	
	function graphical_summary_suppliers($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_suppliers_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_suppliers_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_suppliers');
		$model = $this->Summary_suppliers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['supplier']] = $row['total'];
		}
		
		$data = array(
			"title" => lang('reports_suppliers_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}
	
	function graphical_summary_employees($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_employees_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_employees_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_employees');
		$model = $this->Summary_employees;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['employee']] = $row['total'];
		}
		
		$data = array(
			"title" => lang('reports_employees_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	
	function graphical_summary_taxes($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_taxes_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_taxes_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_taxes');
		$model = $this->Summary_taxes;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['percent']] = $row['tax'];
		}
		
		$data = array(
			"title" => lang('reports_taxes_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	
	//Graphical summary customers report
	function graphical_summary_customers($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_customers_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_customers_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_customers');
		$model = $this->Summary_customers;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['customer']] = $row['total'];
		}
		
		$data = array(
			"title" => lang('reports_customers_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/pie",$data);
	}
	
	//Graphical summary discounts report
	function graphical_summary_discounts($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_discounts_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_discounts_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_discounts');
		$model = $this->Summary_discounts;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['discount_percent']] = $row['count'];
		}
		
		$data = array(
			"title" => lang('reports_discounts_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	
	function graphical_summary_payments($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"graph_file" => site_url("reports/graphical_summary_payments_graph/$start_date/$end_date/$sale_type"),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"summary_data" => $model->getSummaryData()
		);

		$this->load->view("reports/graphical",$data);
	}
	
	//The actual graph data
	function graphical_summary_payments_graph($start_date, $end_date, $sale_type)
	{
		$this->load->model('reports/Summary_payments');
		$model = $this->Summary_payments;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		$report_data = $model->getData();
		
		$graph_data = array();
		foreach($report_data as $row)
		{
			$graph_data[$row['payment_type']] = $row['payment_amount'];
		}
		
		$data = array(
			"title" => lang('reports_payments_summary_report'),
			"data" => $graph_data
		);

		$this->load->view("reports/graphs/bar",$data);
	}
	function specific_customer_input()
	{
		$data = $this->_get_common_report_data();
		$data['specific_input_name'] = lang('reports_customer');
		
		$customers = array();
		foreach($this->Customer->get_all()->result() as $customer)
		{
			$customers[$customer->person_id] = $customer->first_name .' '.$customer->last_name;
		}
		$data['specific_input_data'] = $customers;
		$this->load->view("reports/specific_input",$data);	
	}

	function specific_customer($start_date, $end_date, $customer_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_customer');
		$model = $this->Specific_customer;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 'sale_type' => $sale_type));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=> 'left'), array('data'=>$row['items_purchased'], 'align'=> 'left'), array('data'=>$row['employee_name'], 'align'=> 'left'), array('data'=>to_currency($row['subtotal']), 'align'=> 'right'), array('data'=>to_currency($row['total']), 'align'=> 'right'), array('data'=>to_currency($row['tax']), 'align'=> 'right'),array('data'=>to_currency($row['profit']), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['serialnumber'], 'align'=> 'left'), array('data'=>$drow['description'], 'align'=> 'left'), array('data'=>$drow['quantity_purchased'], 'align'=> 'left'), array('data'=>to_currency($drow['subtotal']), 'align'=> 'right'), array('data'=>to_currency($drow['total']), 'align'=> 'right'), array('data'=>to_currency($drow['tax']), 'align'=> 'right'),array('data'=>to_currency($drow['profit']), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}

		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.lang('reports_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function specific_employee_input()
	{
		$data = $this->_get_common_report_data();
		$data['specific_input_name'] = lang('reports_employee');
		
		$employees = array();
		foreach($this->Employee->get_all()->result() as $employee)
		{
			$employees[$employee->person_id] = $employee->first_name .' '.$employee->last_name;
		}
		$data['specific_input_data'] = $employees;
		$this->load->view("reports/specific_input",$data);	
	}

	function specific_employee($start_date, $end_date, $employee_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_employee');
		$model = $this->Specific_employee;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id, 'sale_type' => $sale_type));
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=> 'left'), array('data'=>$row['items_purchased'], 'align'=> 'left'), array('data'=>$row['customer_name'], 'align'=> 'left'), array('data'=>to_currency($row['subtotal']), 'align'=> 'right'), array('data'=>to_currency($row['total']), 'align'=> 'right'), array('data'=>to_currency($row['tax']), 'align'=> 'right'),array('data'=>to_currency($row['profit']), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['serialnumber'], 'align'=> 'left'), array('data'=>$drow['description'], 'align'=> 'left'), array('data'=>$drow['quantity_purchased'], 'align'=> 'left'), array('data'=>to_currency($drow['subtotal']), 'align'=> 'right'), array('data'=>to_currency($drow['total']), 'align'=> 'right'), array('data'=>to_currency($drow['tax']), 'align'=> 'right'),array('data'=>to_currency($drow['profit']), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}

		$employee_info = $this->Employee->get_info($employee_id);
		$data = array(
			"title" => $employee_info->first_name .' '. $employee_info->last_name.' '.lang('reports_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function detailed_sales($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Detailed_sales');
		$model = $this->Detailed_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(
											array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=>'left'), 
											array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=>'left'), 
											array('data'=>$row['items_purchased'], 'align'=>'left'), 
											array('data'=>$row['employee_name'], 'align'=>'left'), 
											array('data'=>$row['customer_name'], 'align'=>'left'), 
											array('data'=>to_currency($row['subtotal']), 'align'=>'right'), 
											array('data'=>to_currency($row['total']), 'align'=>'right'), 
											array('data'=>to_currency($row['tax']), 'align'=>'right'),
											array('data'=>to_currency($row['profit']), 'align'=>'right'), 
											array('data'=>$row['payment_type'], 'align'=>'right'), 
											array('data'=>$row['comment'], 'align'=>'right'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align'=>'left'), array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left'), array('data'=>$drow['category'], 'align'=>'left'), array('data'=>$drow['serialnumber'], 'align'=>'left'), array('data'=>$drow['description'], 'align'=>'left'), array('data'=>$drow['quantity_purchased'], 'align'=>'left'), array('data'=>to_currency($drow['subtotal']), 'align'=>'right'), array('data'=>to_currency($drow['total']), 'align'=>'right'), array('data'=>to_currency($drow['tax']), 'align'=>'right'),array('data'=>to_currency($drow['profit']), 'align'=>'right'), array('data'=>$drow['discount_percent'].'%', 'align'=>'left'));
			}
		}

		$data = array(
			"title" =>lang('reports_detailed_sales_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
    function specific_supplier_input()
	{
		$data = $this->_get_common_report_data();
		$data['specific_input_name'] = lang('reports_supplier');

		$suppliers = array();
		foreach($this->Supplier->get_all()->result() as $supplier)
		{
			$suppliers[$supplier->person_id] = $supplier->first_name .' '.$supplier->last_name;
		}
		$data['specific_input_data'] = $suppliers;
		$this->load->view("reports/specific_input",$data);
	}

	function specific_supplier($start_date, $end_date, $supplier_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_supplier');
		$model = $this->Specific_supplier;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'supplier_id' =>$supplier_id, 'sale_type' => $sale_type));
		
		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'supplier_id' =>$supplier_id, 'sale_type' => $sale_type));
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=> 'left'), array('data'=>$row['items_purchased'], 'align'=> 'left'), array('data'=>$row['customer_name'], 'align'=> 'left'), array('data'=>to_currency($row['subtotal']), 'align'=> 'right'), array('data'=>to_currency($row['total']), 'align'=> 'right'), array('data'=>to_currency($row['tax']), 'align'=> 'right'),array('data'=>to_currency($row['profit']), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));
				
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['serialnumber'], 'align'=> 'left'), array('data'=>$drow['description'], 'align'=> 'left'), array('data'=>$drow['quantity_purchased'], 'align'=> 'left'), array('data'=>to_currency($drow['subtotal']), 'align'=> 'right'), array('data'=>to_currency($drow['total']), 'align'=> 'right'), array('data'=>to_currency($drow['tax']), 'align'=> 'right'),array('data'=>to_currency($drow['profit']), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}
		
		$supplier_info = $this->Supplier->get_info($supplier_id);
		$data = array(
					"title" => $supplier_info->first_name .' '. $supplier_info->last_name.' '.lang('reports_report'),
					"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
					"headers" => $model->getDataColumns(),
					"summary_data" => $summary_data,
					"details_data" => $details_data,
					"overall_summary_data" => $model->getSummaryData(),
					"export_excel" => $export_excel
		);
		
		$this->load->view("reports/tabular_details",$data);
	}
	

	
	function deleted_sales($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Deleted_sales');
		$model = $this->Deleted_sales;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=>'left'), array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=>'left'), array('data'=>$row['items_purchased'], 'align'=>'left'), array('data'=>$row['employee_name'], 'align'=>'left'), array('data'=>$row['customer_name'], 'align'=>'left'), array('data'=>to_currency($row['subtotal']), 'align'=>'right'), array('data'=>to_currency($row['total']), 'align'=>'right'), array('data'=>to_currency($row['tax']), 'align'=>'right'),array('data'=>to_currency($row['profit']), 'align'=>'right'), array('data'=>$row['payment_type'], 'align'=>'left'), array('data'=>$row['comment'], 'align'=>'left'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=>'left'), array('data'=>$drow['category'], 'align'=>'left'), array('data'=>$drow['serialnumber'], 'align'=>'left'), array('data'=>$drow['description'], 'align'=>'left'), array('data'=>$drow['quantity_purchased'], 'align'=>'left'), array('data'=>to_currency($drow['subtotal']), 'align'=>'right'), array('data'=>to_currency($drow['total']), 'align'=>'right'), array('data'=>to_currency($drow['tax']), 'align'=>'right'),array('data'=>to_currency($drow['profit']), 'align'=>'right'), array('data'=>$drow['discount_percent'].'%', 'align'=>'left'));
			}
		}

		$data = array(
			"title" =>lang('reports_deleted_sales_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
	function detailed_receivings($start_date, $end_date, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Detailed_receivings');
		$model = $this->Detailed_receivings;
		$model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));

		$this->Receiving->create_receivings_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('receivings/edit/'.$row['receiving_id'], 'RECV '.$row['receiving_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['receiving_date'])), 'align'=> 'left'), array('data'=>$row['items_purchased'], 'align'=> 'left'), array('data'=>$row['employee_name'], 'align'=> 'left'), array('data'=>$row['supplier_name'], 'align'=> 'left'), array('data'=>to_currency($row['total']), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>$drow['name'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['quantity_purchased'], 'align'=> 'left'), array('data'=>to_currency($drow['total']), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}

		$data = array(
			"title" =>lang('reports_detailed_receivings_report'),
			"subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
			
	function excel_export()
	{
		$this->load->view("reports/excel_export",array());		
	}
	
	function inventory_low($export_excel=0)
	{
		$this->load->model('reports/Inventory_low');
		$model = $this->Inventory_low;
		$model->setParams(array());
		$tabular_data = array();
		$report_data = $model->getData(array());
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['name'], 'align'=> 'left'), array('data'=>$row['item_number'], 'align'=> 'left'), array('data'=>$row['description'], 'align'=> 'left'),array('data'=>to_currency($row['cost_price']), 'align'=> 'right'),array('data'=>to_currency($row['unit_price']), 'align'=> 'right'), array('data'=>$row['quantity'], 'align'=> 'left'), array('data'=>$row['reorder_level'], 'align'=> 'left'));
		}

		$data = array(
			"title" => lang('reports_low_inventory_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array()),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);	
	}
	
	function inventory_summary($export_excel=0)
	{
		$this->load->model('reports/Inventory_summary');
		$model = $this->Inventory_summary;
		$tabular_data = array();
		$report_data = $model->getData(array());
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['name'], 'align'=> 'left'), array('data'=>$row['item_number'], 'align'=> 'left'), array('data'=>$row['description'], 'align'=> 'left'), array('data'=>to_currency($row['cost_price']), 'align'=> 'right'),array('data'=>to_currency($row['unit_price']), 'align' => 'right'), array('data'=>$row['quantity'], 'align'=> 'left'), array('data'=>$row['reorder_level'], 'align'=> 'left'));
		}

		$data = array(
			"title" => lang('reports_inventory_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array()),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);	
	}
	
	function summary_giftcards($export_excel = 0)
	{
		$this->load->model('reports/Summary_giftcards');
		$model = $this->Summary_giftcards;
		$tabular_data = array();
		$report_data = $model->getData(array());
		foreach($report_data as $row)
		{
			$tabular_data[] = array(array('data'=>$row['giftcard_number'], 'align'=> 'left'),array('data'=>to_currency($row['value']), 'align'=> 'left'), array('data'=>$row['customer_name'], 'align'=> 'left'));
		}

		$data = array(
			"title" => lang('reports_giftcard_summary_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"data" => $tabular_data,
			"summary_data" => $model->getSummaryData(array()),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular",$data);			
	}
	
	function detailed_giftcards_input()
	{
		$data['specific_input_name'] = lang('reports_customer');
		
		$customers = array();
		foreach($this->Customer->get_all()->result() as $customer)
		{
			$customers[$customer->person_id] = $customer->first_name .' '.$customer->last_name;
		}
		$data['specific_input_data'] = $customers;
		$this->load->view("reports/detailed_giftcards_input",$data);	
	}
	
	function detailed_giftcards($customer_id, $export_excel = 0)
	{
		$this->load->model('reports/Detailed_giftcards');
		$model = $this->Detailed_giftcards;
		$model->setParams(array('customer_id' =>$customer_id));

		$this->Sale->create_sales_items_temp_table(array('customer_id' =>$customer_id));
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData();
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(array('data'=>anchor('sales/edit/'.$row['sale_id'], lang('common_edit').' '.$row['sale_id'], array('target' => '_blank')), 'align'=> 'left'), array('data'=>date(get_date_format(), strtotime($row['sale_date'])), 'align'=> 'left'), array('data'=>$row['items_purchased'], 'align'=> 'left'), array('data'=>$row['employee_name'], 'align'=> 'left'), array('data'=>to_currency($row['subtotal']), 'align'=> 'right'), array('data'=>to_currency($row['total']), 'align'=> 'right'), array('data'=>to_currency($row['tax']), 'align'=> 'right'),array('data'=>to_currency($row['profit']), 'align'=> 'right'), array('data'=>$row['payment_type'], 'align'=> 'left'), array('data'=>$row['comment'], 'align'=> 'left'));
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array(array('data'=>isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align'=> 'left'), array('data'=>$drow['category'], 'align'=> 'left'), array('data'=>$drow['serialnumber'], 'align'=> 'left'), array('data'=>$drow['description'], 'align'=> 'left'), array('data'=>$drow['quantity_purchased'], 'align'=> 'left'), array('data'=>to_currency($drow['subtotal']), 'align'=> 'right'), array('data'=>to_currency($drow['total']), 'align'=> 'right'), array('data'=>to_currency($drow['tax']), 'align'=> 'right'),array('data'=>to_currency($drow['profit']), 'align'=> 'right'), array('data'=>$drow['discount_percent'].'%', 'align'=> 'left'));
			}
		}

		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.lang('giftcards_giftcard'). ' '.lang('reports_report'),
			"subtitle" => '',
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
	
}
?>