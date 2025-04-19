<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        $service = Service::findOrFail($request->service_id);
        $user = auth()->user();
        $amount = $service->offer_price ?? $service->price;
        if ($amount < 10) {
           return  back()->with('error', "Amount must be 10");
        }

        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $user->name;
        $post_data['cus_email'] = $user->email;
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] =  $user->phone ?? "01########";
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                // 'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }


    }

    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }

    }

    // public function success(Request $request)
    // {
    //     $tran_id = $request->input('tran_id');
    //     $amount = $request->input('amount');
    //     $currency = $request->input('currency');
    //     $val_id = $request->input('val_id');

    //     if (!$val_id) {
    //         Log::error('Missing val_id in request', $request->all());
    //         return 'Validation failed: Missing val_id';
    //     }

    //     $sslc = new SslCommerzNotification();

    //     $order_details = Order::with('user', 'service')
    //         ->where('transaction_id', $tran_id)
    //         ->first();

    //     if (!$order_details) {
    //         abort(404, 'Order not found');
    //     }

    //     if ($order_details->status === 'Pending') {
    //         $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
    //         if ($validation) {
    //             $order_details->update([
    //                 'status' => 'Processing',
    //                 'payment_time' => now(),
    //             ]);
    //         }
    //     }

    //     // Validate with SSLCommerz external API
    //     $response = Http::asForm()->post('https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php', [
    //         'val_id' => $request->val_id,
    //         'store_id' => env('SSLCZ_STORE_ID'),
    //         'store_passwd' => env('SSLCZ_STORE_PASSWORD'),
    //         'v' => 1,
    //         'format' => 'json',
    //     ]);

    //     dd($response);



    //     if ($response->ok() && $response['status'] === 'VALID') {
    //         $order_details->update(['status' => 'Completed']);
    //         return view('success', compact('tran_id', 'amount', 'currency', 'order_details'));
    //     }

    //     return 'Validation failed at external API';
    // }



    public function success(Request $request)
    {
        // dd($request->all());
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        $order_details = Order::with('user', 'service')
        ->where('transaction_id', $tran_id)
        ->first();

        if (!$order_details) {
            abort(404, 'Order not found');
        }

        // Fetch order items from order_items table


        if ($order_details->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

            if ($validation) {
                DB::table('orders')->where('transaction_id', $tran_id)->update([
                    'status' => 'Processing',
                    'payment_time' => now(),
                ]);
            }
        }

        // Whether it's already Processing/Complete or just validated, always return the view
        return view('success', [
            'tran_id' => $tran_id,
            'amount' => $amount,
            'currency' => $currency,
            'order_details' => $order_details,
        ]);
    }




    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }

    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_details = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_details->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }


    }
    public function ipn(Request $request)
    {
        if ($request->input('tran_id')) {
            $tran_id = $request->input('tran_id');

            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')
                ->first();

            if ($order_details) {
                if ($order_details->status === 'Pending') {

                    $sslc = new SslCommerzNotification();
                    $validation = $sslc->orderValidate(
                        $request->all(),
                        $tran_id,
                        $order_details->amount,
                        $order_details->currency
                    );

                    if ($validation === true) {
                        DB::table('orders')
                            ->where('transaction_id', $tran_id)
                            ->update(['status' => 'Completed']);

                        echo "Transaction is successfully Completed";
                    } else {
                        DB::table('orders')
                            ->where('transaction_id', $tran_id)
                            ->update(['status' => 'Failed']);

                        echo "Transaction validation failed";
                    }
                } elseif (in_array($order_details->status, ['Processing', 'Completed'])) {
                    echo "Transaction is already successfully Completed";
                } else {
                    echo "Invalid Transaction Status";
                }
            } else {
                echo "Transaction not found";
            }
        } else {
            echo "Invalid Data";
        }
    }


    // public function ipn(Request $request)
    // {
    //     #Received all the payement information from the gateway
    //     if ($request->input('tran_id')) #Check transation id is posted or not.
    //     {

    //         $tran_id = $request->input('tran_id');

    //         #Check order status in order tabel against the transaction id or order id.
    //         $order_details = DB::table('orders')
    //             ->where('transaction_id', $tran_id)
    //             ->select('transaction_id', 'status', 'currency', 'amount')->first();

    //         if ($order_details->status == 'Pending') {
    //             $sslc = new SslCommerzNotification();
    //             $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
    //             if ($validation == TRUE) {
    //                 /*
    //                 That means IPN worked. Here you need to update order status
    //                 in order table as Processing or Complete.
    //                 Here you can also sent sms or email for successful transaction to customer
    //                 */
    //                 $update_product = DB::table('orders')
    //                     ->where('transaction_id', $tran_id)
    //                     ->update(['status' => 'Completed']);

    //                 echo "Transaction is successfully Completed";
    //             }
    //         } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

    //             #That means Order status already updated. No need to udate database.

    //             echo "Transaction is already successfully Completed";
    //         } else {
    //             #That means something wrong happened. You can redirect customer to your product page.

    //             echo "Invalid Transaction";
    //         }
    //     } else {
    //         echo "Invalid Data";
    //     }
    // }

}
