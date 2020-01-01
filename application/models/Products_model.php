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

class Products_model extends CI_Model
{
    var $table = 'geopos_products';
    var $column_order = array(null, 'geopos_products.product_name', 'geopos_products.qty', 'geopos_products.product_code', 'geopos_product_cat.title', 'geopos_products.product_price', null); //set column field database for datatable orderable
    var $column_search = array('geopos_products.product_name', 'geopos_products.product_code', 'geopos_product_cat.title', 'geopos_warehouse.title'); //set column field database for datatable searchable
    var $order = array('geopos_products.pid' => 'desc'); // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($id = '', $w = '', $sub = '')
    {
        $this->db->select('geopos_products.*,geopos_product_cat.title AS c_title,geopos_warehouse.title,tb_stock.qty as product_qty,tb_stock.unique_id');
        $this->db->from($this->table);
        $this->db->join('tb_stock', 'geopos_products.pid = tb_stock.product_id');
        $this->db->join('geopos_warehouse', 'tb_stock.warehouse_id=geopos_warehouse.id');
        if ($sub) {
            $this->db->join('geopos_product_cat', 'geopos_product_cat.id = geopos_products.sub_id');
            $this->db->where('geopos_products.merge', 0);
            if ($this->aauth->get_user()->loc) {
                $this->db->group_start();
                $this->db->where('geopos_warehouse.loc', $this->aauth->get_user()->loc);
                if (BDATA) $this->db->or_where('geopos_warehouse.loc', 0);
                $this->db->group_end();
            } elseif (!BDATA) {
                $this->db->where('geopos_warehouse.loc', 0);
            }

            $this->db->where("geopos_products.sub_id=$id");
            

        } else {
            $this->db->join('geopos_product_cat', 'geopos_product_cat.id = geopos_products.pcat');
            $this->db->where('tb_stock.qty>0');
            if ($w) {

                if ($id > 0) {
                    $this->db->where("geopos_warehouse.id = $id");
                   // $this->db->where('geopos_products.sub_id', 0);
                }
                if ($this->aauth->get_user()->loc) {
                    $this->db->group_start();
                    $this->db->where('geopos_warehouse.loc', $this->aauth->get_user()->loc);

                    if (BDATA) $this->db->or_where('geopos_warehouse.loc', 0);
                    $this->db->group_end();
                } elseif (!BDATA) {
                    $this->db->where('geopos_warehouse.loc', 0);
                }

            } else {

                $this->db->where('geopos_products.merge', 0);
                if ($this->aauth->get_user()->loc) {
                    $this->db->group_start();
                    $this->db->where('geopos_warehouse.loc', $this->aauth->get_user()->loc);
                    if (BDATA) $this->db->or_where('geopos_warehouse.loc', 0);
                    $this->db->group_end();
                } elseif (!BDATA) {
                    $this->db->where('geopos_warehouse.loc', 0);
                }
                if ($id > 0) {
                    $this->db->where("geopos_product_cat.id = $id");
                    $this->db->where('geopos_products.sub_id', 0);
                }
            }
        }

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    private function _get_datatables_item_query($id = '', $w = '', $sub = '')
    {
        $this->db->select('geopos_products.*,geopos_product_cat.title AS c_title');
        $this->db->from($this->table);
        if ($sub) {
            $this->db->join('geopos_product_cat', 'geopos_product_cat.id = geopos_products.sub_id');
            $this->db->where('geopos_products.merge', 0);
            $this->db->where("geopos_products.sub_id=$id");

        } else {
            $this->db->join('geopos_product_cat', 'geopos_product_cat.id = geopos_products.pcat');
            if ($w) {
                

            } else {

                $this->db->where('geopos_products.merge', 0);
                if ($id > 0) {
                    $this->db->where("geopos_product_cat.id = $id");
                    $this->db->where('geopos_products.sub_id', 0);
                }
            }
        }

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            $search = $this->input->post('search');
            $value = $search['value'];
            if ($value) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $value);
                } else {
                    $this->db->or_like($item, $value);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }
        $search = $this->input->post('order');
        if ($search) // here order processing
        {
            $this->db->order_by($this->column_order[$search['0']['column']], $search['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables($id = '', $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }
    
    function get_datatables_item($id = '', $w = '', $sub = '')
    {
        if ($id > 0) {
            $this->_get_datatables_item_query($id, $w, $sub);
        } else {
            $this->_get_datatables_item_query();
        }
        if ($this->input->post('length') != -1)
            $this->db->limit($this->input->post('length'), $this->input->post('start'));
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id, $w = '', $sub='')
    {
        if ($id > 0) {
            $this->_get_datatables_query($id, $w, $sub);
        } else {
            $this->_get_datatables_query();
        }

        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->join('geopos_warehouse', 'geopos_warehouse.id = geopos_products.warehouse');
        if ($this->aauth->get_user()->loc) {

            $this->db->where('geopos_warehouse.loc', $this->aauth->get_user()->loc);
            if (BDATA) $this->db->or_where('geopos_warehouse.loc', 0);
        } elseif (!BDATA) {
            $this->db->where('geopos_warehouse.loc', 0);
        }
        return $this->db->count_all_results();
    }

    public function addnew($catid, $warehouse, $product_name, $product_code, $product_price, $factoryprice, $taxrate, $disrate, $product_qty, $product_qty_alert, $product_desc, $image, $unit, $barcode, $v_type, $v_stock, $v_alert, $wdate, $code_type, $w_type = '', $w_stock = '', $w_alert = '', $sub_cat = '', $b_id = '', $is_stock = '')
    {
        $ware_valid = $this->valid_warehouse($warehouse);
        if(!$sub_cat) $sub_cat=0;
        if(!$b_id) $b_id=0;
        $datetime1 = new DateTime(date('Y-m-d'));

        $datetime2 = new DateTime($wdate);

        $difference = $datetime1->diff($datetime2);
        if (!$difference->d > 0) {
            $wdate = null;
        }

        if ($this->aauth->get_user()->loc) {
            if ($ware_valid['loc'] == $this->aauth->get_user()->loc OR $ware_valid['loc'] == '0' OR $warehouse == 0) {
                if (strlen($barcode) > 5 AND is_numeric($barcode)) {
                    $data = array(
                        'pcat' => $catid,
                        'warehouse' => $warehouse,
                        'product_name' => $product_name,
                        'product_code' => $product_code,
                        'product_price' => $product_price,
                        'fproduct_price' => $factoryprice,
                        'taxrate' => $taxrate,
                        'disrate' => $disrate,
                        'qty' => $product_qty,
                        'product_des' => $product_desc,
                        'alert' => $product_qty_alert,
                        'unit' => $unit,
                        'image' => $image,
                        'barcode' => $barcode,
                        'expiry' => $wdate,
                        'code_type' => $code_type,
                        'sub_id' => $sub_cat,
                        'b_id' => $b_id,
                        'i_stock' => (int)$is_stock
                    );

                } else {

                    $barcode = rand(100, 999) . rand(0, 9) . rand(1000000, 9999999) . rand(0, 9);

                    $data = array(
                        'pcat' => $catid,
                        'warehouse' => $warehouse,
                        'product_name' => $product_name,
                        'product_code' => $product_code,
                        'product_price' => $product_price,
                        'fproduct_price' => $factoryprice,
                        'taxrate' => $taxrate,
                        'disrate' => $disrate,
                        'qty' => $product_qty,
                        'product_des' => $product_desc,
                        'alert' => $product_qty_alert,
                        'unit' => $unit,
                        'image' => $image,
                        'barcode' => $barcode,
                        'expiry' => $wdate,
                        'code_type' => 'EAN13',
                        'sub_id' => $sub_cat,
                        'b_id' => $b_id,
                        'i_stock' => (int)$is_stock
                    );
                }
                $this->db->trans_start();
                if ($this->db->insert('geopos_products', $data)) {
                    $pid = $this->db->insert_id();
                    $this->movers(1, $pid, $product_qty, 0, 'Stock Initialized');
                    $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    echo json_encode(array('status' => 'Success', 'message' =>
                        $this->lang->line('ADDED') . "  <a href='add' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('ERROR')));
                }
                if ($v_type) {
                    foreach ($v_type as $key => $value) {
                        if ($v_type[$key] && numberClean($v_stock[$key]) > 0.00) {
                            $this->db->select('u.id,u.name,u2.name AS variation');
                            $this->db->join('geopos_units u2', 'u.rid = u2.id', 'left');
                            $this->db->where('u.id', $v_type[$key]);
                            $query = $this->db->get('geopos_units u');
                            $r_n = $query->row_array();
                            $data['product_name'] = $product_name . '-' . $r_n['variation'] . '-' . $r_n['name'];
                            $data['qty'] = numberClean($v_stock[$key]);
                            $data['alert'] = numberClean($v_alert[$key]);
                            $data['merge'] = 1;
                            $data['sub'] = $pid;
                            $data['vb'] = $v_type[$key];
                            $this->db->insert('geopos_products', $data);
                            $pidv = $this->db->insert_id();
                            $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                            $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                        }
                    }
                }
                if ($w_type) {
                    foreach ($w_type as $key => $value) {
                        if ($w_type[$key] && numberClean($w_stock[$key]) > 0.00 && $w_type[$key] != $warehouse) {
                            $data['product_name'] = $product_name;
                            $data['warehouse'] = $w_type[$key];
                            $data['qty'] = numberClean($w_stock[$key]);
                            $data['alert'] = numberClean($w_alert[$key]);
                            $data['merge'] = 2;
                            $data['sub'] = $pid;
                            $data['vb'] = $w_type[$key];
                            $this->db->insert('geopos_products', $data);
                            $pidv = $this->db->insert_id();
                            $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                            $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                        }
                    }
                }
                $this->db->trans_complete();
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        } else {
            if (strlen($barcode) > 5 AND is_numeric($barcode)) {
                $data = array(
                    'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'fproduct_price' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'expiry' => $wdate,
                    'code_type' => $code_type,
                    'sub_id' => $sub_cat,
                    'b_id' => $b_id,
                    'i_stock' => (int)$is_stock
                );
            } else {
                $barcode = rand(100, 999) . rand(0, 9) . rand(1000000, 9999999) . rand(0, 9);
                $data = array(
                    'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'fproduct_price' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'expiry' => $wdate,
                    'code_type' => 'EAN13',
                    'sub_id' => $sub_cat,
                    'b_id' => $b_id,
                    'i_stock' => (int)$is_stock
                );
            }
            $this->db->trans_start();
            if ($this->db->insert('geopos_products', $data)) {
                $pid = $this->db->insert_id();
                $this->movers(1, $pid, $product_qty, 0, 'Stock Initialized');
                $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                echo json_encode(array('status' => 'Success', 'message' =>
                    $this->lang->line('ADDED') . "  <a href='add' class='btn btn-blue btn-lg'><span class='fa fa-plus-circle' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
            if ($v_type) {
                foreach ($v_type as $key => $value) {
                    if ($v_type[$key] && numberClean($v_stock[$key]) > 0.00) {
                        $this->db->select('u.id,u.name,u2.name AS variation');
                        $this->db->join('geopos_units u2', 'u.rid = u2.id', 'left');
                        $this->db->where('u.id', $v_type[$key]);

                        $query = $this->db->get('geopos_units u');
                        $r_n = $query->row_array();
                        $data['product_name'] = $product_name . '-' . $r_n['variation'] . '-' . $r_n['name'];
                        $data['qty'] = numberClean($v_stock[$key]);
                        $data['alert'] = numberClean($v_alert[$key]);
                        $data['merge'] = 1;
                        $data['sub'] = $pid;
                        $data['vb'] = $v_type[$key];
                        $this->db->insert('geopos_products', $data);
                        $pidv = $this->db->insert_id();
                        $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                        $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    }
                }
            }
            if ($w_type) {
                foreach ($w_type as $key => $value) {
                    if ($w_type[$key] && numberClean($w_stock[$key]) > 0.00 && $w_type[$key] != $warehouse) {

                        $data['product_name'] = $product_name;
                        $data['warehouse'] = $w_type[$key];
                        $data['qty'] = numberClean($w_stock[$key]);
                        $data['alert'] = numberClean($w_alert[$key]);
                        $data['merge'] = 2;
                        $data['sub'] = $pid;
                        $data['vb'] = $w_type[$key];
                        $this->db->insert('geopos_products', $data);
                        $pidv = $this->db->insert_id();
                        $this->movers(1, $pidv, $data['qty'], 0, 'Stock Initialized');
                        $this->aauth->applog("[New Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    }
                }
            }
            $this->custom->save_fields_data($pid, 4);
            $this->db->trans_complete();

        }
    }

    public function edit($pid, $catid, $warehouse, $product_name, $product_code, $product_price, $factoryprice, $taxrate, $disrate, $product_qty, $product_qty_alert, $product_desc, $image, $unit, $barcode, $code_type, $sub_cat = '', $b_id = '', $is_stock='')
    {
        $ware_valid = $this->valid_warehouse($warehouse);
        if ($this->aauth->get_user()->loc) {
            if ($ware_valid['loc'] == $this->aauth->get_user()->loc OR $ware_valid['loc'] == '0' OR $warehouse == 0) {
                $data = array(
                    'pcat' => $catid,
                    'warehouse' => $warehouse,
                    'product_name' => $product_name,
                    'product_code' => $product_code,
                    'product_price' => $product_price,
                    'fproduct_price' => $factoryprice,
                    'taxrate' => $taxrate,
                    'disrate' => $disrate,
                    'qty' => $product_qty,
                    'product_des' => $product_desc,
                    'alert' => $product_qty_alert,
                    'unit' => $unit,
                    'image' => $image,
                    'barcode' => $barcode,
                    'code_type' => $code_type,
                    'sub_id' => $sub_cat,
                    'b_id' => $b_id,
                    'i_stock' => (int)$is_stock
                );

                $this->db->set($data);
                $this->db->where('pid', $pid);

                if ($this->db->update('geopos_products')) {
                    
                    $this->aauth->applog("[Update Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                    echo 1;
                    echo json_encode(array('status' => 'Success', 'message' =>
                        $this->lang->line('UPDATED') . " <a href='" . base_url('products/edit?id=' . $pid) . "' class='btn btn-blue btn-lg'><span class='fa fa-eye' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
                } else {
                    echo json_encode(array('status' => 'Error', 'message' =>
                        $this->lang->line('ERROR')));
                }
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        } else {
            $data = array(
                'pcat' => $catid,
                'warehouse' => $warehouse,
                'product_name' => $product_name,
                'product_code' => $product_code,
                'product_price' => $product_price,
                'fproduct_price' => $factoryprice,
                'taxrate' => $taxrate,
                'disrate' => $disrate,
                'qty' => $product_qty,
                'product_des' => $product_desc,
                'alert' => $product_qty_alert,
                'unit' => $unit,
                'image' => $image,
                'barcode' => $barcode,
                'code_type' => $code_type,
                'sub_id' => $sub_cat,
                'b_id' => $b_id,
                'i_stock' => (int)$is_stock
            );
                    
            $this->db->set($data);
            $this->db->where('pid', $pid);
            if ($this->db->update('geopos_products')) {
                $this->aauth->applog("[Update Product] -$product_name  -Qty-$product_qty ID " . $pid, $this->aauth->get_user()->username);
                echo json_encode(array('status' => 'Success', 'message' =>
                $this->lang->line('UPDATED') . " <a href='" . base_url('products/edit?id=' . $pid) . "' class='btn btn-blue btn-lg'><span class='fa fa-eye' aria-hidden='true'></span>  </a> <a href='" . base_url('products') . "' class='btn btn-grey-blue btn-lg'><span class='fa fa-list-alt' aria-hidden='true'></span>  </a>"));
            } else {
                echo json_encode(array('status' => 'Error', 'message' =>
                    $this->lang->line('ERROR')));
            }
        }
        $this->custom->edit_save_fields_data($pid, 4);

    }

    public function prd_stats()
    {

        $whr = ' left join tb_stock on geopos_products.pid=tb_stock.product_id ';
        $w = 0;
        if ($this->aauth->get_user()->loc) {
            $w = 1;
            $whr .= 'LEFT JOIN  geopos_warehouse on tb_stock.warehouse_id = geopos_warehouse.id WHERE geopos_warehouse.loc=' . $this->aauth->get_user()->loc;
            if (BDATA) $whr .= 'LEFT JOIN  geopos_warehouse on tb_stock.warehouse_id = geopos_warehouse.id WHERE (geopos_warehouse.loc=0 OR geopos_warehouse.loc=' . $this->aauth->get_user()->loc.")";
        } elseif (!BDATA) {
            $w = 1;
            $whr .= 'LEFT JOIN  geopos_warehouse on tb_stock.warehouse_id=geopos_warehouse.id WHERE geopos_warehouse.loc=0';
        }
        if($w==0){
            $whr .= " where ifnull(geopos_products.i_stock,0)=1 ";
        }
        else{
            $whr .= " and ifnull(geopos_products.i_stock,0)=1 ";
        }
        $query = $this->db->query("SELECT
                                COUNT(IF( ifnull(tb_stock.qty,0) > 0, 1, NULL)) AS instock,
                                COUNT(IF( ifnull(tb_stock.qty,0) <= 0, 0, NULL)) AS outofstock,
                                COUNT(ifnull(ifnull(tb_stock.qty,0),0)) AS total
                                FROM geopos_products $whr");
        echo json_encode($query->result_array());
    }

    public function products_list($id, $term = '')
    {
        $this->db->select('geopos_products.*');
        $this->db->from('geopos_products');
        $this->db->where('geopos_products.warehouse', $id);
        if ($this->aauth->get_user()->loc) {
            $this->db->join('geopos_warehouse', 'geopos_warehouse.id = geopos_products.warehouse');
            $this->db->where('geopos_warehouse.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->join('geopos_warehouse', 'geopos_warehouse.id = geopos_products.warehouse');
            $this->db->where('geopos_warehouse.loc', 0);
        }
        if ($term) {
            $this->db->where("geopos_products.product_name LIKE '%$term%'");
            $this->db->or_where("geopos_products.product_code LIKE '$term%'");
        }
        $query = $this->db->get();
        return $query->result_array();

    }


    public function units()
    {
        $this->db->select('*');
        $this->db->from('geopos_units');
        $this->db->where('type', 0);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function transfer($from_warehouse, $to_warehouse, $transferdate, $notes, $items, $pid, $psid, $unique_id, $qty, $pro_name)
    {
        
        $this->db->select('title');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $to_warehouse);
        $query = $this->db->get();
        $to_warehouse_name = $query->row_array()['title'];
        
        $this->db->select('title');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $from_warehouse);
        $query = $this->db->get();
        $from_warehouse_name = $query->row_array()['title'];

        $data = array(
            'transferdate' => datefordatabase($transferdate), 
            'notes' => $notes,
            'eid' => $this->aauth->get_user()->id, 
            'items' => $items, 
            'from_warehouse' => $from_warehouse, 
            'to_warehouse' => $to_warehouse,
            'loc' => $this->aauth->get_user()->loc
        );
        if ($this->db->insert('tb_transfers', $data)) {
            $transfer_id = $this->db->insert_id();
            $prodindex = 0;
            $productlist = array();
            foreach ($pid as $key => $value) {
                if($unique_id[$key]!=""){
                    $this->db->set('warehouse_id', $to_warehouse, FALSE);
                    $this->db->where('id', $psid[$key]);
                    $this->db->update('tb_stock');
                }
                else{
                    $this->db->select('count(id) as cid');
                    $this->db->from('tb_stock');
                    $this->db->where('warehouse_id', $to_warehouse);
                    $this->db->where('product_id', $pid[$key]);
                    $query = $this->db->get();
                    $cid = $query->row_array()['cid'];
                    if((int)$cid==0){
                        $data_stock = array(
                            "product_id"=>$pid[$key],
                            "warehouse_id"=>$to_warehouse,
                            "unique_id"=>"",
                            "qty"=>$qty[$key],
                            "init_stock"=>$to_warehouse
                        );
                        $this->db->insert("tb_stock",$data_stock);
                    }
                    else{
                        $this->db->set('qty', "qty+$qty[$key]", FALSE);
                        $this->db->where('warehouse_id', $to_warehouse);
                        $this->db->where('product_id', $pid[$key]);
                        $this->db->update('tb_stock');
                    }
                    
                    $this->db->set('qty', "qty-$qty[$key]", FALSE);
                    $this->db->where('id', $psid[$key]);
                    $this->db->update('tb_stock');
                }
                $this->movers(1, $pid[$key], $qty[$key], 0, 'Stock Transferred W- ' . $to_warehouse_name);
                $this->aauth->applog("[Product Transfer] $pro_name[$key] Qty $qty[$key] From $from_warehouse_name to $to_warehouse_name", $this->aauth->get_user()->username);
                
                $data_transfer = array(
                    'tid' => $transfer_id,
                    'pid' => $pid[$key],
                    'qty' => numberClean($qty[$key]),
                    'stock_id' => $psid[$key]
                );

                $productlist[$prodindex] = $data_transfer;
                $prodindex++;
            }
            $this->db->insert_batch('tb_transfer_items', $productlist);
        }
        echo json_encode(array('status' => 'Redirect', 'message' => base_url("products/stock_transfer_list")));
    }
    
    private function _get_transfer_datatables_query($opt = '')
    {
        $this->db->select('tb_transfers.id,tb_transfers.transferdate,tb_transfers.notes,tb_transfers.items,tb_transfers.from_warehouse,tb_transfers.to_warehouse,fw.title as fwarehouse,tw.title as twarehouse');
        $this->db->from("tb_transfers");
        $this->db->join("geopos_warehouse fw","tb_transfers.from_warehouse=fw.id","LEFT");
        $this->db->join("geopos_warehouse tw","tb_transfers.to_warehouse=tw.id","LEFT");
        if ($opt) {
            $this->db->where('tb_transfers.eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('tb_transfers.loc', $this->aauth->get_user()->loc);
        }
        elseif(!BDATA) { $this->db->where('tb_transfers.loc', 0); }
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(tb_transfers.transferdate) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(tb_transfers.transferdate) <=', datefordatabase($this->input->post('end_date')));
        }
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
    }

    function get_transfer_datatables($opt = '')
    {
        $this->_get_transfer_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_all_transfer($opt = '')
    {
        $this->db->select('tb_transfers.id');
        $this->db->from('tb_transfers');
        if ($opt) {
            $this->db->where('tb_transfers.eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('tb_transfers.loc', $this->aauth->get_user()->loc);
        }  elseif(!BDATA) { $this->db->where('tb_transfers.loc', 0); }
        return $this->db->count_all_results();
    }
    
    public function get_transfer_master($id=0)
    {
        $this->db->select('tb_transfers.id,tb_transfers.transferdate,tb_transfers.notes,tb_transfers.items,tb_transfers.from_warehouse,tb_transfers.to_warehouse,fw.title as fwarehouse,tw.title as twarehouse,u.username');
        $this->db->from("tb_transfers");
        $this->db->join("geopos_warehouse fw","tb_transfers.from_warehouse=fw.id","LEFT");
        $this->db->join("geopos_warehouse tw","tb_transfers.to_warehouse=tw.id","LEFT");
        $this->db->join("geopos_users u","tb_transfers.eid=u.id","LEFT");
        $this->db->where('tb_transfers.id', $id);
        return $this->db->get()->row_array();
    }
    public function get_transfer_detail($id=0)
    {
        $this->db->select('i.*,p.product_name,s.unique_id');
        $this->db->from("tb_transfer_items i");
        $this->db->join("geopos_products p","i.pid=p.pid","LEFT");
        $this->db->join("tb_stock s","i.stock_id=s.id","LEFT");
        $this->db->where('i.tid', $id);
        return $this->db->get()->result_array();
    }
    
    //adjustment
    
    public function adjust($from_warehouse, $transferdate, $notes, $items, $pid, $psid, $unique_id, $qty, $pro_name)
    {
        
        $this->db->select('title');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $from_warehouse);
        $query = $this->db->get();
        $from_warehouse_name = $query->row_array()['title'];

        $data = array(
            'adjustdate' => datefordatabase($transferdate), 
            'notes' => $notes,
            'eid' => $this->aauth->get_user()->id, 
            'items' => $items, 
            'status' => "success", 
            'from_warehouse' => $from_warehouse, 
            'loc' => $this->aauth->get_user()->loc
        );
        if ($this->db->insert('tb_adjustments', $data)) {
            $transfer_id = $this->db->insert_id();
            $prodindex = 0;
            $productlist = array();
            foreach ($pid as $key => $value) {
                $this->db->set('qty', "qty-$qty[$key]", FALSE);
                $this->db->where('id', $psid[$key]);
                $this->db->update('tb_stock');
                
                $this->movers(1, $pid[$key], $qty[$key], 0, 'Stock Adjust W- ' . $from_warehouse);
                $this->aauth->applog("[Product Stock Adjustment] $pro_name[$key] Qty -$qty[$key] From $from_warehouse_name", $this->aauth->get_user()->username);
                
                $data_transfer = array(
                    'tid' => $transfer_id,
                    'pid' => $pid[$key],
                    'qty' => numberClean($qty[$key]),
                    'stock_id' => $psid[$key],
                    'unique_id' => $unique_id[$key],
                    'warehouse_id' => $from_warehouse
                );

                $productlist[$prodindex] = $data_transfer;
                $prodindex++;
            }
            $this->db->insert_batch('tb_adjustment_items', $productlist);
        }
        echo json_encode(array('status' => 'Redirect', 'message' => base_url("products/stock_adjustment_list")));
    }
    
    public function reverse_adjustment($tid)
    {
        $this->db->select('from_warehouse');
        $this->db->from('tb_adjustments');
        $this->db->where('id', $tid);
        $query = $this->db->get();
        $from_warehouse_name = $query->row_array()['from_warehouse'];
        
        $this->db->select('title');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $from_warehouse_name);
        $query = $this->db->get();
        $from_warehouse_name = $query->row_array()['title'];
        
        $this->db->select('i.*,p.product_name');
        $this->db->from('tb_adjustment_items i');
        $this->db->join('geopos_products p','i.pid=p.pid','left');
        $this->db->where('tid', $tid);
        $query = $this->db->get();
        $list = $query->result();
        foreach($list as $l){
            $this->db->set('qty', "qty+($l->qty)", FALSE);
            $this->db->where('id', $l->stock_id);
            $this->db->update('tb_stock');

            $this->movers(1, $l->pid, $l->qty, 0, 'Stock Adjust Reverse W- ' . $from_warehouse_name);
            $this->aauth->applog("[Product Stock Adjustment Reverse] $l->product_name Qty +$l->qty To $from_warehouse_name", $this->aauth->get_user()->username);

        }
        
        $update_data = array(
            "status" => "canceled",
            "eid" => $this->aauth->get_user()->id
        );
        $this->db->where('id', $tid);
        $this->db->update('tb_adjustments',$update_data);
    }
    
    private function _get_adjustment_datatables_query($opt = '')
    {
        $this->db->select('tb_adjustments.id,tb_adjustments.adjustdate,tb_adjustments.notes,tb_adjustments.items,tb_adjustments.from_warehouse,fw.title as fwarehouse,tb_adjustments.status,tb_adjustments.total_cost');
        $this->db->from("tb_adjustments");
        $this->db->join("geopos_warehouse fw","tb_adjustments.from_warehouse=fw.id","LEFT");
        if ($opt) {
            $this->db->where('tb_adjustments.eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('tb_adjustments.loc', $this->aauth->get_user()->loc);
        }
        elseif(!BDATA) { $this->db->where('tb_adjustments.loc', 0); }
        if ($this->input->post('start_date') && $this->input->post('end_date')) // if datatable send POST for search
        {
            $this->db->where('DATE(tb_adjustments.adjustdate) >=', datefordatabase($this->input->post('start_date')));
            $this->db->where('DATE(tb_adjustments.adjustdate) <=', datefordatabase($this->input->post('end_date')));
        }
        $i = 0;

        foreach ($this->column_search as $item) // loop column
        {
            if ($this->input->post('search')['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->input->post('search')['value']);
                } else {
                    $this->db->or_like($item, $this->input->post('search')['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
    }

    function get_adjustment_datatables($opt = '')
    {
        $this->_get_adjustment_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_all_adjustment($opt = '')
    {
        $this->db->select('tb_adjustments.id');
        $this->db->from('tb_adjustments');
        if ($opt) {
            $this->db->where('tb_adjustments.eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('tb_adjustments.loc', $this->aauth->get_user()->loc);
        }  elseif(!BDATA) { $this->db->where('tb_adjustments.loc', 0); }
        return $this->db->count_all_results();
    }
    
    public function get_adjustment_master($id=0)
    {
        $this->db->select('tb_adjustments.id,tb_adjustments.adjustdate,tb_adjustments.notes,tb_adjustments.items,tb_adjustments.from_warehouse,tb_adjustments.total_cost,tb_adjustments.status,fw.title as fwarehouse,u.username');
        $this->db->from("tb_adjustments");
        $this->db->join("geopos_warehouse fw","tb_adjustments.from_warehouse=fw.id","LEFT");
        $this->db->join("geopos_users u","tb_adjustments.eid=u.id","LEFT");
        $this->db->where('tb_adjustments.id', $id);
        return $this->db->get()->row_array();
    }
    public function get_adjustment_detail($id=0)
    {
        $this->db->select('i.*,p.product_name');
        $this->db->from("tb_adjustment_items i");
        $this->db->join("geopos_products p","i.pid=p.pid","LEFT");
        $this->db->join("tb_stock s","i.pid=s.product_id and i.warehouse_id=s.warehouse_id and i.unique_id=s.unique_id","LEFT");
        $this->db->where('i.tid', $id);
        return $this->db->get()->result_array();
    }
    
    //end adjustment
    
    

    public function meta_delete($name)
    {
        if (@unlink(FCPATH . 'userfiles/product/' . $name)) {
            return true;
        }
    }

    public function valid_warehouse($warehouse)
    {
        $this->db->select('id,loc');
        $this->db->from('geopos_warehouse');
        $this->db->where('id', $warehouse);
        $query = $this->db->get();
        $row = $query->row_array();
        return $row;
    }


    public function movers($type = 0, $rid1 = 0, $rid2 = 0, $rid3 = 0, $note = '')
    {
        $data = array(
            'd_type' => $type,
            'rid1' => $rid1,
            'rid2' => $rid2,
            'rid3' => $rid3,
            'note' => $note
        );
        $this->db->insert('geopos_movers', $data);
    }

}