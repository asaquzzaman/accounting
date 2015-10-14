<div class="wrap erp-accounting chart-of-accounts">

    <h2><?php _e( 'Chart of Accounts', 'erp-accounting' ); ?> <a href="<?php echo admin_url( 'admin.php?page=erp-accounting-charts&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'erp-accounting' ); ?></a></h2>

    <?php
    if ( isset( $_GET['msg'] ) ) {
        switch ( $_GET['msg'] ) {
            case 'update':
                erp_html_show_notice( __( 'Account has been updated!', 'erp-accounting' ) );
                break;

            case 'new':
                erp_html_show_notice( __( 'New account has been added!', 'erp-accounting' ) );
                break;
        }
    }

    $charts     = [];
    $all_charts = erp_ac_get_all_chart( [ 'number' => 200 ]);
    $edit_url   = admin_url( 'admin.php?page=erp-accounting-charts&action=edit&id=' );

    foreach ($all_charts as $chart) {
        $charts[ $chart->class_id ][] = $chart;
    }
    ?>

    <div class="metabox-holder">
        <div class="postbox-container">
            <div class="meta-box-sortables">
                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'Income', 'erp-accounting' ); ?></h3>

                    <div class="inside">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Name', 'erp-accounting' ); ?></th>
                                    <th class="col-balance"><?php _e( 'Balance', 'erp-accounting' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $charts['4'] as $income ) { ?>
                                <tr>
                                    <td><a href="<?php echo $edit_url . $income->id; ?>"><?php echo esc_html( $income->name ); ?></a></td>
                                    <td class="col-balance">0</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'Expenses', 'erp-accounting' ); ?></h3>

                    <div class="inside">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Name', 'erp-accounting' ); ?></th>
                                    <th class="col-balance"><?php _e( 'Balance', 'erp-accounting' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $charts['3'] as $expense ) { ?>
                                <tr>
                                    <td><a href="<?php echo $edit_url . $expense->id; ?>"><?php echo esc_html( $expense->name ); ?></a></td>
                                    <td class="col-balance">0</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="postbox-container">
            <div class="meta-box-sortables">
                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'Assets', 'erp-accounting' ); ?></h3>

                    <div class="inside">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Name', 'erp-accounting' ); ?></th>
                                    <th class="col-balance"><?php _e( 'Balance', 'erp-accounting' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $charts['1'] as $asset ) { ?>
                                <tr>
                                    <td><a href="<?php echo $edit_url . $asset->id; ?>"><?php echo esc_html( $asset->name ); ?></a></td>
                                    <td class="col-balance">0</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'Liability', 'erp-accounting' ); ?></h3>

                    <div class="inside">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php _e( 'Name', 'erp-accounting' ); ?></th>
                                    <th class="col-balance"><?php _e( 'Balance', 'erp-accounting' ); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach( $charts['2'] as $liability ) { ?>
                                <tr>
                                    <td><a href="<?php echo $edit_url . $liability->id; ?>"><?php echo esc_html( $liability->name ); ?></a></td>
                                    <td class="col-balance">0</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="postbox">
                    <h3 class="hndle"><?php _e( 'Equity', 'erp-accounting' ); ?></h3>

                    <div class="inside"></div>
                </div>
            </div>
        </div>
    </div>

</div>