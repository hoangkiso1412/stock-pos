<h5><?php echo $this->lang->line('From')." ".$this->lang->line('Warehouse').": <b>".$transfer["fwarehouse"]."</b>" ?></h5>
<h5><?php echo $this->lang->line('To')." ".$this->lang->line('Warehouse').": <b>".$transfer["twarehouse"]."</b>" ?></h5>
<h5><?php echo $this->lang->line('Employee').": <b>".$transfer["username"]."</b>" ?></h5>

<table class="table">
    <tr>
        <th><?php echo $this->lang->line('Item Name') ?></th>
        <th><?php echo $this->lang->line('Unique ID') ?></th>
        <th><?php echo $this->lang->line('Qty') ?></th>
    </tr>
    <?php foreach($transfer_item as $p){
    ?>
    <tr>
        <td><?php echo $p['product_name'] ?></td>
        <td><?php echo $p['unique_id'] ?></td>
        <td><?php echo $p['qty'] ?></td>
    </tr>
    <?php
    }
    ?>
</table>
<hr>

