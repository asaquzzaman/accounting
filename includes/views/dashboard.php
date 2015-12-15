<?php 
$plotdata             = erp_ac_get_expense_plot();
$open_invoice         = erp_ac_get_open_invoice();
$overdue_income       = erp_ac_db_get_overdue_income();
$currency_symbole     = erp_ac_get_currency_symbole();
$total_previous       = erp_ac_get_paid_last_30_days(); 
$expence_last_30_days = erp_ac_get_expenses_last_30_days();   

$amount = [];
$label  = [];
$amounts = [];
$labels  = [];
$ledgers = [];
foreach ( $plotdata as $key => $data ) {
    if ( ! isset(  $data['journals'] ) ) {
        continue;
    } 

    foreach ( $data['journals'] as $jouranal_key => $journal ) {

        $ledgers[$journal['ledger_id']][] = $journal['credit'];
        // $ledger = \WeDevs\ERP\Accounting\Model\Ledger::find( $journal['ledger_id'] );
        // if ( array_key_exists( $journal['ledger_id'], $amount ) ) {

        // }
        // $amount[$journal['ledger_id']] = array( $key, $journal['credit'] );
        // $label[$journal['ledger_id']] = array( $key, $ledger->name );
    }
}    

foreach ( $ledgers as $ledger_id => $ledger_amount ) {
    $amounts[] = array_sum( $ledger_amount );
    $ledger = \WeDevs\ERP\Accounting\Model\Ledger::find( $ledger_id );
    $labels[] = $ledger->name;
}

foreach ( $amounts as $key => $amount_val ) {
    $amount[] = array( $key, $amount_val );
    $label[] = array( $key, $labels[$key] );
}

$amount = json_encode( $amount );
$label  = json_encode( $label );
   //var_dump( $amount, $label  ); die();

?>
    <script type="text/javascript">

        jQuery(document).ready(function ($) {





        //******* 2012 Average Temperature - BAR CHART
        var data = <?php echo $amount; ?>;
        var ticks = <?php echo $label; ?>;

        //var data = a[[0, 11],[1, 15],[2, 25],[3, 24],[4, 13],[5, 18]];
        
       /// console.log( amount, data );
        var dataset = [{ label: "Expenses Last 30 days", data: data, color: "#5482FF" }];
        //var ticks = [[0, "London"], [1, "New York"], [2, "New Delhi"], [3, "Taipei"],[4, "Beijing"], [5, "Sydney"]];
        var options = {
            series: {
                bars: {
                    show: true
                }
            },
            bars: {
                align: "center",
                barWidth: 0.5
            },
            xaxis: {
                axisLabel: "Journals",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 10,
                ticks: ticks
            },
            yaxis: {
                axisLabel: "Expenses",
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial',
                axisLabelPadding: 3,
                tickFormatter: function (v, axis) {
                    return v;
                }
            },
            legend: {
                noColumns: 0,
                labelBoxBorderColor: "#000000",
                position: "nw"
            },
            grid: {
                hoverable: true,
                borderWidth: 2,
                backgroundColor: { colors: ["#ffffff", "#EDF5FF"] }
            }
        };
 







            jQuery.plot($("#flot-placeholder"), dataset, options);
            jQuery("#flot-placeholder").UseTooltip();
        });
 
        function gd(year, month, day) {
            return new Date(year, month, day).getTime();
        }
 
        var previousPoint = null, previousLabel = null;
 
        jQuery.fn.UseTooltip = function () {
            jQuery(this).bind("plothover", function (event, pos, item) {
                if (item) {
                    if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                        previousPoint = item.dataIndex;
                        previousLabel = item.series.label;
                        jQuery("#tooltip").remove();
 
                        var x = item.datapoint[0];
                        var y = item.datapoint[1];
 
                        var color = item.series.color;
 
                        //console.log(item.series.xaxis.ticks[x].label);                
 
                        showTooltip(item.pageX,
                        item.pageY,
                        color,
                        "<strong>" + item.series.label + "</strong><br>" + item.series.xaxis.ticks[x].label + " : <strong>" + y + "</strong> °C");
                    }
                } else {
                    jQuery("#tooltip").remove();
                    previousPoint = null;
                }
            });
        };
 
        function showTooltip(x, y, color, contents) {
            jQuery('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 120,
                border: '2px solid ' + color,
                padding: '3px',
                'font-size': '9px',
                'border-radius': '5px',
                'background-color': '#fff',
                'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                opacity: 0.9
            }).appendTo("body").fadeIn(200);
        }
    </script>
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
                <div class="price"><?php echo $currency_symbole; ?><?php echo $expence_last_30_days; ?></div>
                <div class="text">Last 30 days</div>
            </div>

            <div class="expense-cat-chart">
                <div style="width:450px;height:300px;text-align:center;margin:10px">        
                    <div id="flot-placeholder" style="width:100%;height:100%;"></div>        
                </div>
            </div>
        </div>
    </div>
</div>

 







