<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5><?php echo $this->lang->line('Stock Transfer') ?></h5>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <div class="message"></div>
            </div>
            <div class="card-body">
                <form method="post" id="data_form" class="form-horizontal">
                    <input type="hidden" name="act" value="add_product">
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label class="col-form-label"
                                   for="from_warehouse"><?php echo $this->lang->line('Transfer From') ?></label>
                            <div class="input-group">
                                <select id="wfrom" name="from_warehouse" class="form-control">
                                    <option value='0'>Select</option>
                                    <?php
                                    foreach ($warehouse as $row) {
                                        $cid = $row['id'];
                                        $title = $row['title'];
                                        echo "<option value='$cid'>$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <label class="col-form-label"
                                   for="product_cat"><?php echo $this->lang->line('Transfer To') ?></label>
                            <div class="input-group">
                                <select name="to_warehouse" class="form-control">
                                    <?php
                                    foreach ($warehouse as $row) {
                                        $cid = $row['id'];
                                        $title = $row['title'];
                                        echo "<option value='$cid'>$title</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <label class="col-form-label"
                                   for="product_cat"><?php echo $this->lang->line('Date') ?></label>
                            <div class="input-group">
                                <input type="text" class="form-control required"
                                       placeholder="Transfer Date" name="transferdate"
                                       data-toggle="datepicker"
                                       autocomplete="false">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="notes" class="col-form-label"><?php echo $this->lang->line('Note') ?></label>
                            <textarea class="form-control" name="notes" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        
                    </div>
                    <div id="saman-row" class="">
                        <table class="table-responsive tfr my_stripe">
                            <thead>
                                <tr class="item_header bg-gradient-directional-blue white">
                                    <th width="40%" class="text-center"><?php echo $this->lang->line('Item Name') ?></th>
                                    <th width="40%" class="text-center"><?php echo $this->lang->line('Unique ID') ?></th>
                                    <th width="" class="text-center"></th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Quantity') ?></th>
                                    <th width="10%" class="text-center"><?php echo $this->lang->line('Action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="product_name[]"
                                               placeholder="<?php echo $this->lang->line('Enter Product name') ?>"
                                               id='productname_transfer-0'>
                                    </td>
                                    <td><input type="text" class="form-control" name="product_unique_id[]"
                                               placeholder="<?php echo $this->lang->line('Enter Unique ID') ?>"
                                               id='productunique_id_transfer-0'>
                                    </td>
                                    <td>
                                        <i id="product_check-0" class='icon-close text-danger'></i>
                                    </td>
                                    <td><input type="text" class="form-control req amnt" name="product_qty[]" id="amount-0"
                                               onkeypress="return isNumber(event)" onkeyup="rowTotal('0'), billUpyog()"
                                               autocomplete="off" value="1" data-key="transfer_qty"><input type="hidden" id="alert-0" value=""
                                               name="alert[]"></td>
                                    <td>
                                        
                                    </td>
                                    <td class="hidden">
                                        <input type="text" class="qtyIn" name="qtyIn[]" id="qtyIn-0">
                                        <input type="text" class="pdIn" name="pid[]" id="pid-0">
                                        <input type="text" class="pdsIn" name="psid[]" id="psid-0">
                                    </td>
                                </tr>
                                <tr class="last-item-row sub_c">
                                    <td class="add-row">
                                        <button type="button" class="btn btn-success" aria-label="Left Align"
                                                id="addproducttransfer">
                                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('Add Row') ?>
                                        </button>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <input type="hidden" value="0" name="counter" id="ganak">
                                        <div class="form-group row">
                                            <input type="submit" id="submit-data" class="btn btn-success"
                                                   value="<?php echo $this->lang->line('Stock Transfer') ?>"
                                                   data-loading-text="Adding...">
                                            <input type="hidden" value="products/stock_transfer" id="action-url">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#products_l").select2();
    $("#wfrom").on('change', function () {
        var tips = $('#wfrom').val();
        $("#products_l").select2({

            tags: [],
            ajax: {
                url: baseurl + 'products/stock_transfer_products?wid=' + tips,
                dataType: 'json',
                type: 'POST',
                quietMillis: 50,
                data: function (product) {

                    return {
                        product: product,
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash

                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.product_name,
                                id: item.pid
                            }
                        })
                    };
                },
            }
        });
    });
</script>

