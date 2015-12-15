(function($) {

    var ERP_Accounting = {

        initialize: function() {
            $( 'table.erp-ac-transaction-table' ).on( 'click', '.add-line', this.table.addRow );
            $( 'table.erp-ac-transaction-table' ).on( 'click', '.remove-line', this.table.removeRow );

            // payment voucher
            $( 'table.erp-ac-transaction-table.payment-voucher-table' ).on( 'click', '.remove-line', this.paymentVoucher.onChange );
            $( 'table.erp-ac-transaction-table.payment-voucher-table' ).on( 'change', 'input.line_qty, input.line_price, input.line_dis', this.paymentVoucher.onChange );

            // journal entry
            $( 'table.erp-ac-transaction-table.journal-table' ).on( 'click', '.remove-line', this.journal.onChange );
            $( 'table.erp-ac-transaction-table.journal-table' ).on( 'change', 'input.line_debit, input.line_credit', this.journal.onChange );

            // chart of accounts
            $( 'form#erp-ac-accounts-form').on( 'change', 'input#code', this.accounts.checkCode );

            //plot
            //this.plot.init();
        },

        plot: {
            init: function() {

                if ( ! jQuery.fn.plot ) {
                    return;
                };

                var bar_customised_1 = [[1388534400000, 120], [1388534400100, 70]];
               // var bar_customised_2 = [[1388534400000, 90], [1391212800000, 60], [1393632000000, 30], [1396310400000, 73], [1398902400000, 30]];
               // var bar_customised_3 = [[1388534400000, 80], [1391212800000, 40], [1393632000000, 47], [1396310400000, 22], [1398902400000, 24]];
             
                var data = [
                    { label: "Series 1", data: bar_customised_1 },
                  //  { label: "Series 2", data: bar_customised_2 },
                  //  { label: "Series 3", data: bar_customised_3 }
                ];
             
                $.plot($("#placeholder"), data, {
                    series: {
                        bars: {
                            show: true,
                            barWidth: 12*24*60*60*350,
                            lineWidth: 0,
                            order: 1,
                            fillColor: {
                                colors: [{
                                    opacity: 1
                                }, {
                                    opacity: 0.7
                                }]
                            }
                        }
                    },
                    xaxis: {
                        mode: "time",
                        min: 1387497600000,
                        max: 1400112000000,
                        tickLength: 0,
                        tickSize: [1, "month"],
                        axisLabel: 'Month',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 13,
                        axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                        axisLabelPadding: 15
                    },
                    yaxis: {
                        axisLabel: 'Value',
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 13,
                        axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                        axisLabelPadding: 5
                    },
                    grid: {
                        hoverable: true,
                        borderWidth: 0
                    },
                    legend: {
                        backgroundColor: "#EEE",
                        labelBoxBorderColor: "none"
                    },
                    colors: ["#AA4643", "#89A54E", "#4572A7"]
                    
                });

                var previous_point = null;
                var previous_label = null;
             
                $("#placeholder").on("plothover", function (event, pos, item) {
                    if (item) {
                        if ((previous_point != item.dataIndex) || (previous_label != item.series.label)) {
                            previous_point = item.dataIndex;
                            previous_label = item.series.label;
             
                            $("#bar_tooltip").remove();
             
                            var x = ERP_Accounting.plot.getMonthName(item.series.data[item.dataIndex][0]),
                                y = item.datapoint[1],
                                z = item.series.color;
             
                            ERP_Accounting.plot.showTooltip(item.pageX, item.pageY,
                                "<div style='text-align: center;'><b>" + item.series.label + "</b><br />" + x + ": " + y + "</div>",
                                z);
                        }
                    } else {
                        $("#bar_tooltip").remove();
                        previous_point = null;
                        previous_label = null;
                    }
                });    
            },

            showTooltip: function( x, y, contents, z ) {
                $('<div id="bar_tooltip">' + contents + '</div>').css({
                    top: y - 45,
                    left: x - 28,
                    'border-color': z,
                }).appendTo("body").fadeIn();
            },

            getMonthName: function( month_timestamp ) {
                var month_date = new Date(month_timestamp);
                var month_numeric = month_date.getMonth();
                var month_array = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                var month_string = month_array[month_numeric];

                return month_string;
            },

        },
         
        /**
         * Table related general functions
         *
         * @type {Object}
         */
        table: {
            removeRow: function(e) {
                if ( typeof e !== 'undefined' ) {
                    e.preventDefault();
                }

                var self = $(this),
                    table = self.closest( 'table' );

                if ( table.find('tbody > tr').length < 2 ) {
                    return;
                }

                self.closest('tr').remove();
            },

            addRow: function(e) {
                e.preventDefault();

                var self = $(this),
                    table = self.closest( 'table' );

                // destroy the last select2 for proper cloning
                table.find('tbody > tr:last').find('select').select2('destroy');

                var tr = table.find('tbody > tr:last'),
                    clone = tr.clone();

                clone.find('input').val('');

                tr.after( clone );

                // re-initialize selec2
                $('.select2').select2({
                    'theme': 'classic'
                });
            }
        },

        /**
         * Payment voucher
         *
         * @type {Object}
         */
        paymentVoucher: {

            calculate: function() {
                var table = $('table.payment-voucher-table');
                var total = 0.00;

                table.find('tbody > tr').each(function(index, el) {
                    var row = $(el);
                    var qty        = parseInt( row.find('input.line_qty').val() ) || 1;
                    var line_price = parseFloat( row.find('input.line_price').val() ) || 0;
                    var discount   = parseFloat( row.find('input.line_dis').val() ) || 0;

                    var price = qty * line_price;

                    if ( discount > 0 ) {
                        price -= ( price * discount ) / 100;
                    }

                    total += price;
                    row.find('input.line_total').val( price );
                    row.find('input.line_total_disp').val( price.toFixed(2) );

                    // console.log(qty, line_price, discount);
                });

                table.find('tfoot input.price-total').val( total.toFixed(2) );
            },

            onChange: function() {
                ERP_Accounting.paymentVoucher.calculate();
            },
        },

        /**
         * Journal entry
         *
         * @type {Object}
         */
        journal: {
            calculate: function() {
                var table = $('table.journal-table');
                var debit_total = credit_total = 0.00;

                table.find('tbody > tr').each(function(index, el) {
                    var row    = $(el);
                    var debit  = parseFloat( row.find('input.line_debit').val() ) || 0;
                    var credit = parseFloat( row.find('input.line_credit').val() ) || 0;

                    // both are filled
                    if ( debit && credit ) {
                        debit = 0;
                        row.find('input.line_debit').val('0.00');
                    }

                    debit_total += debit;
                    credit_total += credit;
                });

                var diff = debit_total - credit_total;

                table.find('tfoot input.debit-price-total').val( debit_total.toFixed(2) );
                table.find('tfoot input.credit-price-total').val( credit_total.toFixed(2) );

                if ( diff !== 0 ) {
                    table.find('th.col-diff').addClass('invalid').text( diff.toFixed(2) );
                    $( '#submit_erp_ac_journal' ).attr('disabled', 'disabled');

                } else {
                    table.find('th.col-diff').removeClass('invalid').text( diff.toFixed(2) );
                    $( '#submit_erp_ac_journal' ).removeAttr('disabled');
                }

            },

            onChange: function() {
                ERP_Accounting.journal.calculate();
            }
        },

        /**
         * Chart of accounts
         *
         * @type {Object}
         */
        accounts: {

            checkCode: function() {
                var self = $(this);
                var li = self.closest('li');

                wp.ajax.send( {
                    data: {
                        action: 'erp_ac_ledger_check_code',
                        '_wpnonce': ERP_AC.nonce,
                        code: self.val()
                    },
                    success: function(res) {
                        li.removeClass('invalid');
                    },
                    error: function(error) {
                        li.addClass('invalid');
                        alert( 'This code already exists, please try another.' );
                    }
                });
            }
        }
    };

    $(function() {
        ERP_Accounting.initialize();

        $( 'select.erp-ac-customer-search' ).select2({
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                cache: true,
                url: ajaxurl,
                dataType: 'json',
                quietMillis: 250,
                data: function( term ) {
                    return {
                        term: term,
                        action: 'erp_ac_customer_search',
                        _wpnonce: ''
                    };
                },
            }
        });
    });



















    





})(jQuery);