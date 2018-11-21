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
use App\Models\Freight;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Tanmo\Search\Facades\Search;
use Tanmo\Search\Query\Searcher;

/**
 * @module 运费管理
 *
 * Class CategoryController
 * @package App\Admin\Controllers\Items
 */
class FreightController extends Controller
{
    /**
     * @permission 运费列表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $freights = (new Freight())->orderBy('created_at', 'desc')->paginate(10);

        $header = '运费列表';
        return view('admin::items.freights', compact('freights','header'));
    }

    /**
     * @permission 新增运费-页面
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin::items.freight-create');
    }

    /**
     * @permission 新增运费
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $freight = new Freight();
        $freight->free= $request->get('free');
        $freight->freight = $request->get('freight');
        $freight->save();
        return redirect()->route('admin::freights.index');
    }

    /**
     * @permission 修改运费-页面
     *
     * @param ItemCategory $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Freight $freight)
    {
        return view('admin::items.freight-edit', compact('freight'));
    }

    /**
     * @permission 修改运费
     *
     * @param ItemCategory $category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Freight $freight, Request $request)
    {
        $freight->free= $request->get('free');
        $freight->freight = $request->get('freight');
        $freight->save();

        return redirect()->route('admin::freights.index');
    }

    /**
     * @permission 删除运费
     *
     * @param ItemCategory $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Freight $freight)
    {
        $freight->delete();

        return response()->json(['status' => 1, 'message' => '成功']);
    }

}