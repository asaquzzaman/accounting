<?php
namespace WeDevs\ERP\Accounting;

/**
 * Handle the form submissions
 *
 * @package Package
 * @subpackage Sub Package
 */
class Form_Handler {

    /**
     * Hook 'em all
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'handle_customer_form' ) );
        add_action( 'admin_init', array( $this, 'chart_form' ) );
        add_action( 'admin_init', array( $this, 'transaction_form' ) );
    }

    /**
     * Handle the customer new and edit form
     *
     * @return void
     */
    public function handle_customer_form() {
        if ( ! isset( $_POST['submit_erp_ac_customer'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'erp-ac-customer' ) ) {
            die( __( 'Are you cheating?', 'erp-accounting' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'erp-accounting' ) );
        }

        $message     = 'new';
        $errors      = array();
        $field_id    = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;

        $first_name  = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
        $last_name   = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
        $email       = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
        $company     = isset( $_POST['company'] ) ? sanitize_text_field( $_POST['company'] ) : '';
        $phone       = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
        $mobile      = isset( $_POST['mobile'] ) ? sanitize_text_field( $_POST['mobile'] ) : '';
        $other       = isset( $_POST['other'] ) ? sanitize_text_field( $_POST['other'] ) : '';
        $website     = isset( $_POST['website'] ) ? sanitize_text_field( $_POST['website'] ) : '';
        $fax         = isset( $_POST['fax'] ) ? sanitize_text_field( $_POST['fax'] ) : '';
        $notes       = isset( $_POST['notes'] ) ? wp_kses_post( $_POST['notes'] ) : '';
        $street_1    = isset( $_POST['street_1'] ) ? sanitize_text_field( $_POST['street_1'] ) : '';
        $city        = isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
        $state       = isset( $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : '';
        $postal_code = isset( $_POST['postal_code'] ) ? sanitize_text_field( $_POST['postal_code'] ) : '';
        $country     = isset( $_POST['country'] ) ? sanitize_text_field( $_POST['country'] ) : '';
        $currency    = isset( $_POST['currency'] ) ? sanitize_text_field( $_POST['currency'] ) : '';
        $type        = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : 'customer';

        if ( $type == 'customer' ) {
            $page_url    = admin_url( 'admin.php?page=erp-accounting-customers' );
        } else {
            $page_url    = admin_url( 'admin.php?page=erp-accounting-vendors' );
        }

        // some basic validation
        if ( ! $first_name ) {
            $errors[] = __( 'Error: First Name is required', 'erp-accounting' );
        }

        if ( ! $last_name ) {
            $errors[] = __( 'Error: Last Name is required', 'erp-accounting' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'email'       => $email,
            'company'     => $company,
            'phone'       => $phone,
            'mobile'      => $mobile,
            'other'       => $other,
            'website'     => $website,
            'fax'         => $fax,
            'notes'       => $notes,
            'street_1'    => $street_1,
            'city'        => $city,
            'state'       => $state,
            'postal_code' => $postal_code,
            'country'     => $country,
            'currency'    => $currency,
            'type'        => $type,
        );

        // New or edit?
        if ( ! $field_id ) {

            $insert_id = erp_ac_insert_customer( $fields );

        } else {

            $fields['id'] = $field_id;
            $message      = 'update';

            $insert_id    = erp_ac_insert_customer( $fields );
        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'msg' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'msg' => $message ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }

    /**
     * Handle the chart new and edit form
     *
     * @return void
     */
    public function chart_form() {
        if ( ! isset( $_POST['submit_erp_ac_chart'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'erp-ac-chart' ) ) {
            die( __( 'Are you cheating?', 'erp-accounting' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'erp-accounting' ) );
        }

        $message  = 'new';
        $errors   = array();
        $page_url = admin_url( 'admin.php?page=erp-accounting-charts' );
        $field_id = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;

        $name            = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
        $account_type_id = isset( $_POST['account_type_id'] ) ? sanitize_text_field( $_POST['account_type_id'] ) : '';
        $active          = isset( $_POST['active'] ) ? intval( $_POST['active'] ) : 1;

        // some basic validation
        if ( ! $name ) {
            $errors[] = __( 'Error: Name is required', 'erp-accounting' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = array(
            'name'            => $name,
            'account_type_id' => $account_type_id,
            'active'          => $active
        );

        // New or edit?
        if ( ! $field_id ) {

            $insert_id = erp_ac_insert_chart( $fields );

        } else {

            $fields['id'] = $field_id;
            $message      = 'update';

            $insert_id = erp_ac_insert_chart( $fields );
        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'msg' => 'error' ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'msg' => $message ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }

    /**
     * Handle the transaction new and edit form
     *
     * @return void
     */
    public function transaction_form() {
        if ( ! isset( $_POST['submit_erp_ac_trans'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'erp-ac-trans-new' ) ) {
            die( __( 'Are you cheating?', 'erp-accounting' ) );
        }

        if ( ! current_user_can( 'read' ) ) {
            wp_die( __( 'Permission Denied!', 'erp-accounting' ) );
        }

        $errors          = array();
        $page_url        = admin_url( 'admin.php?page=erp-accounting-expense' );
        $field_id        = isset( $_POST['field_id'] ) ? intval( $_POST['field_id'] ) : 0;

        $type            = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : '';
        $form_type       = isset( $_POST['form_type'] ) ? sanitize_text_field( $_POST['form_type'] ) : '';
        $account_id      = isset( $_POST['account_id'] ) ? intval( $_POST['account_id'] ) : 0;
        $status          = isset( $_POST['status'] ) ? sanitize_text_field( $_POST['status'] ) : '';
        $user_id         = isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
        $billing_address = isset( $_POST['billing_address'] ) ? sanitize_text_field( $_POST['billing_address'] ) : '';
        $ref             = isset( $_POST['ref'] ) ? sanitize_text_field( $_POST['ref'] ) : '';
        $issue_date      = isset( $_POST['issue_date'] ) ? sanitize_text_field( $_POST['issue_date'] ) : '';
        $summary         = isset( $_POST['summary'] ) ? wp_kses_post( $_POST['summary'] ) : '';
        $total           = isset( $_POST['price_total'] ) ? sanitize_text_field( $_POST['price_total'] ) : '';
        $files           = isset( $_POST['files'] ) ? sanitize_text_field( $_POST['files'] ) : '';
        $currency        = isset( $_POST['currency'] ) ? sanitize_text_field( $_POST['currency'] ) : 'USD';

        // var_dump( $_POST ); die();

        // some basic validation
        if ( ! $issue_date ) {
            $errors[] = __( 'Error: Issue Date is required', 'erp-accounting' );
        }

        if ( ! $account_id ) {
            $errors[] = __( 'Error: Account ID is required', 'erp-accounting' );
        }

        if ( ! $total ) {
            $errors[] = __( 'Error: Total is required', 'erp-accounting' );
        }

        // bail out if error found
        if ( $errors ) {
            $first_error = reset( $errors );
            $redirect_to = add_query_arg( array( 'error' => $first_error ), $page_url );
            wp_safe_redirect( $redirect_to );
            exit;
        }

        $fields = [
            'type'            => $type,
            'form_type'       => $form_type,
            'account_id'      => $account_id,
            'status'          => $status,
            'user_id'         => $user_id,
            'billing_address' => $billing_address,
            'ref'             => $ref,
            'issue_date'      => $issue_date,
            'summary'         => $summary,
            'total'           => $total,
            'files'           => $files,
            'currency'        => $currency,
        ];

        $items = [];
        foreach ($_POST['line_account'] as $key => $acc_id) {
            $line_total = (float) $_POST['line_total'][ $key ];

            if ( ! $acc_id || ! $line_total ) {
                continue;
            }

            $items[] = [
                'account_id'  => (int) $acc_id,
                'description' => sanitize_text_field( $_POST['line_desc'][ $key ] ),
                'qty'         => intval( $_POST['line_qty'][ $key ] ),
                'unit_price'  => floatval( $_POST['line_unit_price'][ $key ] ),
                'discount'    => floatval( $_POST['line_discount'][ $key ] ),
                'line_total'  => $line_total,
            ];
        }

        // var_dump( $fields, $items ); die();

        // New or edit?
        if ( ! $field_id ) {
            $insert_id = erp_ac_insert_transaction( $fields, $items );
        }

        if ( is_wp_error( $insert_id ) ) {
            $redirect_to = add_query_arg( array( 'msg' => $insert_id->get_error_message() ), $page_url );
        } else {
            $redirect_to = add_query_arg( array( 'msg' => 'success' ), $page_url );
        }

        wp_safe_redirect( $redirect_to );
        exit;
    }
}
