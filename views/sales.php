<div class="wrap">

    <h2>
        <?php _e( 'Sales Transactions', 'accounting' ); ?>
        <a href="#" class="add-new-h2">New Payment</a>
        <a href="#" class="add-new-h2">New Invoice</a>
        <a href="#" class="add-new-h2">New Quote</a>
        <a href="#" class="add-new-h2">New Credit Note</a>
    </h2>

<?php
$invoices =
array (
  0 =>
  array (
    'no' => 'INV-0028',
    'ref' => 'GB1-White',
    'contact' => 'Bayside Club',
    'date' => '9-Dec-14',
    'due_date' => '28-Dec-14',
    'paid' => '0',
    'due' => '234',
    'status' => 'Awaiting Payment',
    'sent' => 'Unsent',
  ),
  1 =>
  array (
    'no' => 'INV-0028',
    'ref' => 'GB1-White',
    'contact' => 'Bayside Club',
    'date' => '9-Dec-14',
    'due_date' => '28-Dec-14',
    'paid' => '0',
    'due' => '234',
    'status' => 'Awaiting Payment',
    'sent' => 'Unsent',
  ),
  2 =>
  array (
    'no' => 'INV-0027',
    'ref' => 'Ref MK815',
    'contact' => 'Marine Systems',
    'date' => '9-Dec-14',
    'due_date' => '15-Dec-14',
    'paid' => '0',
    'due' => '396',
    'status' => 'Awaiting Payment',
    'sent' => 'Unsent',
  ),
  3 =>
  array (
    'no' => 'INV-0026',
    'ref' => '',
    'contact' => 'Basket Case',
    'date' => '9-Dec-14',
    'due_date' => '19-Dec-14',
    'paid' => '0',
    'due' => '914.55',
    'status' => 'Awaiting Payment',
    'sent' => 'Unsent',
  ),
  4 =>
  array (
    'no' => 'INV-0026',
    'ref' => '',
    'contact' => 'Basket Case',
    'date' => '9-Dec-14',
    'due_date' => '19-Dec-14',
    'paid' => '0',
    'due' => '914.55',
    'status' => 'Awaiting Payment',
    'sent' => 'Unsent',
  ),
  5 =>
  array (
    'no' => 'INV-0030',
    'ref' => 'Monthly support',
    'contact' => 'Rex Media Group',
    'date' => '8-Dec-14',
    'due_date' => '23-Dec-14',
    'paid' => '0',
    'due' => '550',
    'status' => 'Draft',
    'sent' => 'Unsent',
  ),
  6 =>
  array (
    'no' => 'INV-0029',
    'ref' => 'Monthly support',
    'contact' => 'Hamilton Smith Ltd',
    'date' => '8-Dec-14',
    'due_date' => '23-Dec-14',
    'paid' => '0',
    'due' => '550',
    'status' => 'Draft',
    'sent' => 'Unsent',
  ),
  7 =>
  array (
    'no' => 'INV-0024',
    'ref' => 'P/O 9711',
    'contact' => 'City Limousines',
    'date' => '4-Dec-14',
    'due_date' => '19-Dec-14',
    'paid' => '0',
    'due' => '703.63',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  8 =>
  array (
    'no' => 'CN-0015',
    'ref' => 'Monthly Support',
    'contact' => 'Hamilton Smith Ltd',
    'date' => '19-Nov-14',
    'due_date' => '',
    'paid' => '0',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  9 =>
  array (
    'no' => 'INV-0025',
    'ref' => 'P/O CRM08-12',
    'contact' => 'Ridgeway University',
    'date' => '18-Nov-14',
    'due_date' => '9-Dec-14',
    'paid' => '0',
    'due' => '6187.5',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  10 =>
  array (
    'no' => 'CN-0023',
    'ref' => 'Yr Ref W08-143',
    'contact' => 'DIISR - Small Business Services',
    'date' => '14-Nov-14',
    'due_date' => '',
    'paid' => '0',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  11 =>
  array (
    'no' => 'INV-0022',
    'ref' => 'Yr Ref W08-143',
    'contact' => 'DIISR - Small Business Services',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '216.5',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  12 =>
  array (
    'no' => 'INV-0022',
    'ref' => 'Yr Ref W08-143',
    'contact' => 'DIISR - Small Business Services',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '216.5',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  13 =>
  array (
    'no' => 'INV-0021',
    'ref' => 'Monthly Support',
    'contact' => 'Rex Media Group',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  14 =>
  array (
    'no' => 'INV-0020',
    'ref' => 'Monthly Support',
    'contact' => 'Port & Philip Freight',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  15 =>
  array (
    'no' => 'INV-0019',
    'ref' => 'Monthly Support',
    'contact' => 'Young Bros Transport',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  16 =>
  array (
    'no' => 'INV-0018',
    'ref' => 'Monthly Support',
    'contact' => 'Hamilton Smith Ltd',
    'date' => '9-Nov-14',
    'due_date' => '19-Nov-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  17 =>
  array (
    'no' => 'INV-0016',
    'ref' => 'Yr Ref W08-143',
    'contact' => 'DIISR - Small Business Services',
    'date' => '30-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '568.31',
    'due' => '270.63',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  18 =>
  array (
    'no' => 'INV-0016',
    'ref' => 'Yr Ref W08-143',
    'contact' => 'DIISR - Small Business Services',
    'date' => '30-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '568.31',
    'due' => '270.63',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  19 =>
  array (
    'no' => 'INV-0017',
    'ref' => 'Book',
    'contact' => 'City Limousines',
    'date' => '28-Oct-14',
    'due_date' => '7-Nov-14',
    'paid' => '0',
    'due' => '21.7',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  20 =>
  array (
    'no' => 'INV-0013',
    'ref' => 'Training',
    'contact' => 'Boom FM',
    'date' => '28-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '1082.5',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  21 =>
  array (
    'no' => 'INV-0013',
    'ref' => 'Training',
    'contact' => 'Boom FM',
    'date' => '28-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '1082.5',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  22 =>
  array (
    'no' => 'CN-0014',
    'ref' => 'Training',
    'contact' => 'Boom FM',
    'date' => '28-Oct-14',
    'due_date' => '',
    'paid' => '0',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  23 =>
  array (
    'no' => 'INV-0012',
    'ref' => 'P/O 9711',
    'contact' => 'City Limousines',
    'date' => '25-Oct-14',
    'due_date' => '4-Nov-14',
    'paid' => '0',
    'due' => '216.5',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
  24 =>
  array (
    'no' => 'INV-0011',
    'ref' => 'Portal Proj',
    'contact' => 'Petrie McLoud Watson & Associates',
    'date' => '23-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '1407.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  25 =>
  array (
    'no' => 'INV-0009',
    'ref' => 'P/O CRM08-12',
    'contact' => 'Ridgeway University',
    'date' => '18-Oct-14',
    'due_date' => '9-Nov-14',
    'paid' => '6187.5',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  26 =>
  array (
    'no' => 'INV-0010',
    'ref' => 'Training',
    'contact' => 'Boom FM',
    'date' => '17-Oct-14',
    'due_date' => '30-Oct-14',
    'paid' => '0',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  27 =>
  array (
    'no' => 'INV-0007',
    'ref' => 'Workshop',
    'contact' => 'City Agency',
    'date' => '12-Oct-14',
    'due_date' => '23-Oct-14',
    'paid' => '593.23',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  28 =>
  array (
    'no' => 'INV-0007',
    'ref' => 'Workshop',
    'contact' => 'City Agency',
    'date' => '12-Oct-14',
    'due_date' => '23-Oct-14',
    'paid' => '593.23',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  29 =>
  array (
    'no' => 'INV-0008',
    'ref' => 'Training',
    'contact' => 'Bank West',
    'date' => '11-Oct-14',
    'due_date' => '22-Oct-14',
    'paid' => '1299',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  30 =>
  array (
    'no' => 'INV-0008',
    'ref' => 'Training',
    'contact' => 'Bank West',
    'date' => '11-Oct-14',
    'due_date' => '22-Oct-14',
    'paid' => '1299',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  31 =>
  array (
    'no' => 'INV-0008',
    'ref' => 'Training',
    'contact' => 'Bank West',
    'date' => '11-Oct-14',
    'due_date' => '22-Oct-14',
    'paid' => '1299',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  32 =>
  array (
    'no' => 'INV-0005',
    'ref' => 'Monthly Support',
    'contact' => 'Hamilton Smith Ltd',
    'date' => '10-Oct-14',
    'due_date' => '20-Oct-14',
    'paid' => '0',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  33 =>
  array (
    'no' => 'INV-0004',
    'ref' => 'Monthly Support',
    'contact' => 'Rex Media Group',
    'date' => '9-Oct-14',
    'due_date' => '20-Oct-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  34 =>
  array (
    'no' => 'INV-0003',
    'ref' => 'Monthly Support',
    'contact' => 'Port & Philip Freight',
    'date' => '9-Oct-14',
    'due_date' => '20-Oct-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  35 =>
  array (
    'no' => 'INV-0002',
    'ref' => 'Monthly Support',
    'contact' => 'Young Bros Transport',
    'date' => '9-Oct-14',
    'due_date' => '20-Oct-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  36 =>
  array (
    'no' => 'INV-0001',
    'ref' => 'Monthly Support',
    'contact' => 'Hamilton Smith Ltd',
    'date' => '9-Oct-14',
    'due_date' => '20-Oct-14',
    'paid' => '541.25',
    'due' => '0',
    'status' => 'Paid',
    'sent' => 'Unsent',
  ),
  37 =>
  array (
    'no' => 'INV-0006',
    'ref' => 'P/O 9711',
    'contact' => 'City Limousines',
    'date' => '8-Oct-14',
    'due_date' => '18-Oct-14',
    'paid' => '0',
    'due' => '250',
    'status' => 'Awaiting Payment',
    'sent' => 'Sent',
  ),
);

?>

    <ul class="subsubsub">
        <li class="all"><a href="#" class="current">All <span class="count">(11)</span></a> |</li>
        <li class="publish"><a href="#">Draft <span class="count">(2)</span></a> |</li>
        <li class="publish"><a href="#">Awaiting Approval <span class="count">(0)</span></a> |</li>
        <li class="publish"><a href="#">Awaiting Payment <span class="count">(9)</span></a> |</li>
        <li class="publish"><a href="#">Paid</a> |</li>
        <li class="publish"><a href="#">Repeating</a></li>
    </ul>

    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
            <select name="action" id="bulk-action-selector-top">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="email">Approve</option>
                <option value="email">Print</option>
                <option value="email">Email</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" name="" id="doaction" class="button action" value="Apply">
        </div>
    </div>

    <table class="wp-list-table widefat fixed invoice-list-table">
        <thead>
            <tr>
                <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                    <input id="cb-select-all-1" type="checkbox">
                </th>
                <th class="col-inv-no"><?php _e( 'Date', 'accounting' ); ?></th>
                <th class="col"><?php _e( 'Type', 'accounting' ); ?></th>
                <th class="col"><?php _e( 'No', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Customer', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Due Date', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Balance', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Total', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Status', 'accounting' ); ?></th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                    <input id="cb-select-all-1" type="checkbox">
                </th>
                <th class="col-inv-no"><?php _e( 'Date', 'accounting' ); ?></th>
                <th class="col"><?php _e( 'Type', 'accounting' ); ?></th>
                <th class="col"><?php _e( 'No', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Customer', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Due Date', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Balance', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Total', 'accounting' ); ?></th>
                <th class="col-"><?php _e( 'Status', 'accounting' ); ?></th>
            </tr>
        </tfoot>

        <tbody id="the-list">
            <?php foreach( $invoices as $num => $row ) { ?>
            <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                <th scope="row" class="check-column">
                    <input id="cb-select-1" type="checkbox" name="post[]" value="1">
                </th>
                <td class="username col-inv-no">
                    <strong><a href="#"><?php echo $row['date']; ?></a></strong>

                    <div class="row-actions">
                        <span class="edit"><a href="#" title="Edit this item">Edit</a> | </span>
                        <span class="trash"><a class="submitdelete" title="Delete this item" href="#">Delete</a></span>
                    </div>
                </td>
                <td class="col-"><?php echo isset( $row['type'] ) ? $row['type'] : 'Payment'; ?></td>
                <td class="col-"><?php echo $row['no']; ?></td>
                <td class="col-">
                    <a href="#"><?php echo $row['contact']; ?></a>
                </td>
                <td class="col-"><?php echo $row['due_date']; ?></td>
                <td class="col-"><?php echo $row['paid']; ?></td>
                <td class="col-"><?php echo $row['due']; ?></td>
                <td class="col-"><?php echo $row['status']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
            <select name="action" id="bulk-action-selector-top">
                <option value="-1" selected="selected">Bulk Actions</option>
                <option value="email">Approve</option>
                <option value="email">Print</option>
                <option value="email">Email</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" name="" id="doaction" class="button action" value="Apply">
        </div>
    </div>

</div>