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

class Search_products extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("Aauth");
        $this->load->model('search_model');
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if (!$this->aauth->premission(1)) {
            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');
        }
    }

//search product in invoice
    public function search()
    {
//        $result = array();
//        $out = array();
//        $row_num = $this->input->post('row_num', true);
//        $name = $this->input->post('name_startsWith', true);
//        $wid = $this->input->post('wid', true);
//        $qw = '';
//        if ($wid > 0) {
//            $qw = "(geopos_products.warehouse='$wid') AND ";
//        }
//        $join = '';
//        if ($this->aauth->get_user()->loc) {
//            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
//            if (BDATA) $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' OR geopos_warehouse.loc=0) AND '; else $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
//        } elseif (!BDATA) {
//            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
//            $qw .= '(geopos_warehouse.loc=0) AND ';
//        }
//        if ($name) {
//            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,geopos_products.product_price,geopos_products.product_code,geopos_products.taxrate,geopos_products.disrate,geopos_products.product_des,geopos_products.qty,geopos_products.unit  FROM geopos_products $join WHERE " . $qw . "(UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%') LIMIT 6");
//
//            $result = $query->result_array();
//            foreach ($result as $row) {
//                $name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], amountFormat_general($row['qty']), $row_num);
//                array_push($out, $name);
//            }
//            echo json_encode($out);
//        }
        
        
        $result = array();
        $out = array();
        $row_num = $this->input->post('row_num', true);
        $name = $this->input->post('name_startsWith', true);
        $wid = $this->input->post('wid', true);
        $iid = (int)$this->input->post('iid', true);
        
        $qw = " if(ifnull(i_stock,0)=0,'$wid',tb_stock.warehouse_id)='$wid' AND ";
        $join = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        $join .= " left join geopos_invoice_items ii on geopos_products.pid=ii.pid and ii.tid='$iid' ";
        //$union = "union all SELECT geopos_products.pid,geopos_products.product_name,geopos_products.product_price,geopos_products.product_code,geopos_products.taxrate,geopos_products.disrate,geopos_products.product_des,geopos_products.qty,geopos_products.unit,0 i_stock,'' unique_id,0 as stock_qty,0 as stock_id FROM geopos_products WHERE ((UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%')) and ifnull(i_stock,0)=0 LIMIT 6";
        
        if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,geopos_products.product_price,geopos_products.product_code,geopos_products.taxrate,geopos_products.disrate,geopos_products.product_des,geopos_products.qty,geopos_products.unit,ifnull(i_stock,0) i_stock,ifnull(tb_stock.unique_id,'') unique_id,ifnull(ii.qty,0)+tb_stock.qty as stock_qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "((UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%')) group by tb_stock.product_id LIMIT 6");
            $result = $query->result_array();
            foreach ($result as $row) {
                $name = array($row['product_name'], amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], amountFormat_general($row['qty']), $row['stock_id'], $row['unique_id'], amountFormat_general($row['stock_qty']),$row['i_stock'], $row_num);
                array_push($out, $name);
            }
            //echo "SELECT geopos_products.pid,geopos_products.product_name,geopos_products.product_price,geopos_products.product_code,geopos_products.taxrate,geopos_products.disrate,geopos_products.product_des,geopos_products.qty,geopos_products.unit,ifnull(i_stock,0) i_stock,ifnull(tb_stock.unique_id,'') unique_id,ifnull(ii.qty,0)+ifnull(tb_stock.qty,0) as stock_qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "((UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%')) group by tb_stock.product_id LIMIT 6";
            echo json_encode($out);
        }

    }
    
    public function search_product_transfer()
    {
        $result = array();
        $out = array();
        $row_num = $this->input->post('row_num', true);
        $name = $this->input->post('name_startsWith', true);
        $wid = $this->input->post('wid', true);
        $qw = " (tb_stock.warehouse_id='$wid') AND tb_stock.qty>0 and geopos_products.i_stock=1 and ";
        $join = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        
        if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "((UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%') OR (UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%')) group by tb_stock.product_id LIMIT 6");

            $result = $query->result_array();
            foreach ($result as $row) {
                $name = array($row['product_name'], $row['pid'], $row['stock_id'], $row['unique_id'], amountFormat_general($row['qty']), $row_num);
                array_push($out, $name);
            }
            echo json_encode($out);
        }

    }
    public function search_unique_id_transfer()
    {
        $result = array();
        $out = array();
        $row_num = $this->input->post('row_num', true);
        $name = $this->input->post('name_startsWith', true);
        $wid = $this->input->post('wid', true);
        $pid = $this->input->post('pid', true);
        $iid = (int)$this->input->post('iid', true);
        $qw = " (tb_stock.warehouse_id='$wid') AND ifnull(ii.qty,0)+ifnull(tb_stock.qty,0)>0 and geopos_products.i_stock=1 and geopos_products.pid='$pid' and ";
        $join = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        $join .= " left join geopos_invoice_items ii on geopos_products.pid=ii.pid and ii.tid='$iid' ";
        
        //if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,ifnull(ii.qty,0)+ifnull(tb_stock.qty,0) as qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "(UPPER(tb_stock.unique_id) LIKE '%" . strtoupper($name) . "%') LIMIT 6");

            $result = $query->result_array();
            foreach ($result as $row) {
                $name = array($row['unique_id'], $row['pid'], $row['stock_id'], $row['product_name'], amountFormat_general($row['qty']), $row_num);
                array_push($out, $name);
            }
            echo json_encode($out);
        //}

    }
    
    public function verify_product(){
        $result = array();
        $out = array();
        $name = $this->input->post('unique_id', true);
        $wid = $this->input->post('wid', true);
        $pid = $this->input->post('pid', true);
        $qw = " (tb_stock.warehouse_id='$wid') AND tb_stock.qty>0 and geopos_products.i_stock=1 and geopos_products.pid='$pid' and ";
        $join = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        //if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "(UPPER(ifnull(tb_stock.unique_id,'')) = '" . strtoupper($name) . "')");
            $result = $query->result_array();
            echo json_encode($result);
            //echo "SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "(UPPER(ifnull(tb_stock.unique_id,'')) = '" . strtoupper($name) . "')";
        //}
    }
    
    public function verify_product_sale(){
        $name = $this->input->post('unique_id', true);
        $wid = $this->input->post('wid', true);
        $pid = $this->input->post('pid', true);
        $iid = (int)$this->input->post('iid', true);
        $qw = " if(ifnull(i_stock,0)=0,'$wid',tb_stock.warehouse_id)='$wid' AND if(ifnull(i_stock,0)=0,1,ifnull(ii.qty,0)+ifnull(tb_stock.qty,0))>0 and geopos_products.pid='$pid' and ";
        $join = " left join tb_stock on geopos_products.pid=tb_stock.product_id ";
        $join .= " left join geopos_invoice_items ii on geopos_products.pid=ii.pid and ii.tid='$iid' ";
        //if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,ifnull(ii.qty,0)+ifnull(tb_stock.qty,0) as qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "(UPPER(ifnull(tb_stock.unique_id,'')) = '" . strtoupper($name) . "')");
            $result = $query->result_array();
            echo json_encode($result);
            //echo "SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw . "(UPPER(ifnull(tb_stock.unique_id,'')) = '" . strtoupper($name) . "')";
        //}
    }

    public function verify_product_purchase(){
        $result = array();
        $out = array();
        $name = $this->input->post('unique_id', true);
        $pid = $this->input->post('pid', true);
        $qw = " geopos_products.i_stock=1 and geopos_products.pid='$pid' and ifnull(tb_stock.unique_id,'') = '$name' and tb_stock.qty>0 ";
        $join = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        //if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw);
            $result = $query->result_array();
            echo json_encode($result);
            //echo "SELECT geopos_products.pid,geopos_products.product_name,ifnull(tb_stock.unique_id,'') unique_id,tb_stock.qty,tb_stock.id as stock_id FROM geopos_products $join WHERE " . $qw;
        //}
    }
    public function puchase_search()
    {
        $result = array();
        $out = array();
        $row_num = $this->input->post('row_num', true);
        $name = $this->input->post('name_startsWith', true);
        $wid = $this->input->post('wid', true);
        $qw = ' geopos_products.i_stock=1 and ';
        if ($wid > 0) {
            //$qw = "(geopos_products.warehouse='$wid' ) AND ";
        }
        $join = '';
        if ($this->aauth->get_user()->loc) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            if (BDATA) $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' OR geopos_warehouse.loc=0) AND '; else $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            $qw .= '(geopos_warehouse.loc=0) AND ';
        }
        if ($name) {
            $query = $this->db->query("SELECT geopos_products.pid,geopos_products.product_name,geopos_products.product_code,geopos_products.fproduct_price,geopos_products.taxrate,geopos_products.disrate,geopos_products.product_des,geopos_products.unit FROM geopos_products $join WHERE " . $qw . "UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%' OR UPPER(geopos_products.product_code) LIKE '" . strtoupper($name) . "%' LIMIT 6");

            $result = $query->result_array();
            foreach ($result as $row) {
                $name = array($row['product_name'], amountExchange_s($row['fproduct_price'], 0, $this->aauth->get_user()->loc), $row['pid'], amountFormat_general($row['taxrate']), amountFormat_general($row['disrate']), $row['product_des'], $row['unit'], $row['product_code'], $row_num);
                array_push($out, $name);
            }

            echo json_encode($out);
        }

    }

    public function csearch()
    {
        $result = array();
        $out = array();
        $name = $this->input->get('keyword', true);
        $whr = '';
        if ($this->aauth->get_user()->loc) {
            $whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
            if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $whr = ' (loc=0) AND ';
        }
        if ($name) {
            $query = $this->db->query("SELECT id,name,address,city,phone,email,discount_c FROM geopos_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
            $result = $query->result_array();
            echo '<ol>';
            $i = 1;
            foreach ($result as $row) {

                echo "<li onClick=\"selectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "','" . amountFormat_general($row['discount_c']) . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
                $i++;
            }
            echo '</ol>';
        }

    }

    public function party_search()
    {
        $result = array();
        $out = array();
        $tbl = 'geopos_customers';
        $name = $this->input->get('keyword', true);

        $ty = $this->input->get('ty', true);
        if ($ty) $tbl = 'geopos_supplier';
        $whr = '';


        if ($this->aauth->get_user()->loc) {
            $whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
            if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $whr = ' (loc=0) AND ';
        }


        if ($name) {
            $query = $this->db->query("SELECT id,name,address,city,phone,email FROM $tbl  WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
            $result = $query->result_array();
            echo '<ol>';
            $i = 1;
            foreach ($result as $row) {

                echo "<li onClick=\"selectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
                $i++;
            }
            echo '</ol>';
        }

    }

    public function pos_c_search()
    {
        $result = array();
        $out = array();
        $name = $this->input->get('keyword', true);
        $whr = '';
        if ($this->aauth->get_user()->loc) {
            $whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
            if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $whr = ' (loc=0) AND ';
        }

        if ($name) {
            $query = $this->db->query("SELECT id,name,phone,discount_c FROM geopos_customers WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
            $result = $query->result_array();
            echo '<ol>';
            $i = 1;
            foreach ($result as $row) {
                echo "<li onClick=\"PselectCustomer('" . $row['id'] . "','" . $row['name'] . " ','" . amountFormat_general($row['discount_c']) . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
                $i++;
            }
            echo '</ol>';
        }

    }


    public function supplier()
    {
        $result = array();
        $out = array();
        $name = $this->input->get('keyword', true);

        $whr = '';
        if ($this->aauth->get_user()->loc) {
            $whr = ' (loc=' . $this->aauth->get_user()->loc . ' OR loc=0) AND ';
            if (!BDATA) $whr = ' (loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $whr = ' (loc=0) AND ';
        }
        if ($name) {
            $query = $this->db->query("SELECT id,name,address,city,phone,email FROM geopos_supplier WHERE $whr (UPPER(name)  LIKE '%" . strtoupper($name) . "%' OR UPPER(phone)  LIKE '" . strtoupper($name) . "%') LIMIT 6");
            $result = $query->result_array();
            echo '<ol>';
            $i = 1;
            foreach ($result as $row) {
                echo "<li onClick=\"selectSupplier('" . $row['id'] . "','" . $row['name'] . " ','" . $row['address'] . "','" . $row['city'] . "','" . $row['phone'] . "','" . $row['email'] . "')\"><span>$i</span><p>" . $row['name'] . " &nbsp; &nbsp  " . $row['phone'] . "</p></li>";
                $i++;
            }
            echo '</ol>';
        }

    }

    public function pos_search()
    {

        $out = '';
        $name = $this->input->post('name', true);
        $cid = $this->input->post('cid', true);
        $wid = $this->input->post('wid', true);
        $qw = '';
        if ($wid > 0) {
            $qw .= "(geopos_products.warehouse='$wid') AND ";
        }
        if ($cid > 0) {
            $qw .= "(geopos_products.pcat='$cid') AND ";
        }
        $join = '';
        if ($this->aauth->get_user()->loc) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            if (BDATA) $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' OR geopos_warehouse.loc=0) AND '; else $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            $qw .= '(geopos_warehouse.loc=0) AND ';
        }
        $bar = '';
        if (is_numeric($name)) {
            $b = array('-', '-', '-');
            $c = array(3, 4, 11);
            $barcode = $name;
            for ($i = count($c) - 1; $i >= 0; $i--) {
                $barcode = substr_replace($barcode, $b[$i], $c[$i], 0);
            }

            $bar = " OR (geopos_products.barcode LIKE '" . (substr($barcode, 0, -1)) . "%' OR geopos_products.barcode LIKE '" . $name . "%')";
        }
        $query = "SELECT geopos_products.* FROM geopos_products $join WHERE " . $qw . "(UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR geopos_products.product_code LIKE '" . strtoupper($name) . "%') AND (geopos_products.qty>0) LIMIT 16";


        $query = $this->db->query($query);

        $result = $query->result_array();
        $i = 0;
        echo '<div class="row match-height">';
        foreach ($result as $row) {

            $out .= '    <div class="col-3 border mb-1 "><div class="rounded">
                                 <a   id="posp' . $i . '"  class="select_pos_item btn btn-outline-light-blue round"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['taxrate']) . '"  data-discount="' . amountFormat_general($row['disrate']) . '"   data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['qty']) . '" data-unit="' . $row['unit'] . '" >
                                        <img class="round"
                                             src="' . base_url('userfiles/product/' . $row['image']) . '"  style="max-height: 100%;max-width: 100%">
                                        <div class="text-xs-center text">
                                       
                                            <small style="white-space: pre-wrap;">' . $row['product_name'] . '</small>

                                            
                                        </div></a>
                                  
                                </div></div>';

            $i++;
            //   if ($i % 4 == 0) $out .= '</div><div class="row">';
        }

        echo $out;

    }

    public function v2_pos_search()
    {

        $out = '';
        $name = $this->input->post('name', true);
        $cid = $this->input->post('cid', true);
        $wid = $this->input->post('wid', true);
        $qw = '';
        if ($wid > 0) {
            $qw .= "(geopos_products.warehouse='$wid') AND ";
        }
        if ($cid > 0) {
            $qw .= "(geopos_products.pcat='$cid') AND ";
        }
        $join = '';

        if ($this->aauth->get_user()->loc) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            if (BDATA) $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' OR geopos_warehouse.loc=0) AND '; else $qw .= '(geopos_warehouse.loc=' . $this->aauth->get_user()->loc . ' ) AND ';
        } elseif (!BDATA) {
            $join = 'LEFT JOIN geopos_warehouse ON geopos_warehouse.id=geopos_products.warehouse';
            $qw .= '(geopos_warehouse.loc=0) AND ';
        }
        $bar = '';

        if (is_numeric($name)) {
            $b = array('-', '-', '-');
            $c = array(3, 4, 11);
            $barcode = $name;
            for ($i = count($c) - 1; $i >= 0; $i--) {
                $barcode = substr_replace($barcode, $b[$i], $c[$i], 0);
            }
            //    echo(substr($barcode, 0, -1));
            $bar = " OR (geopos_products.barcode LIKE '" . (substr($barcode, 0, -1)) . "%' OR geopos_products.barcode LIKE '" . $name . "%')";
            //  $query = "SELECT geopos_products.* FROM geopos_products $join WHERE " . $qw . " $bar AND (geopos_products.qty>0) LIMIT 16";
        }
        $query = "SELECT geopos_products.* FROM geopos_products $join WHERE " . $qw . "(UPPER(geopos_products.product_name) LIKE '%" . strtoupper($name) . "%' $bar OR geopos_products.product_code LIKE '" . strtoupper($name) . "%') AND (geopos_products.qty>0) ORDER BY geopos_products.product_name LIMIT 18";

        $query = $this->db->query($query);
        $result = $query->result_array();
        $i = 0;
        echo '<div class="row match-height">';
        foreach ($result as $row) {

            $out .= '    <div class="col-2 border mb-1"  ><div class=" rounded" >
                                 <a  id="posp' . $i . '"  class="v2_select_pos_item round"   data-name="' . $row['product_name'] . '"  data-price="' . amountExchange_s($row['product_price'], 0, $this->aauth->get_user()->loc) . '"  data-tax="' . amountFormat_general($row['taxrate']) . '"  data-discount="' . amountFormat_general($row['disrate']) . '" data-pcode="' . $row['product_code'] . '"   data-pid="' . $row['pid'] . '"  data-stock="' . amountFormat_general($row['qty']) . '" data-unit="' . $row['unit'] . '" >
                                        <img class="round"
                                             src="' . base_url('userfiles/product/' . $row['image']) . '"  style="max-height: 100%;max-width: 100%">
                                        <div class="text-center" style="margin-top: 4px;">
                                       
                                            <small style="white-space: pre-wrap;">' . $row['product_name'] . '</small>

                                            
                                        </div></a>
                                  
                                </div></div>';

            $i++;

        }

        echo $out;

    }
}