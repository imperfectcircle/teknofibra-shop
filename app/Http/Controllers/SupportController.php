<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Mail\SupportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function index() {
        return view('support.support');
    }

    public function submit(SupportRequest $request) {
        $data = $request->validated();
        $contact = [
            'name' => $data['name'],
            'email' => $data['email'],
            'order_number' => $data['order_number'] ?? '',
            'message' => $data['message'],
        ];
        Mail::to('shop@teknofibra.it')->send(new SupportMail($contact));
        return redirect()->back()->with('message', __('messages.support'));
    }
}
