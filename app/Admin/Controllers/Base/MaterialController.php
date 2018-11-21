<?php

namespace App\Admin\Controllers\Base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use EasyWeChat\Factory;
use App\Models\Article;
use App\Models\Video;
use App\Models\AticleInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * @permission 文章列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function article()
    {
        $header = '文章列表';

//        $perPage = 10;//页码
//        if (request()->has('page')) {
//            $current_page = request()->input('page');
//            $current_page = $current_page <= 0 ? 1 :$current_page;
//        } else {
//            $current_page = 1;
//        }
//        $offset = ( $current_page - 1 ) * $perPage;//偏移量
//
//        if(!Redis::get('articles')) {
//            $list = json_encode(getArticleList());
//            Redis::set('articles',$list);
//        }
//        $list = json_decode(Redis::get('articles'),true);
//
//        $item  = array_slice($list['item'], $offset, $perPage);
//        $total = count($list['item']);
//
//        $list  = new LengthAwarePaginator($item, $total, $perPage,$current_page, [
//            'path' => Paginator::resolveCurrentPath(),
//            'pageName' => 'page',
//        ]);
        $list = AticleInfo::join('articles','article_id','=','articles.id')->orderBy('articles.update_time','desc')->paginate(10);

        return view('admin::base.Materials-articles',compact('list','header'));
    }

    /**
     * @permission 视频列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function video()
    {
        $header = '视频列表';
        //$result= $app->material->uploadVideo(public_path("storage/video/a.mp4"), "台风来袭", "狂风暴雨,太可怕了！");

//        $perPage = 10;//页码
//        if (request()->has('page')) {
//            $current_page = request()->input('page');
//            $current_page = $current_page <= 0 ? 1 :$current_page;
//        } else {
//            $current_page = 1;
//        }
//        $offset = ( $current_page - 1 ) * $perPage;//偏移量
//
//        if(!Redis::get('videos')) {
//            $list = json_encode(getVideoList());
//            Redis::set('videos',$list);
//        }
//        $list = json_decode(Redis::get('videos'),true);
//
//        $item  = array_slice($list['item'], $offset, $perPage);
//        $total = count($list['item']);
//
//        $list  = new LengthAwarePaginator($item, $total, $perPage,$current_page, [
//            'path' => Paginator::resolveCurrentPath(),
//            'pageName' => 'page',
//        ]);
        $list = Video::orderBy('update_time','desc')->paginate(10);

        return view('admin::base.Materials-videos',compact('list','header'));
    }

    /**
     * @permission 更新图文列表
     *
     * @return mixed
     */
    public function updateList()
    {
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());
            $stats = $app->material->stats();
            if (isset($stats['errcode']) || !$stats) {
                Log::error(sprintf('Article Fail:error_msg[%s]:[%s]',  $stats['errmsg'],  $stats['errcode']));
                return response()->json(['status' => 0, 'message' => '请求微信服务器失败，请重新获取！']);
            } else {
                $begin = 0;
                $length = 20;
                $sum = $stats['news_count'];
                return  DB::transaction(function () use ($app,$begin,$length,$sum) {
                    while ($begin < $sum) {
                        $list = $app->material->list('news', $begin, $length);
                        if (isset($list['errcode']) || !$list) {
                            Log::error(sprintf('Article Fail:error_msg[%s]:[%s]',  $list['errmsg'],  $list['errcode']));
                            return response()->json(['status' => 0, 'message' => '请求微信服务器失败，请重新获取！']);
                        } else {
                            $res = false;
                            foreach ($list['item'] as $item) {
                                $res = Article::where('media_id', $item['media_id'])->exists();
                                if ($res) break;
                                $article = new Article();
                                $article->media_id = $item['media_id'];
                                $article->update_time = $item['update_time'];
                                $article->save();
                                foreach ($item['content']['news_item'] as $k => $v) {
                                    $articleInfo = new AticleInfo(['title' => $v['title'], 'author' => $v['author'], 'digest' => $v['digest'], 'url' => $v['url'], 'thumb_url' => $v['thumb_url']]);
                                    $article->articleInfos()->save($articleInfo);
                                }
    //                            $data[$item['media_id']]['update_time'] = $item['update_time'];
    //                            foreach($item['content']['news_item'] as  $k => $v) {
    //                                $data[$item['media_id']]['content'][] = ['title' => $v['title'],'author' => $v['author'],'digest' => $v['digest'],'url' => $v['url'],'thumb_url' => $v['thumb_url']];
    //                            }
                            }
                            if ($res) break;
                            else $begin = $begin + $length - 1;
                        }
                    }
                    return response()->json(['status' => 1, 'message' => '获取最新图文素材成功！']);
                });
            }
    }

    /**
     * @permission 设置文章类别
     *
     * @param AticleInfo $aticleInfo
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setScope(AticleInfo $aticleInfo,Request $request)
    {
        $scope = $request->get('scope');

        $aticleInfo->scope =$scope;
        $aticleInfo->save();

        return response()->json(['status' => 1, 'message' => '设置成功']);
    }

    /**
     * @permission 更新视频列表
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function uploadVideos()
    {
        $app = Factory::officialAccount(app('wechat.official_account.default')->config->toArray());
        $stats = $app->material->stats();
        if (isset($stats['errcode']) || !$stats) {
            Log::error(sprintf('View Fail:error_msg[%s]:[%s]',  $stats['errmsg'],  $stats['errcode']));
            return response()->json(['status' => 0, 'message' => '请求微信服务器失败，请重新获取！']);
        }else {
            $begin = 0;
            $length = 20;
            $sum = $stats['video_count'];
            return  DB::transaction(function () use ($app,$begin,$length,$sum) {
                while ($begin < $sum) {
                    $list = $app->material->list('video', $begin, $length);
                    if (isset($list['errcode']) || !$list) {
                        Log::error(sprintf('Video Fail:error_msg[%s]:[%s]',  $list['errmsg'],  $list['errcode']));
                        return response()->json(['status' => 0, 'message' => '请求微信服务器失败，请重新获取！']);
                    } else {
                        $res = false;
                        foreach ($list['item'] as $item) {
                            $res = Video::where('media_id', $item['media_id'])->exists();
                            $detail = $app->material->get( $item['media_id']);
                            if (isset($detail['errcode']) || !$detail) {
                                Log::error(sprintf('Video Fail:error_msg[%s]:[%s]',  $detail['errmsg'],  $detail['errcode']));
                                return response()->json(['status' => 0, 'message' => '请求微信服务器失败，请重新获取！']);
                            }
                            if ($res) break;
                            $video = new Video();
                            $video->media_id = $item['media_id'];
                            $video->update_time = $item['update_time'];
                            $video->name = $item['name'];
                            $video->description = $detail['description'];
                            $video->url = $detail['down_url'];
                            $video->save();
                        }
                        if ($res) break;
                        else $begin = $begin + $length - 1;
                    }
                }
                return response()->json(['status' => 1, 'message' => '获取最新视频素材成功！']);
            });
        }
    }

}