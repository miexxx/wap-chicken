<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\SharerCenterResource;

class SharerCenterController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $this->authorize('share',$user);

        return api()->item($user,SharerCenterResource::class);
    }

}
