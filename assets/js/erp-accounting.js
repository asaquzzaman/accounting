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