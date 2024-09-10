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
        $name = $data['name'];
        $email = $data['email'];
        $message = $data['message'];

        $contact = compact('name', 'email', 'message');
        Mail::to('shop@teknofibra.it')->send(new SupportMail($contact));
        return redirect()->back()->with('message', 'Email Inviata con Successo. Sarai ricontattato a breve.');
    }
}
