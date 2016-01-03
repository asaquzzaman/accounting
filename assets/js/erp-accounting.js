(function($) {

    var ERP_Accounting = {

        initialize: function() {
            
            this.incrementField();

            // payment voucher
            

            // journal entry
            $( 'table.erp-ac-transaction-table.journal-table' ).on( 'click', '.remove-line', this.journal.onChange );
            $( 'table.erp-ac-transaction-table.journal-table' ).on( 'change', 'input.line_debit, input.line_credit', this.journal.onChange );

            // chart of accounts
            $( 'form#erp-ac-accounts-form').on( 'change', 'input#code', this.accounts.checkCode );

            $('.erp-ac-form-wrap').on('change', '.erp-ac-payment-receive', this.paymentReceive );
            $('.erp-ac-form-wrap').on( 'keyup', '.erp-ac-line-due', this.lineDue );
            $('.erp-ac-bank-account-wrap').on( 'click', '.erp-ac-transfer-money-btn', this.transferMoney );
            $('body').on( 'change', '.erp-ac-bank-ac-drpdwn', this.checkSameAccount );
        },

        checkSameAccount: function(e) {
            e.preventDefault();
            var self = $(this),
                from = $('.erp-ac-bank-ac-drpdwn-frm').val(),
                to   = $('.erp-ac-bank-ac-drpdwn-to').val(),
                submit_btn = self.closest('form').find( 'button[type=submit]' );

            if ( from == '' || to == '' ) {
                return;
            }
            
            if ( from === to ) {
                submit_btn.prop( 'disabled', true );
                alert( 'Please choose another account' );
                self.select2('val', '');
                return;
            } else {
                submit_btn.prop( 'disabled', false );
            }
        },

        initFields: function() {
            $( '.erp-ac-date-field').datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+0',
            });

            $( '.select2' ).select2({
                theme: "classic"
            });
        },

        transferMoney: function(e) {
            e.preventDefault();
            $.erpPopup({
                title: 'Transfer Money',
                button: 'submit',
                id: 'erp-ac-transfer-popup',
                _wpnonce: ERP_AC.nonce,
                content: wperp.template('erp-ac-transfer-money-pop')().trim(),
                extraClass: 'smaller',
                onReady: function(modal) {
                    $('.erp-ac-transfer-popup').find('.erp-ac-chart-drop-down').addClass('select2');
                    modal.disableButton();
                    ERP_Accounting.initFields();
                },
                onSubmit: function(modal) {
                    wp.ajax.send( {
                        data: this.serialize(),
                        success: function(res) {
                            modal.closeModal();
                        },
                        error: function(error) {
                            alert( error );
                        }
                    });
                }
            }); //popup
        },

        incrementField: function() {
            $( 'table.erp-ac-transaction-table' ).on( 'click', '.add-line', this.table.addRow );
            $( 'table.erp-ac-transaction-table' ).on( 'click', '.remove-line', this.table.removeRow );
            $( 'table.erp-ac-transaction-table.payment-voucher-table' ).on( 'click', '.remove-line', this.paymentVoucher.onChange );
            $( 'table.erp-ac-transaction-table.payment-voucher-table' ).on( 'change', 'input.line_qty, input.line_price, input.line_dis', this.paymentVoucher.onChange );
        },

        lineDue: function(e) {
            e.preventDefault();
            var line_due_total = 0;
            $.each( $('.erp-ac-line-due'), function( key, line_due ) {
                var due = $(line_due).val()  === '' ? 0 : $(line_due).val(); 
                line_due_total = parseFloat( due ) + parseFloat( line_due_total );
            } );

            $('.erp-ac-total-due').val(line_due_total);
        },

        paymentReceive: function(e) {
            e.preventDefault();
            var self = $(this),
                user = self.val();

            wp.ajax.send( {
                data: {
                    action: 'erp_ac_payment_receive',
                    '_wpnonce': ERP_AC.nonce,
                    user_id: user,
                    //account_id: $('.erp-ac-deposit-dropdown').val()
                },

                success: function(res) {
                    $('.erp-form').find('.erp-ac-receive-payment-table').html(res);
                },

                error: function() {
                    var clone_form = $('.erp-ac-receive-payment-table-clone').html();
                    if ( clone_form == '' ) {
                        return;
                    }
                    $('.erp-form').find('.erp-ac-receive-payment-table').html(clone_form);  
                    $('.erp-form').find( '.erp-ac-selece-custom' ).addClass('select2');
                    $('.select2').select2({
                        'theme': 'classic'
                    });
                    ERP_Accounting.incrementField();
                }
            } );
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