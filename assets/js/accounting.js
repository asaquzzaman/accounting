jQuery(function($) {

    $( 'table.erp-ac-transaction-table' ).on('click', '.add-line', function(event) {
        event.preventDefault();

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
    });

    $( 'table.erp-ac-transaction-table' ).on('click', '.remove-line', function(event) {
        event.preventDefault();

        var self = $(this),
            table = self.closest( 'table' );

        if ( table.find('tbody > tr').length < 2 ) {
            return;
        }

        self.closest('tr').remove();
        calculateTablePrice();
    });

    function calculateTablePrice() {
        var table = $('table.erp-ac-transaction-table');
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
    }

    $( 'table.erp-ac-transaction-table' ).on('change', 'input.line_qty, input.line_price, input.line_dis', function(event) {
        calculateTablePrice();
    });

});