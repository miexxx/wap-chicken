<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;

/**
 * @module 联系方式
 *
 * Class ContactController
 * @package App\Admin\Controllers\Base
 */
class ContactController extends Controller
{
    /**
     * @permission 编辑查看联系方式
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $header = '联系方式';
        $contact = Contact::first();

        return view('admin::base.contact-edit',compact('contact','header'));
    }

    /**
     * @permission 修改联系方式
     *
     * @param Request $request
     * @param Contact $contact
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request,Contact $contact)
    {
        $contact->address = $request->get('address');
        $contact->phone = $request->get('phone');
        $contact->email = $request->get('email');
        $contact->wechat_no = $request->get('wechat_no');

        if($request->file('code_img')) {
            $path = $request->file('code_img')->store('contacts', 'public');
            $contact->code_img = $path;
        }

        $contact->save();

       return redirect()->route('admin::contacts.index');
    }
}
