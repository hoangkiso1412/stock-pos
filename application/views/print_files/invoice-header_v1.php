<table>
    <tr>
        <td class="myco">
            <img1 src="<?php $loc = location($invoice['loc']);
            echo FCPATH . 'userfiles/company/' . $loc['logo'] ?>"
                 class="top_logo">
        </td>
        <td>

        </td>
        <td class="myw1 heading">
            <table class="top_sum">
                <tr>
                    <td colspan="2" class="t_center"><center><h2><?= $general['title'] ?></h2></center><br><br></td>
                </tr>
                <tr>
                    <td><?= $general['title'] ?></td>
                    <td><?= $general['prefix'] . ' ' . $formated_invoice ?></td>
                </tr>
                <tr>
                    <td><?= $general['title'] . ' ' . $this->lang->line('Date') ?></td>
                    <td class="right_align"><?php echo dateformat($invoice['invoicedate']) ?></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('Due Date') ?></td>
                    <td class="right_align"><?php echo dateformat($invoice['invoiceduedate']) ?></td>
                </tr>
                <?php if ($invoice['refer']) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('Reference') ?></td>
                        <td class="right_align"><?php echo $invoice['refer'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </td>
    </tr>
</table>
<hr>
<br>