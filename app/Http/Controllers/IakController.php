<?php

namespace App\Http\Controllers;

use App\Mail\TicketNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class IakController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return view('iak');
    }

    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'payment_code' => 'required',
            'amount' => 'required',
            'name' => 'required'
        ]);

        $data = [
            'payment_code' => $validatedRequest['payment_code'],
            'amount' => intval($validatedRequest['amount']),
            'name' => $validatedRequest['name']
        ];


        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post('https://do27w.wiremockapi.cloud/pay/validate', $data);

        $responseBody = json_decode($response->body());

        if ($response->status() == 201 && $responseBody->message == "Payment Success") {


            $this->email($validatedRequest['payment_code'], $validatedRequest['amount'], $validatedRequest['name']);

            // nantinya dapat dibuat redirect ke halaman sukses dan diberikan pesan sukses
            return redirect('/')->with('success', $responseBody->message);
        } else {

            // nantinya dapat dibuat error message yang lebih spesifik
            return redirect('/')->with('error', $responseBody->message);
        }
    }

    private function email($payment_code, $amount, $name)
    {

        $payment_code = "TKT-" . substr($payment_code, 0, 3) . "-" . substr($payment_code, 3, 3) . "-" . substr($payment_code, 6, 3);

        $data = [
            'payment_code' => $payment_code,
            'amount' => $amount,
            'name' => $name
        ];

        $email = strtolower(str_replace(' ', '', $name)) . '@mobilepulsa.com';
        

        try {
            Mail::to($email)->send(new TicketNotification($data));
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email failed to send'
            ]);
        }
    }
}
