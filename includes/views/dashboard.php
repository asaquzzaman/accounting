<?php 
$open_invoice     = erp_ac_get_open_invoice();
$overdue_income   = erp_ac_db_get_overdue_income();
$currency_symbole = erp_ac_get_currency_symbole();
$total_previous   = erp_ac_get_paid_within_month();           

?>
<div class="wrap erp-account-dashboard">

    <h2><?php _e( 'Accounting', 'erp-accounting' ); ?></h2>

    <div class="module income">
        <div class="header">
            <div class="title">Income</div>
            <div class="text">Last 365 Days</div>
        </div>

        <div class="module-content">
            <div class="chart-container">
                <div class="open-container">
                    <div class="open-area">
                        <div class="top-bar"></div>
                        <div class="bar-text">
                            <div class="price"><?php echo $currency_symbole; ?><?php echo $open_invoice; ?></div>
                            <div class="text">Open Invoices</div>
                        </div>
                    </div>

                    <div class="due-area">
                        <div class="top-bar"></div>
                        <div class="bar-text">
                            <div class="price"><?php echo $currency_symbole; ?><?php echo $overdue_income; ?></div>
                            <div class="text">Overdue</div>
                        </div>
                    </div>
                </div>

                <div class="paid-container">
                    <div class="top-bar"></div>
                    <div class="bar-text">
                        <div class="price"><?php echo $currency_symbole; ?><?php echo $total_previous; ?></div>
                        <div class="text">Paid last 30 days</div>
                    </div>
                </div>
            </div><!-- .chart-container -->
        </div><!-- .module-content -->
    </div><!-- .income -->

    <div class="module expense">
        <div class="header">
            <div class="title">Expenses</div>
        </div>

        <div class="module-content">
            <div class="expense-value">
                <div class="price"><?php echo $currency_symbole; ?>0</div>
                <div class="text">Last 30 days</div>
            </div>

            <div class="expense-cat-chart">
                Category Chart here
            </div>
        </div>
    </div>
</div>