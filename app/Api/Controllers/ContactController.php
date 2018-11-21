<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;

class ContactController extends Controller
{
    public function show()
    {
        $contact = Contact::first();

        return api()->item($contact, ContactResource::class);
    }
}
