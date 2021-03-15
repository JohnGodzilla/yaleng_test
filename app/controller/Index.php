<?php
namespace app\controller;

use app\BaseController;
use think\facade\View;
use app\biz\Procedure_node_biz;
use think\facade\Request;
use app\model\Procedure_node_model;



class Index extends BaseController
{
    public function index()
    {
        $pn_biz = new Procedure_node_biz();

        $str = $pn_biz->get_procedure_node();
        
        $pn_mode = new Procedure_node_model();
        $last_id = $pn_mode->get_last_procedure_node_id();
        $last_branch_id = $pn_mode->get_last_branch_id();
        //dd($last_branch_id);

        return View::fetch('examine/add', ['data_str'  => $str, 'last_id' => $last_id ?? 0, 'last_branch_id' => $last_branch_id ?? 0]);
    }

    public function save()
    {
        $ids = Request::post('ids', []);
        $pids = Request::post('pids', []);
        $level = Request::post('level', []);
        $content = Request::post('content', []);
        $type = Request::post('type', []);
        $branch_id = Request::post('branch_id', []);
        $parent_branch_id = Request::post('parent_branch_id', []);

        $node_arr = [];
        foreach ($ids as $key => $value) {
            $node_data = [];
            $node_data['id'] = $value;
            $node_data['parent_id'] = $pids[$key];
            $node_data['priority_level'] = $level[$key];
            $node_data['type'] = $type[$key];
            $node_data['content'] = $content[$key];
            $node_data['branch_id'] = $branch_id[$key] ?? 0;
            $node_data['parent_branch_id'] = $parent_branch_id[$key] ?? 0;
            $node_data['p_id'] = 1;
            $node_arr[] = $node_data;
        }
        
        $pn_mode = new Procedure_node_model();
        $pn_mode->save_procedure_node($node_arr, 1);

        $arr = ['data' => '保存成功'];
        return json($arr);
    }

    public function delete()
    {
        
        $pn_mode = new Procedure_node_model();
        $pn_mode->del_procedure_node(1);

        $arr = ['data' => '保存成功'];
        return json($arr);
    }
}
