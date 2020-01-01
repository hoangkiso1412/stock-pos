<h5><?php //echo $product['product_name'] . ' (' . $product['title'] . ')'; ?></h5>

<table class="table">
    <tr>
        <th><?php echo $this->lang->line('Warehouse') ?></th>
        <th><?php echo $this->lang->line('Unique ID') ?></th>
        <th><?php echo $this->lang->line('Stock') ?></th>
    </tr>
    <?php foreach($product as $p){
    ?>
    <tr>
        <td><?php echo $p['title'] ?></td>
        <td><?php echo $p['unique_id'] ?></td>
        <td><?php echo $p['product_qty'] ?></td>
    </tr>
    <?php
    }
    ?>
</table>
<hr>

