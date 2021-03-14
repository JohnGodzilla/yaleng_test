<?php
namespace app\biz;

use app\model\Procedure_node_model;

class Procedure_node_biz
{
    private $is_show_arr = [];
    private $all_pn_data = [];

    public function get_procedure_node()
    {
        $pn_mode = new Procedure_node_model();
        $data = $pn_mode->get_procedure_node(1);

        $pn_data = [];
        foreach ($data as $value) {
            $pn_data[$value['id']] = [
                'id' => $value['id'],
                'parent_id' => $value['parent_id'],
                'level' => $value['priority_level'],
                'type' => $value['type'],
                'content' => $value['content'],
                'branch_id' => $value['branch_id'],
                'parent_branch_id' => $value['parent_branch_id'],
            ];
        }

        $this->all_pn_data = $pn_data;
        $pn_data = $this->get_tree_data($pn_data, 0);
        $pn_str = $this->get_html_str($pn_data);

        dump($pn_data);
        dump($this->is_show_arr);
        return $pn_str;
    }

    private function get_branch_tree_data($data_array = [], $bpid = 0)
    {

        $tree = [];
        foreach ($data_array as $v) {
            if ($v['parent_branch_id'] == $bpid && $v['type'] == 2) {
                $v['cb_data'] = $v;
                $tree[] = $v;
            }
            if ($v['parent_branch_id'] == $bpid && $v['type'] == 1) {
                $cdata = $this->get_branch_tree_data($data_array, $v['branch_id']);
                $v['cb_data'] = $cdata;
                $tree[] = $v;
            }
        }
        return $tree;
    }

    private function get_tree_data($data_array = [], $pid = 0)
    {

        $tree = [];
        foreach ($data_array as $v) {
            if ($v['parent_id'] == $pid) {
                $cdata = $this->get_tree_data($data_array, $v['id']);
                $v['c_data'] = $cdata;
                $tree[] = $v;
            }
        }
        return $tree;
    }

