<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/4/13
 * Time: 15:48
 * Function:
 */

namespace App\Admin\Controllers\Items;


use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 套餐分类管理
 *
 * Class CategoryController
 * @package App\Admin\Controllers\Items
 */
class CategorytController extends Controller
{
    /**
     * @permission 套餐分类列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $searcher = Search::build(function (Searcher $searcher) {
            $searcher->equal('id');
            $searcher->like('name');
        });
        $data = request()->all();
        $categories = (new ItemCategory())->search($searcher)->where('type','=',1)->orderBy('created_at', 'desc')->paginate(10);

        $header = '套餐商品分类';
        return view('admin::items.categoriest', compact('categories','data','header'));
    }

    /**
     * @permission 新增套餐分类-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::items.categoryt-create');
    }

    /**
     * @permission 新增套餐分类
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        ItemCategory::create(['name' => $request->name,'type'=>1]);
        return redirect()->route('admin::categoriest.index');
    }

    /**
     * @permission 修改套餐分类-页面
     *
     * @param ItemCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ItemCategory $category)
    {
        return view('admin::items.categoryt-edit', compact('category'));
    }

    /**
     * @permission 修改套餐分类
     *
     * @param ItemCategory $category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ItemCategory $category, Request $request)
    {
        $category->name = $request->get('name');
        $category->save();

        return redirect()->route('admin::categoriest.index');
    }

    /**
     * @permission 删除套餐分类
     *
     * @param ItemCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ItemCategory $category)
    {
        $category->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

}