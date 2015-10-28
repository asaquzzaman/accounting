<div class="account-chart">

    <h3><?php echo $title; ?></h3>

    <table class="table widefat striped ac-chart-table">
        <thead>
            <tr>
                <th class="col-code"><?php _e( 'Code', 'erp-accounting' ); ?></th>
                <th class="col-name"><?php _e( 'Name', 'erp-accounting' ); ?></th>
                <th class="col-type"><?php _e( 'Type', 'erp-accounting' ); ?></th>
                <th class="col-balance"><?php _e( 'Balance', 'erp-accounting' ); ?></th>
                <th class="col-action"><?php _e( 'Actions', 'erp-accounting' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( $charts ) {
                foreach( $charts as $chart ) {
                    ?>
                    <tr>
                        <td class="col-code"><?php echo $chart->code; ?></td>
                        <td class="col-name"><?php echo esc_html( $chart->name ); ?></td>
                        <td class="col-type"><?php echo $chart->type_name; ?></td>
                        <td class="col-balance">0</td>
                        <td class="col-action">
                            <?php if ( $chart->system ) {
                                _e( 'System Account', 'erp-accounting' );
                            } else {
                                ?>
                                <a href="<?php echo $edit_url . $chart->id; ?>"><?php _e( 'Edit', 'erp-accounting' ); ?></a>
                                <a href="#"><?php _e( 'Delete', 'erp-accounting' ); ?></a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5"><?php _e( 'No chart found!', 'erp-accounting' ); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>