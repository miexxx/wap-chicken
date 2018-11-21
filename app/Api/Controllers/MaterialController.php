<?php

namespace App\Api\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use EasyWeChat\Factory;
use App\Models\AticleInfo;
use App\Models\Video;
use App\Http\Resources\ArticleResource;

/**
 * use Illuminate\Support\Facades\Storage;
 * @module 素材管理
 *
 * Class TemplateController
 * @package App\Admin\Controllers\Base
 */
class MaterialController extends Controller
{
    /**
     * @return \Tanmo\Api\Http\Response
     */
    public function articles()
    {
        if(request()->get('scope')){
            $articles = AticleInfo::FilterScope(request()->get('scope'))->join('articles','article_id','=','articles.id')->orderBy('articles.update_time','desc')->paginate(10);
        }
        else {
            $articles = AticleInfo::join('articles','article_id','=','articles.id')->orderBy('articles.update_time','desc')->paginate(10);
        }
        return api()->collection($articles, ArticleResource::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function videos()
    {
        $videos = Video::orderBy('update_time','desc')->paginate(10);

        return response()->json($videos);
    }
}