    private function get_html_str($pn_data = [])
    {
        $str = '';
        if (empty($pn_data)) {
            return $str;
        }
        foreach ($pn_data as $key => $value) {
            //审批条件
            if (in_array($value['id'], $this->is_show_arr)) {
                continue;
            }

            if ($value['type'] == 1) {
                $pingxing_pn = [];
                $branch_id = $value['branch_id'];
                $pingxing_pn = $this->get_pingxing_pn($value['parent_id'], $value['branch_id'], $pn_data);
                $level = array_column($pingxing_pn, 'level');
                array_multisort($level, SORT_ASC, $pingxing_pn);
                $str .= '<div class="branch-wrap">
                            <div class="branch-box-wrap">
                                <div class="branch-box"><button class="add-branch">添加条件</button>';
                $i = 0;
                foreach ($pingxing_pn as $v) {
                    $branch_id = $v['branch_id'];
                    $str .= '<div class="col-box">';
                        $str .= '<div class="condition-node" name="node_data" data-type="' . $v['type'] . '" data-index="' . $v['id'] . '" data-pid="' . $v['parent_id'] . '" data-level="' . $v['level'] . '" data-content="' . $v['content'] . '" branch-id="' . $v['branch_id'] . '" parent-branch-id="' . $v['parent_branch_id'] . '">
                                    <div class="condition-node-box">
                                        <div class="auto-judge">
                                            <div class="title-wrapper"><span class="editable-title">' . $v['content'] . '</span><span class="priority-title">优先级' . $v['level'] . '</span><svg class="svg-icon copy" viewBox="0 0 1024 1024" width="1em" height="1em"><defs><style></style></defs><path d="M637.873 157.538c60.731 0 110.829 47.262 110.829 106.496v576.119c0 55.768-44.348 100.824-100.274 105.944l-10.555.552H160.532c-60.81 0-110.907-47.262-110.907-106.496V264.034c0-59.313 50.097-106.496 110.828-106.496h477.341zm0 68.924H160.532c-23.631 0-41.984 17.329-41.984 37.572v576.119c0 20.243 18.353 37.573 41.905 37.573h477.341c23.631 0 41.984-17.33 41.984-37.573V264.034c0-20.322-18.353-37.572-41.905-37.572zM834.796 0c60.731 0 110.829 47.262 110.829 106.496v576.118c0 59.235-50.098 106.496-110.829 106.496a34.422 34.422 0 01-6.222-68.372l6.222-.55c23.631 0 41.906-17.33 41.906-37.574V106.496c0-20.322-18.354-37.573-41.906-37.573H357.455c-21.032 0-37.81 13.627-41.354 30.956l-.63 6.617a34.422 34.422 0 11-68.923 0C246.548 47.183 296.645 0 357.376 0h477.342zM426.772 491.284a43.323 43.323 0 010 86.646H253.479a43.323 43.323 0 010-86.646h173.293zm86.646-173.293a43.323 43.323 0 010 86.647H253.479a43.323 43.323 0 010-86.647h259.939z"></path></svg>
                                                <svg class="svg-icon close" viewBox="0 0 1024 1024" width="1em" height="1em">
                                                    <path d="M512 451.67L768.427 195.2a42.667 42.667 0 1160.373 60.33L572.31 512 828.8 768.427a42.667 42.667 0 11-60.33 60.373L512 572.31 255.573 828.8a42.667 42.667 0 11-60.373-60.33L451.69 512 195.2 255.573a42.667 42.667 0 1160.33-60.373L512 451.69z"></path>
                                                </svg>
                                            </div>
                                            <div class="content">条件</div>';
                            $str .= '   </div>
                                        <div class="add-node-btn"><button class="btn" type="button"><span class="layui-icon layui-icon-add-1"></span></button></div>
                                    </div>
                                </div>';
                    if (isset($v['c_data']) && !empty($v['c_data'])) {
                        $str .= $this->get_html_str($v['c_data']);
                    }
                    $str .= '</div>';
                    $this->is_show_arr[] = $v['id'];
                    $i++;
                }

                $str .= '</div>
                            <div class="add-node-btn-box bottom-node-btn">
                                <div class="add-node-btn">
                                    <button class="btn" type="button"><span class="layui-icon layui-icon-add-1"></span></button>
                                </div>
                            </div>
                        </div>';
                $str .= '</div>';
                $temp_nd_data = [];
                $last_data = '';
                EACH:
                foreach($this->all_pn_data as $value) {
                    if ($value['parent_branch_id'] == $branch_id && $value['type'] == 1) {
                        $temp_nd_data = $this->get_tree_data($this->all_pn_data, $value['id']);
                        //dump($temp_nd_data);
                        $last_data = $value;
                    }
                }
                if (!empty($temp_nd_data)) {
                    $str .= $this->get_html_str($temp_nd_data);
                }
                if (!empty($last_data) && $last_data['branch_id'] != $branch_id) {
                    $branch_id = $last_data['branch_id'];
                    goto EACH;
                }
                
                //dump($temp_nd_data);
                //审批人
            } else if ($value['type'] == 2) {
                $str .= '
                <div class="node-wrap" name="node_data" data-type="' . $value['type'] . '" data-index="' . $value['id'] . '" data-pid="' . $value['parent_id'] . '" data-level="' . $value['level'] . '" data-content="' . $value['content'] . '" branch-id="' . $value['branch_id'] . '" parent-branch-id="' . $value['parent_branch_id'] . '">
                    <div class="node-wrap-box active">
                        <div>
                            <div class="title" style="background: rgb(255, 148, 62);"><span class="editable-title">审批人</span><i aria-label="icon: close" tabindex="-1" class="anticon anticon-close close"><svg viewBox="64 64 896 896" focusable="false" class="" data-icon="close" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M563.8 512l262.5-312.9c4.4-5.2.7-13.1-6.1-13.1h-79.8c-4.7 0-9.2 2.1-12.3 5.7L511.6 449.8 295.1 191.7c-3-3.6-7.5-5.7-12.3-5.7H203c-6.8 0-10.5 7.9-6.1 13.1L459.4 512 196.9 824.9A7.95 7.95 0 0 0 203 838h79.8c4.7 0 9.2-2.1 12.3-5.7l216.5-258.1 216.5 258.1c3 3.6 7.5 5.7 12.3 5.7h79.8c6.8 0 10.5-7.9 6.1-13.1L563.8 512z"></path></svg></i></div>
                            <div class="content">
                                <div class="time-text"><span class="time-placeholder">' . $value['content'] . '</span></div>
                                <i aria-label="icon: right" class="anticon anticon-right arrow"><svg viewBox="64 64 896 896" focusable="false" class="" data-icon="right" width="1em" height="1em" fill="currentColor" aria-hidden="true"><path d="M765.7 486.8L314.9 134.7A7.97 7.97 0 0 0 302 141v77.3c0 4.9 2.3 9.6 6.1 12.6l360 281.1-360 281.1c-3.9 3-6.1 7.7-6.1 12.6V883c0 6.7 7.7 10.4 12.9 6.3l450.8-352.1a31.96 31.96 0 0 0 0-50.4z"></path></svg></i>
                            </div>
                        </div>
                    </div>
                    <div class="add-node-btn-box">
                        <div class="add-node-btn">
                            <button class="btn" type="button">
                                <span class="layui-icon layui-icon-add-1"></span>
                            </button>
                        </div>
                    </div>
                </div>
                ';
                if (isset($value['c_data']) && !empty($value['c_data'])) {
                    $str .= $this->get_html_str($value['c_data']);
                }
                $this->is_show_arr[] = $value['id'];
            }
        }
        return $str;
    }

    private function get_pingxing_pn(int $pid, int $branch_id, array $pn_data)
    {
        # code...
        $pingxing_data = [];
        foreach ($pn_data as $v) {
            if ($v['parent_id'] == $pid && $v['type'] == 1 && $v['branch_id'] == $branch_id) {
                $pingxing_data[] = $v;
            }
        }
        return $pingxing_data;
    }

}
