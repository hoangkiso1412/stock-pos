<?php
/**
 * Geo POS -  Accounting,  Invoicing  and CRM Application
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@ultimatekode.com
 *  Website: https://www.ultimatekode.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://codecanyon.net/licenses/standard/
 * ***********************************************************************
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model', 'purchase');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }

        if (!$this->aauth->premission(2)) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
        $this->li_a = 'stock';

    }

    //create invoice
    public function create()
    {
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist($this->config->item('tax'));
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $data['currency'] = $this->purchase->currencies();
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['lastinvoice'] = $this->purchase->lastpurchase();
        $data['terms'] = $this->purchase->billingterms();
        $head['title'] = "New Purchase";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['taxdetails'] = $this->common->taxdetail();
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/newinvoice', $data);
        $this->load->view('fixed/footer');
    }

    //edit invoice
    public function edit()
    {

        $tid = $this->input->get('id');
        $data['id'] = $tid;
        $data['title'] = "Purchase Order $tid";
        $this->load->model('customers_model', 'customers');
        $data['customergrouplist'] = $this->customers->group_list();
        $data['terms'] = $this->purchase->billingterms();
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products_stock($tid);
        $head['title'] = "Edit Invoice #$tid";
        $head['usernm'] = $this->aauth->get_user()->username;
        $data['warehouse'] = $this->purchase->warehouses();
        $data['currency'] = $this->purchase->currencies();
        $this->load->model('plugins_model', 'plugins');
        $data['exchange'] = $this->plugins->universal_api(5);
        $this->load->library("Common");
        $data['taxlist'] = $this->common->taxlist_edit($data['invoice']['taxstatus']);
        
        $this->db->select('title');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $data['invoice']['warehouse_id']);
        $query = $this->db->get();
        $data['warehouse_name'] = $query->row_array()['title'];
        
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/edit', $data);
        $this->load->view('fixed/footer');

    }

    //invoices list
    public function index()
    {
        $head['title'] = "Manage Purchase Orders";
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        $this->load->view('purchase/invoices');
        $this->load->view('fixed/footer');
    }

    public function test(){
        $now = new DateTime('NOW');
        print_r($now->getTimestamp());
    }
    //action
    public function action()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('invocieno');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $warehouse = $this->input->post('s_warehouse');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        $ship_taxtype = $this->input->post('ship_taxtype');
        if ($ship_taxtype == 'incl') @$shipping = $shipping - $shipping_tax;
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $i = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit;
        }
        $this->db->trans_start();
        //products
        $transok = true;
        //Invoice Data
        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $data = array('tid' => $invocieno, 'invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'eid' => $this->aauth->get_user()->id, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'loc' => $this->aauth->get_user()->loc, 'multi' => $currency, 'warehouse_id'=>$warehouse);


        if ($this->db->insert('geopos_purchase', $data)) {
            $invocieno = $this->db->insert_id();

            $pid = $this->input->post('pid');
            $productlist = array();
            $prodindex = 0;
            $itc = 0;
            $flag = false;
            $product_id = $this->input->post('pid');
            $product_name1 = $this->input->post('product_name', true);
            $product_unique_id = $this->input->post('product_unique_id', true);
            $product_qty = $this->input->post('product_qty');
            $product_price = $this->input->post('product_price');
            $product_tax = $this->input->post('product_tax');
            $product_discount = $this->input->post('product_discount');
            $product_subtotal = $this->input->post('product_subtotal');
            $ptotal_tax = $this->input->post('taxa');
            $ptotal_disc = $this->input->post('disca');
            $product_des = $this->input->post('product_description', true);
            $product_unit = $this->input->post('unit');
            $product_hsn = $this->input->post('hsn');

            $this->db->select('title');
            $this->db->from('geopos_warehouse');
            $this->db->where('id', $warehouse);
            $query = $this->db->get();
            $warehouse_name = $query->row_array()['title'];
            foreach ($pid as $key => $value) {
                $total_discount += numberClean(@$ptotal_disc[$key]);
                $total_tax += numberClean($ptotal_tax[$key]);


                $data = array(
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $product_hsn[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'product_unique_id' => $product_unique_id[$key],
                    'warehouse_id'=>$warehouse
                );
                
                $this->aauth->applog("[Product Purchase] $product_name1[$key] Qty $product_qty[$key] to $warehouse_name", $this->aauth->get_user()->username);

                $flag = true;
                $productlist[$prodindex] = $data;
                $i++;
                $prodindex++;
                $amt = numberClean($product_qty[$key]);
                $itc += $amt;

//                if ($product_id[$key] > 0) {
//                    if ($this->input->post('update_stock') == 'yes') {
//
//                        $this->db->set('qty', "qty+$amt", FALSE);
//                        $this->db->where('pid', $product_id[$key]);
//                        $this->db->update('geopos_products');
//                    }
//                    
//                }

            }
            if ($prodindex > 0) {
                $this->db->insert_batch('geopos_purchase_items', $productlist);
                $this->db->set(array('discount' => rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc), 'tax' => rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc), 'items' => $itc));
                $this->db->where('id', $invocieno);
                $this->db->update('geopos_purchase');
                $purchase_product = $this->purchase->purchase_products_stock($invocieno);
                $data_insert = array();
                $data_update = array();
                $pro_update = 0;
                $pro_insert = 0;
                foreach($purchase_product as $p){
                    if($p['stock_id']==0){
                        $d_insert = array(
                            'product_id' => $p['pid'],
                            'warehouse_id' => $p['warehouse_id'],
                            'unique_id' => $p['product_unique_id'],
                            'qty' => numberClean($p['qty']),
                            'purchase_detail_id' => $p['id'],
                            'product_desc' => $p['product_des'],
                            'sale_status' => 'in-stock',
                            'init_stock' => $p['warehouse_id']
                        );
                        $data_insert[$pro_insert] = $d_insert;
                        $pro_insert++;
                    }
                    else{
                        $d_update = array(
                            'product_id' => $p['pid'],
                            'warehouse_id' => $p['warehouse_id'],
                            'unique_id' => $p['product_unique_id'],
                            'qty' => numberClean($p['qty']+$p['stock_qty']),
                            'purchase_detail_id' => $p['id'],
                            'product_desc' => $p['product_des'],
                            'sale_status' => 'in-stock',
                            'init_stock' => $p['warehouse_id'],
                            'id' => $p['stock_id']
                        );
                        $data_update[$pro_update] = $d_update;
                        $pro_update++;
                    }
                }
                if($pro_insert>0){
                    $this->db->insert_batch('tb_stock', $data_insert);
                }
                if($pro_update>0){
                    $this->db->update_batch('tb_stock', $data_update,'id');
                }
                echo json_encode(array('status' => 'Success', 'message' => $this->lang->line('Purchase order success') . "<a href='view?id=$invocieno' class='btn btn-info btn-lg'><span class='fa fa-eye' aria-hidden='true'></span>" . $this->lang->line('View') . " </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "Please choose product from product list. Go to Item manager section if you have not added the products."));
                $transok = false;
            }
        } else {
            echo json_encode(array('status' => 'Error', 'message' => $this->lang->line('ERROR')));
            $transok = false;
        }


        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }


    }


    public function ajax_list()
    {

        $list = $this->purchase->get_datatables();
        $data = array();

        $no = $this->input->post('start');

        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $invoices->tid;
            $row[] = $invoices->name;
            $row[] = dateformat($invoices->invoicedate);
            $row[] = amountExchange($invoices->total, 0, $this->aauth->get_user()->loc);
            $row[] = '<span class="st-' . $invoices->status . '">' . $this->lang->line(ucwords($invoices->status)) . '</span>';
            $row[] = '<a href="' . base_url("purchase/view?id=$invoices->id") . '" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> ' . $this->lang->line('View') . '</a> &nbsp; <a href="' . base_url("purchase/printinvoice?id=$invoices->id") . '&d=1" class="btn btn-info btn-xs"  title="Download"><span class="fa fa-download"></span></a>&nbsp; &nbsp;<a href="#" data-object-id="' . $invoices->id . '" class="btn btn-danger btn-xs delete-object hidden"><span class="fa fa-trash"></span></a>';

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->purchase->count_all(),
            "recordsFiltered" => $this->purchase->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);

    }

    public function view()
    {
        $this->load->model('accounts_model');
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $tid = intval($this->input->get('id'));
        $data['id'] = $tid;
        $head['title'] = "Purchase $tid";
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products_stock($tid);
        $data['activity'] = $this->purchase->purchase_transactions($tid);
        $data['attach'] = $this->purchase->attach($tid);
        $data['employee'] = $this->purchase->employee($data['invoice']['eid']);
        $head['usernm'] = $this->aauth->get_user()->username;
        $this->load->view('fixed/header', $head);
        if ($data['invoice']['tid']) $this->load->view('purchase/view', $data);
        $this->load->view('fixed/footer');

    }


    public function printinvoice()
    {

        $tid = $this->input->get('id');

        $data['id'] = $tid;
        $data['title'] = "Purchase $tid";
        $data['invoice'] = $this->purchase->purchase_details($tid);
        $data['products'] = $this->purchase->purchase_products($tid);
        $data['employee'] = $this->purchase->employee($data['invoice']['eid']);
        $data['invoice']['multi'] = 0;

        $data['general'] = array('title' => $this->lang->line('Purchase Order'), 'person' => $this->lang->line('Supplier'), 'prefix' => prefix(2), 't_type' => 0);


        ini_set('memory_limit', '64M');

        if ($data['invoice']['taxstatus'] == 'cgst' || $data['invoice']['taxstatus'] == 'igst') {
            $html = $this->load->view('print_files/invoice-a4-gst_v' . INVV, $data, true);
        } else {
            $html = $this->load->view('print_files/invoice-a4_v' . INVV, $data, true);
        }

        //PDF Rendering
        $this->load->library('pdf');
        if (INVV == 1) {
            $header = $this->load->view('print_files/invoice-header_v' . INVV, $data, true);
            $pdf = $this->pdf->load_split(array('margin_top' => 40));
            $pdf->SetHTMLHeader($header);
        }
        if (INVV == 2) {
            $pdf = $this->pdf->load_split(array('margin_top' => 5));
        }
        $pdf->SetHTMLFooter('<div style="text-align: right;font-family: serif; font-size: 8pt; color: #5C5C5C; font-style: italic;margin-top:-6pt;">{PAGENO}/{nbpg} #' . $data['invoice']['tid'] . '</div>');

        $pdf->WriteHTML($html);

        if ($this->input->get('d')) {

            $pdf->Output('Purchase_#' . $data['invoice']['tid'] . '.pdf', 'D');
        } else {
            $pdf->Output('Purchase_#' . $data['invoice']['tid'] . '.pdf', 'I');
        }


    }

    public function delete_i()
    {
        $id = $this->input->post('deleteid');

        if ($this->purchase->purchase_delete($id)) {
            echo json_encode(array('status' => 'Success', 'message' =>
                "Purchase Order #$id has been deleted successfully!"));

        } else {

            echo json_encode(array('status' => 'Error', 'message' =>
                "There is an error! Purchase has not deleted."));
        }

    }

    public function editaction()
    {
        $currency = $this->input->post('mcurrency');
        $customer_id = $this->input->post('customer_id');
        $invocieno = $this->input->post('iid');
        $warehouse = $this->input->post('s_warehouse');
        $invoicedate = $this->input->post('invoicedate');
        $invocieduedate = $this->input->post('invocieduedate');
        $notes = $this->input->post('notes', true);
        $tax = $this->input->post('tax_handle');
        $refer = $this->input->post('refer', true);
        $total = rev_amountExchange_s($this->input->post('total'), $currency, $this->aauth->get_user()->loc);
        $total_tax = 0;
        $total_discount = 0;
        $discountFormat = $this->input->post('discountFormat');
        $pterms = $this->input->post('pterms');
        $ship_taxtype = $this->input->post('ship_taxtype');
        $subtotal = rev_amountExchange_s($this->input->post('subtotal'), $currency, $this->aauth->get_user()->loc);
        $shipping = rev_amountExchange_s($this->input->post('shipping'), $currency, $this->aauth->get_user()->loc);
        $shipping_tax = rev_amountExchange_s($this->input->post('ship_tax'), $currency, $this->aauth->get_user()->loc);
        if ($ship_taxtype == 'incl') $shipping = $shipping - $shipping_tax;

        $itc = 0;
        if ($discountFormat == '0') {
            $discstatus = 0;
        } else {
            $discstatus = 1;
        }

        if ($customer_id == 0) {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add a new supplier or search from a previous added!"));
            exit();
        }

        $this->db->trans_start();
        $flag = false;
        $transok = true;


        //Product Data
        $pid = $this->input->post('pid');
        $productlist = array();

        $prodindex = 0;

        //$this->db->delete('geopos_purchase_items', array('tid' => $invocieno));
        $product_id = $this->input->post('pid');
        $product_name1 = $this->input->post('product_name', true);
        $product_unique_id = $this->input->post('product_unique_id', true);
        $product_qty = $this->input->post('product_qty');
        $old_product_qty = $this->input->post('old_product_qty');
        if ($old_product_qty == '') $old_product_qty = 0;
        $product_price = $this->input->post('product_price');
        $product_tax = $this->input->post('product_tax');
        $product_discount = $this->input->post('product_discount');
        $product_subtotal = $this->input->post('product_subtotal');
        $ptotal_tax = $this->input->post('taxa');
        $ptotal_disc = $this->input->post('disca');
        $product_des = $this->input->post('product_description', true);
        $product_unit = $this->input->post('unit');
        $product_hsn = $this->input->post('hsn');
        $restockpurchase = $this->input->post('restockpurchase');
        $puid = $this->input->post('puid');

        $delete_purchase = array();
        
        $datainsert = array();
        $index_insert = 0;
        $dataupdate = array();
        $index_update = 0;
        
        foreach ($pid as $key => $value) {
            $total_discount += numberClean(@$ptotal_disc[$key]);
            $total_tax += numberClean($ptotal_tax[$key]);
            if((int)$puid[$key]==0){
                $data_i = array(
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $product_hsn[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'product_unique_id' => $product_unique_id[$key],
                    'warehouse_id'=>$warehouse
                );
                $datainsert[$index_insert] = $data_i;
                $index_insert++;
            }
            else{
                $data_u = array(
                    'id' => $puid[$key],
                    'tid' => $invocieno,
                    'pid' => $product_id[$key],
                    'product' => $product_name1[$key],
                    'code' => $product_hsn[$key],
                    'qty' => numberClean($product_qty[$key]),
                    'price' => rev_amountExchange_s($product_price[$key], $currency, $this->aauth->get_user()->loc),
                    'tax' => numberClean($product_tax[$key]),
                    'discount' => numberClean($product_discount[$key]),
                    'subtotal' => rev_amountExchange_s($product_subtotal[$key], $currency, $this->aauth->get_user()->loc),
                    'totaltax' => rev_amountExchange_s($ptotal_tax[$key], $currency, $this->aauth->get_user()->loc),
                    'totaldiscount' => rev_amountExchange_s($ptotal_disc[$key], $currency, $this->aauth->get_user()->loc),
                    'product_des' => $product_des[$key],
                    'unit' => $product_unit[$key],
                    'product_unique_id' => $product_unique_id[$key],
                    'warehouse_id'=>$warehouse
                );
                $dataupdate[$index_update] = $data_u;
                $index_update++;
            }
            
            $prodindex++;
            $amt = numberClean($product_qty[$key]);
            $itc += $amt;
            $flag = true;
        }

        $bill_date = datefordatabase($invoicedate);
        $bill_due_date = datefordatabase($invocieduedate);
        $total_discount = rev_amountExchange_s(amountFormat_general($total_discount), $currency, $this->aauth->get_user()->loc);
        $total_tax = rev_amountExchange_s(amountFormat_general($total_tax), $currency, $this->aauth->get_user()->loc);

        $data = array('invoicedate' => $bill_date, 'invoiceduedate' => $bill_due_date, 'subtotal' => $subtotal, 'shipping' => $shipping, 'ship_tax' => $shipping_tax, 'ship_tax_type' => $ship_taxtype, 'discount' => $total_discount, 'tax' => $total_tax, 'total' => $total, 'notes' => $notes, 'csd' => $customer_id, 'items' => $itc, 'taxstatus' => $tax, 'discstatus' => $discstatus, 'format_discount' => $discountFormat, 'refer' => $refer, 'term' => $pterms, 'multi' => $currency);
        $this->db->set($data);
        $this->db->where('id', $invocieno);

        if ($flag) {
            if ($this->db->update('geopos_purchase', $data)) {
                
                $this->db->select('title');
                $this->db->from('geopos_warehouse');
                $this->db->where('id', $warehouse);
                $query = $this->db->get();
                $warehouse_name = $query->row_array()['title'];

                //restock
                $purchase_product = $this->purchase->purchase_products_stock($invocieno);
                $data_update_stock = array();
                $pro_update = 0;
                foreach($purchase_product as $p){
                    $d_update = array(
                        'product_id' => $p['pid'],
                        'unique_id' => $p['product_unique_id'],
                        'qty' => numberClean($p['stock_qty']-$p['qty']),
                        'purchase_detail_id' => $p['id'],
                        'product_desc' => $p['product_des'],
                        'id' => $p['stock_id']
                    );
                    $data_update_stock[$pro_update] = $d_update;
                    $pro_update++;
                    $this->aauth->applog("[Product Purchase Edit] ".$p['product']." Qty -".$p['qty']." from $warehouse_name", $this->aauth->get_user()->username);
                }
                if($pro_update>0){
                    $this->db->update_batch('tb_stock', $data_update_stock,'id');
                }

                //delete purchase item
                $delete_index = 0;
                foreach ($restockpurchase as $key => $value) {
                    $restockarr = explode('-', $restockpurchase[$key]);
                    $repur_id = $restockarr[0];
                    $restock_id = $restockarr[1];
                    $restock_qty = $restockarr[2];
                    if((int)$repur_id!=0){
                        array_push($delete_purchase,$repur_id);
                        $delete_index++;
                    }
                }
                if($delete_index>0){
                    $this->db->where_in('id', $delete_purchase);
                    $this->db->delete('geopos_purchase_items');
                }
                
                //update or insert purchase item
                if($index_insert>0){
                    $this->db->insert_batch('geopos_purchase_items', $datainsert);
                }
                if($index_update>0){
                    $this->db->update_batch('geopos_purchase_items', $dataupdate,'id');
                }
                
                //update stock
                $purchase_product = $this->purchase->purchase_products_stock($invocieno);
                $data_insert = array();
                $data_update = array();
                $pro_update = 0;
                $pro_insert = 0;
                foreach($purchase_product as $p){
                    if($p['stock_id']==0){
                        $d_insert = array(
                            'product_id' => $p['pid'],
                            'warehouse_id' => $p['warehouse_id'],
                            'unique_id' => $p['product_unique_id'],
                            'qty' => numberClean($p['qty']),
                            'purchase_detail_id' => $p['id'],
                            'product_desc' => $p['product_des'],
                            'sale_status' => 'in-stock',
                            'init_stock' => $p['warehouse_id']
                        );
                        $data_insert[$pro_insert] = $d_insert;
                        $pro_insert++;
                    }
                    else{
                        $d_update = array(
                            'product_id' => $p['pid'],
                            'unique_id' => $p['product_unique_id'],
                            'qty' => numberClean($p['qty']+$p['stock_qty']),
                            'purchase_detail_id' => $p['id'],
                            'product_desc' => $p['product_des'],
                            'id' => $p['stock_id']
                        );
                        $data_update[$pro_update] = $d_update;
                        $pro_update++;
                    }
                    $this->aauth->applog("[Product Purchase Edit] ".$p['product']." Qty +".$p['qty']." to $warehouse_name", $this->aauth->get_user()->username);
                }
                if($pro_insert>0){
                    $this->db->insert_batch('tb_stock', $data_insert);
                }
                if($pro_update>0){
                    $this->db->update_batch('tb_stock', $data_update,'id');
                }
                
                echo json_encode(array('status' => 'Success', 'message' =>
                    "Purchase order has  been updated successfully! <a href='view?id=$invocieno' class='btn btn-info btn-lg'><span class='fa fa-eye' aria-hidden='true'></span> View </a> "));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    "There is a missing field!"));
                $transok = false;
            }


        } else {
            echo json_encode(array('status' => 'Error', 'message' =>
                "Please add atleast one product in order!"));
            $transok = false;
        }
        
        if ($transok) {
            $this->db->trans_complete();
        } else {
            $this->db->trans_rollback();
        }
    }

    public function update_status()
    {
        $tid = $this->input->post('tid');
        $status = $this->input->post('status');


        $this->db->set('status', $status);
        $this->db->where('id', $tid);
        $this->db->update('geopos_purchase');

        echo json_encode(array('status' => 'Success', 'message' =>
            'Purchase Order Status updated successfully!', 'pstatus' => $status));
    }

    public function file_handling()
    {
        if ($this->input->get('op')) {
            $name = $this->input->get('name');
            $invoice = $this->input->get('invoice');
            if ($this->purchase->meta_delete($invoice, 4, $name)) {
                echo json_encode(array('status' => 'Success'));
            }
        } else {
            $id = $this->input->get('id');
            $this->load->library("Uploadhandler_generic", array(
                'accept_file_types' => '/\.(gif|jpe?g|png|docx|docs|txt|pdf|xls)$/i', 'upload_dir' => FCPATH . 'userfiles/attach/', 'upload_url' => base_url() . 'userfiles/attach/'
            ));
            $files = (string)$this->uploadhandler_generic->filenaam();
            if ($files != '') {

                $this->purchase->meta_insert($id, 4, $files);
            }
        }
    }
}