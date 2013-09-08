<?php
class Notes extends CI_Controller
{
    function  __construct() {
        parent::__construct();
    }
    function index(){
        $this->load->view('notes/notes');
    }
    function do_excel_import() {
        $this->db->trans_start();

        $msg = 'do_excel_import';
        $failCodes = array();
        echo '123';
        var_dump($_FILES['file_path']['tmp_name']);
//        if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK) {
//            $msg = lang('items_excel_import_failed');
//            echo json_encode( array('success'=>false,'message'=>$msg) );
//            return;
//        }
//        else {
//            $excelXml = new Excel_XML();
//            if($datas = $excelXml->readXls($_FILES['file_path']['tmp_name'])) {
//                foreach ($datas as $data) {
//                      echo "aas";
////                    $person_data = array(
////                        'first_name'=>$data[0],
////                        'last_name'=>$data[1],
////                        'email'=>$data[2],
////                        'phone_number'=>$data[3],
////                        'address_1'=>$data[4],
////                        'address_2'=>$data[5],
////                        'city'=>$data[6],
////                        'state'=>$data[7],
////                        'zip'=>$data[8],
////                        'country'=>$data[9],
////                        'comments'=>$data[10]
////                    );
////
////                    $customer_data=array(
////                        'account_number'=>$data[11]=='' ? null:$data[11],
////                        'taxable'=>$data[12]=='' ? 0:1,
////                        'company_name' => $data[13],
////                    );
////
////                    if(!$this->Customer->save($person_data,$customer_data)) {
////                        echo json_encode( array('success'=>false,'message'=>lang('customers_duplicate_account_id')));
////                        return;
////                    }
//                }
//            }else {
//                echo json_encode( array('success'=>false,'message'=>lang('common_upload_file_not_supported_format')));
//                return;
//            }
//        }
//        $this->db->trans_complete();
//        echo json_encode(array('success'=>true,'message'=>lang('customers_import_successfull')));
    }
}
?>
