<?php
// We change the headers of the page so that the browser will know what sort of file is dealing with. Also, we will tell the browser it has to treat the file as an attachment which cannot be cached.
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=GramCar_services_expense.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1"> 	
<tr>
       		<th>Date</th>
            <th>Payee</th>
            <th>Voucher No</th>
            <th align="right">Expense (BDT)</th>
            <th>Expense Purpose</th>
        </tr>  
        <?php
		if(isset($all_expense))
		{
		foreach ($all_expense as $expense) :
		
		?>
        <tr>
       		<td><?=$expense->expense_date?></td>
            <td><?=$expense->expense_payee?></td>
            <td><?=$expense->expense_voucher_no?></td>
            <td align="right"><?=$expense->expense_amount?></td>
            <td><?=$expense->expense_purpose?></td>
        </tr>
        <?php
		endforeach;
		}
		?>
       </table>