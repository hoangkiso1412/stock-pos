<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo $this->lang->line('Product Adjustment History') ?> <a
                    href="<?php echo base_url('products/stock_adjustment') ?>"
                    class="btn btn-primary btn-sm rounded">
                    <?php echo $this->lang->line('Add new') ?></a></h4>
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
                <div class="row">
                    
                    <div class="col-md-2"><?php echo $this->lang->line('Transfer Date') ?></div>
                    <div class="col-md-2">
                        <input type="text" name="start_date" id="start_date"
                               class="date30 form-control form-control" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="end_date" id="end_date" class="form-control form-control"
                               data-toggle="datepicker" autocomplete="off"/>
                    </div>
                        
                    <div class="col-md-2">
                        <input type="button" name="search" id="search" value="Search" class="btn btn-info btn"/>
                    </div>
                        
                </div>
                <hr>
                <table id="table_data" class="table table-striped table-bordered zero-configuration ">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('No') ?></th>
                            <th><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Total Products') ?></th>
                            <th><?php echo $this->lang->line('Warehouse') ?></th>
                            <th><?php echo $this->lang->line('Amount') ?></th>
                            <th><?php echo $this->lang->line('Status') ?></th>
                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                        
                    <tfoot>
                        <tr>
                            <th><?php echo $this->lang->line('No') ?></th>
                            <th><?php echo $this->lang->line('Date') ?></th>
                            <th><?php echo $this->lang->line('Total Products') ?></th>
                            <th><?php echo $this->lang->line('Warehouse') ?></th>
                            <th><?php echo $this->lang->line('Amount') ?></th>
                            <th><?php echo $this->lang->line('Status') ?></th>
                            <th class="no-sort"><?php echo $this->lang->line('Settings') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div id="view_model" class="modal  fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php echo $this->lang->line('View') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="view_object">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="view-object-id" value="">
                        <input type="hidden" id="view-action-url" value="products/view_adjustment">
                            
                        <button type="button" data-dismiss="modal"
                                class="btn"><?php echo $this->lang->line('Close') ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_model" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title"><?php echo $this->lang->line('Reverse Adjustment') ?></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo $this->lang->line('delete this adjustment') ?> ?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="object-id" value="">
                        <input type="hidden" id="action-url" value="<?php echo base_url('products/reverse_adjustment')?>">
                        <button type="button" data-dismiss="modal" class="btn btn-primary"
                                id="delete-adjust"><?php echo $this->lang->line('Delete') ?></button>
                        <button type="button" data-dismiss="modal"
                                class="btn"><?php echo $this->lang->line('Cancel') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
<script type="text/javascript">
    $(document).ready(function () {
        draw_data($('#start_date').val(),$('#end_date').val());
        
        function draw_data(start_date = '', end_date = '') {
            $('#table_data').DataTable({
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                <?php datatable_lang();?>
                responsive: true,
                'order': [],
                'ajax': {
                    'url': "<?php echo site_url('products/ajax_adjustment_list')?>",
                    'type': 'POST',
                    'data': {
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                        start_date: start_date,
                        end_date: end_date
                    }
                },
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false
                    }
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    }
                ]
            });
        }
                        
        $('#search').click(function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#table_data').DataTable().destroy();
                draw_data(start_date, end_date);
            } else {
                alert("Date range is Required");
            }
        });
        $(document).on('click', ".view-object", function (e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $('#view_model').modal({backdrop: 'static', keyboard: false});

            $.ajax({
                url: url,
                data: 'id=' + 0 + '&' + crsf_token + '=' + crsf_hash,
                type: 'POST',
                dataType: 'html',
                success: function (data) {
                    $('#view_object').html(data);
                }
            });
        
        });
        
        $(document).on('click', ".delete-object", function (e) {
            e.preventDefault();
            var url = $(this).attr('data-object-id-1');
            $("#object-id").val(url);
        
        });
        
        $("#delete-adjust").on("click",function(e){
            var url = $("#action-url").val();
            var id = $("#object-id").val();
            window.location.href = url + '/' + id;
        });
    });
</script>