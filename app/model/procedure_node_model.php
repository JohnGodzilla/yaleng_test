<?php
namespace app\model;

use think\Model;
use think\facade\Db;

class Procedure_node_model extends Model
{
    
    public function get_procedure_node(int $pid)
    {
        $data = Db::table('procedure_node')->where('p_id', $pid)->select()->toArray();
        //var_dump($data);
        return $data;
    }

    public function save_procedure_node(array $data, int $pid)
    {
        Db::table('procedure_node')->where('p_id', $pid)->delete();
        if (!empty($data)) {
            Db::table('procedure_node')->replace()->insertAll($data);
        }
    }

    public function get_last_procedure_node_id()
    {
        $data = Db::table('procedure_node')->where(1, 1)->order('id', 'desc')->limit(1)->value('id');
        return $data;
    }

    public function get_last_branch_id()
    {
        $data = Db::table('procedure_node')->where(1, 1)->order('branch_id', 'desc')->limit(1)->value('branch_id');
        return $data;
    }
    
}