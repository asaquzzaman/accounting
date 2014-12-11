<div class="wrap">

<h2>Chart of Accounts <a href="#" class="add-new-h2">Add New</a></h2>

<?php

$rows = array(
    array( 'Accounts Receivable', 'Accounts receivable (A/R)',   '-200.00' ),
    array( 'Prepaid Expenses', 'Other Current Assets','0.00' ),
    array( 'Uncategorized Asset', 'Other Current Assets','0.00' ),
    array( 'Undeposited Funds', 'Other Current Assets','200.00' ),
    array( 'Accounts Payable', 'Accounts payable (A/P)','0.00' ),
    array( 'Sales Tax Payable', 'Other Current Liabilities','0.00' ),
    array( 'Opening Balance Equity', 'Equity','0.00' ),
    array( 'Retained Earnings', 'Equity' ),
    array( 'Discounts', 'Income' ),
    array( 'Discounts Given', 'Income' ),
    array( 'Gross Receipts', 'Income' ),
    array( 'Professional Service', 'Income' ),
    array( 'Refunds-Allowances', 'Income' ),
    array( 'Sales', 'Income' ),
    array( 'Services', 'Income' ),
    array( 'Shipping Income', 'Income' ),
    array( 'Shipping, Delivery Income', 'Income' ),
    array( 'Training', 'Income' ),
    array( 'Unapplied Cash Payment Income', 'Income' ),
    array( 'Uncategorized Income', 'Income' ),
    array( 'Cost of labor - COS', 'Cost of Goods Sold' ),
    array( 'Freight & delivery - COS', 'Cost of Goods Sold' ),
    array( 'Other Costs - COS', 'Cost of Goods Sold' ),
    array( 'Purchases - COS', 'Cost of Goods Sold' ),
    array( 'Subcontractors - COS', 'Cost of Goods Sold' ),
    array( 'Supplies & Materials - COGS', 'Cost of Goods Sold' ),
    array( 'Advertising', 'Expenses' ),
    array( 'Bad Debts', 'Expenses' ),
    array( 'Bank Charges', 'Expenses' ),
    array( 'Commissions & fees', 'Expenses' ),
    array( 'Disposal Fees', 'Expenses' ),
    array( 'Dues & Subscriptions', 'Expenses' ),
    array( 'Freight & Delivery', 'Expenses' ),
    array( 'Insurance', 'Expenses' ),
    array( 'Insurance - Disability', 'Expenses' ),
    array( 'Insurance - Liability', 'Expenses' ),
    array( 'Interest Expense', 'Expenses' ),
    array( 'Job Materials', 'Expenses' ),
    array( 'Legal & Professional Fees', 'Expenses' ),
    array( 'Meals and Entertainment', 'Expenses' ),
    array( 'Office Expenses', 'Expenses' ),
    array( 'Other General and Admin Expenses', 'Expenses' ),
    array( 'Promotional', 'Expenses' ),
    array( 'Rent or Lease', 'Expenses' ),
    array( 'Repair & Maintenance', 'Expenses' ),
    array( 'Shipping and delivery expense', 'Expenses' ),
    array( 'Stationery & Printing', 'Expenses' ),
    array( 'Subcontractors', 'Expenses' ),
    array( 'Supplies', 'Expenses' ),
    array( 'Taxes & Licenses', 'Expenses' ),
    array( 'Tools', 'Expenses' ),
    array( 'Travel', 'Expenses' ),
    array( 'Travel Meals', 'Expenses' ),
    array( 'Travel-1', 'Expenses' ),
    array( 'Uncategorized Expense', 'Expenses' ),
    array( 'Utilities', 'Expenses' ),
    array( 'Interest Earned', 'Other Income' ),
    array( 'Other Ordinary Income', 'Other Income' ),
    array( 'Other Portfolio Income', 'Other Income' ),
    array( 'Miscellaneous', 'Other Expense' ),
    array( 'Penalties & Settlements', 'Other Expense' )
);
?>

    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
            <select name="action" id="bulk-action-selector-top">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" name="" id="doaction" class="button action" value="Apply">
        </div>
    </div>

    <table class="wp-list-table widefat fixed">
        <thead>
            <tr>
                <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                    <input id="cb-select-all-1" type="checkbox">
                </th>
                <th><?php _e( 'Name', 'accounting' ); ?></th>
                <th><?php _e( 'Type', 'accounting' ); ?></th>
                <th><?php _e( 'Balance', 'accounting' ); ?></th>
            </tr>
        </thead>

        <tbody id="the-list">
            <?php foreach( $rows as $num => $row ) { ?>
            <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                <th scope="row" class="check-column">
                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
                </th>
                <td>
                    <strong><a href="#"><?php echo $row[0]; ?></a></strong>

                    <div class="row-actions">
                        <span class="edit"><a href="#" title="Edit this item">Edit</a> | </span>
                        <span class="trash"><a class="submitdelete" title="Delete this item" href="#">Delete</a></span>
                    </div>
                </td>
                <td><?php echo $row[1]; ?></td>
                <td><?php echo isset( $row[2] ) ? $row[2] : ''; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
            <select name="action" id="bulk-action-selector-top">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" name="" id="doaction" class="button action" value="Apply">
        </div>
    </div>

</div>