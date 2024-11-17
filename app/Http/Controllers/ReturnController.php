<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReturnRequest;
use App\Mail\ReturnMail;
use Illuminate\Support\Facades\Mail;

class ReturnController extends Controller
{
    public function index() {
        return view("return.return");
    }

    public function submit(ReturnRequest $request) {
        $data = $request->validated();
        $contact = [
            'name' => $data['name'],
            'email' => $data['email'],
            'order_number' => $data['order_number'],
            'order_date' => $data['order_date'],
            'received_date' => $data['received_date'],
            'description' => $data['description'],
        ];
        Mail::to('shop@teknofibra.it')->send(new ReturnMail($contact));
        return redirect()->back()->with('message', __('messages.return'));
    }
}
