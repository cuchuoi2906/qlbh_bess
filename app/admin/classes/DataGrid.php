<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Created by PhpStorm.
 * User: Stephen Nguyen
 * Date: 6/11/2017
 * Time: 11:29 PM
 */
class DataGrid
{
    public $data = [];
    public $totalRecord = 0;
    public $idField = 'id';
    public $perPage = 10;

    public $fields = [];

    public $viewRender;

    /**
     * @var \VatGia\Model\ModelBase
     */
    public $model;
    public $dataExport;

    private $canExport = false;


    public function __construct($data, $total, $id = null, $per_page = 10, $view_render = null)
    {
        $this->data = $data;
        $this->totalRecord = (int)$total;
        $this->idField = $id ? $id : $this->idField;
        $this->perPage = $per_page ?? 10;

        $this->viewRender = $view_render;
    }

    public function column($fieldName, $label, $type, $sort = [], $search = [], $show = true)
    {
        $fieldName = $fieldName ? $fieldName : uniqid();
        $fieldName = is_array($fieldName) ? $fieldName : (array)$fieldName;
        $this->fields[reset($fieldName)] = [
            'field' => $fieldName,
            'label' => $label,
            'type' => $type,
            'sort' => $sort,
            'search' => $search,
            'show' => $show
        ];

        return $this;
    }

    public function addExport()
    {
        $lastField = end($this->fields);
        $lastField['export'] = 1;

        $this->fields[reset($lastField['field'])] = $lastField;

        $this->canExport = true;

        return $this;

    }

    public function search($fieldName, $label, $type, $search = true)
    {
        $fieldName = $fieldName ? $fieldName : uniqid();
        $fieldName = is_array($fieldName) ? $fieldName : (array)$fieldName;
        $this->column($fieldName, $label, $type, [], $search, false);
    }

    public function total($field_name, $total, $add_label = '')
    {

        $this->fields[$field_name] = $this->fields[$field_name] ?? [];
        $this->fields[$field_name]['total'] = number_format($total) . ' ' . $add_label;
    }

    public function render()
    {
        if (getValue('export', 'str') == 'Export') {
            return $this->export();
        }
        $string = $this->header();
        $string .= '
<div class="row">
    <div class="col-xs-12">
        <div style="margin-bottom: 0px;overflow-x: auto;border: none;" class="table-responsive table-responsive-clone">
            <div style="height: 15px;" class="table-responsive-clone-content"></div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center bg-primary">#</th>';

        $sort_keeps = $_GET;
        $old_sort_field = $sort_keeps['sort_field'] ?? '';
        $old_sort_type = $sort_keeps['sort_type'] ?? '';
        unset($sort_keeps['sort_field']);
        unset($sort_keeps['sort_type']);


        foreach ($this->fields as $field => $fieldOption) {
            if ($fieldOption['show'] ?? true) {


                if ($fieldOption['sort']) {
                    $icon_sort_class = '';
                    $sort_keeps['sort_field'] = $field;
                    if ($field == $old_sort_field) {
                        $sort_keeps['sort_type'] = $old_sort_type == 'ASC' ? 'DESC' : 'ASC';

                        if ($old_sort_type == 'ASC') {
                            $icon_sort_class = 'fa fa-caret-up';
                        } else {
                            $icon_sort_class = 'fa fa-caret-down';
                        }
                    } else {
                        $icon_sort_class = 'fa fa-sort';
                        $sort_keeps['sort_type'] = 'DESC';
                    }

                    $sort_link = '?' . http_build_query($sort_keeps);
                    $string .= '
                        <th class="field-search text-center bg-primary">
                            <a style="color: white" href="' . $sort_link . '">
                                <i class="' . $icon_sort_class . '"></i>
                            ' . $fieldOption['label'] . '
                            </a>
                        </th>';
                } else {

                    if($fieldOption['label'] == 'chk_item_all'){
                        $fieldOption['label'] = '<input type="checkbox" name="chk_item_all" value="" onclick ="check_all(this);">';
                    }
                    $string .= '
                        <th class="text-center bg-primary">
                            ' . $fieldOption['label'] . '
                        </th>';
                }
            }

        }

        $string .= '
                    </tr>
                </thead>';
        $string .= '
                <tbody>';

        $i = 0;
        if ($this->data) {
            foreach ($this->data as $row) {
                $string .= $this->tr($row, ++$i);
            }
        }

        $string .= '<tr><td></td>';
        foreach ($this->fields as $field => $field_options) {
            if (isset($field_options['total'])) {
                $string .= '<td class="field-total" style="text-align: right">' . $field_options['total'] . '</td>';
            } else {
                $string .= ($field_options['show'] ?? true) ? '<td></td>' : '';
            }
        }
        $string .= '</tr>';


        $string .= '   
                </tbody>
        ';

        $string .= '
            <tfoot>
            <thead>
                    <tr>
                        <th class="text-center bg-primary">#</th>';

        foreach ($this->fields as $field => $fieldOption) {
            if ($fieldOption['show'] ?? true) {
                $string .= '
                        <th class="text-center bg-primary">
                            ' . $fieldOption['label'] . '
                        </th>';
            }

        }

        $string .= '
                    </tr>
                </thead>
</tfoot>
            </table>
        </div>
    </div>
</div>
';
        $string .= $this->footer();

        return $string;
    }

    public function header()
    {
        //Show form search
        $search = [];
        foreach ($this->fields as $field => $fieldOption) {
            if ($fieldOption['search']) {
                $search[$field] = $fieldOption;
            }
        }
        if (!$search) {
            return '';
        }
        $string = '<form action="" method="get">
    <div class="row table-header-search">  
      ';

        //Giữ các giá trị trên $_GET, loại bỏ những field đã có trong $search
        $search_keeps = $_GET;
        unset($search_keeps['page']);

        foreach ($search_keeps as $key => $value) {
            if (!array_key_exists($key, $search)) {
                $string .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
            }
        }

        $i = 0;
        foreach ($search as $field => $fieldOption) {
            $i++;
            if ($i % 6 == 1) {
                $string .= '<div style="clear:both"></div>';
            }
            $string .= '
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label>' . $fieldOption['label'] . '</label>';


            if (is_string($fieldOption['type']) && false !== strpos($fieldOption['type'], '|')) {
                $fieldOption['type'] = explode('|', $fieldOption['type']);
            }

            $fieldOption['type'] = is_array($fieldOption['type']) ? $fieldOption['type'] : (array)$fieldOption['type'];
            $type = reset($fieldOption['type']);

            switch ($type) {
                case 'select':
                case 'selectShow':
                    $data = $fieldOption['field'][1];
                    $string .= '<select class="form-control select select2" name="' . $field . '' . (($fieldOption['search']['multi'] ?? false) ? '[]' : '') . '" ' . (($fieldOption['search']['multi'] ?? false) ? 'multiple' : '') . '>
';

                    foreach ($data as $id => $name) {
                        if (getValue($field, 'str', 'GET', -1) == $id || in_array($id, (array)getValue($field, 'arr', 'GET', []))) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $string .= '<option ' . $selected . ' value="' . $id . '">' . $name . '</option>';
                    }

                    $string .= '
</select>';
                    break;

                case 'active':
                case 'activeDisabled':
                    $string .= '<select class="form-control select" name="' . $field . '">
';

                    foreach ([-1 => 'Tất cả', 0 => 'Không', 1 => 'Có'] as $id => $name) {
                        if (getValue($field, 'str', 'GET', -1) == $id) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }
                        $string .= '<option ' . $selected . ' value="' . $id . '">' . $name . '</option>';
                    }

                    $string .= '
</select>';
                    break;
                case 'dateTime':
                case 'datetime':
                    $string .= '<input name="' . $field . '" value="' . getValue($field, 'str', 'GET', '') . '" class="datetime-picker form-control">';
                    break;
                case 'intRange':
                    $string .= '<div class="input-group">';
                    $string .= '<input style="width: 50%" name="' . $field . '_min" placeholder="Từ" value="' . getValue($field . '_min', 'str', 'GET', '') . '" class="form-control">';
                    $string .= '<input style="width: 50%" name="' . $field . '_max" placeholder="Đến" value="' . getValue($field . '_max', 'str', 'GET', '') . '" class="form-control">';
                    $string .= '</div>';
                    break;
                case 'string':
                default:
                    $string .= '<input name="' . $field . '" value="' . getValue($field, 'str', 'GET', '') . '" class="form-control">';
                    break;
            }


            $string .= '
            </div>
        </div>';
        }

        $string .= '
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label>&nbsp;</label>
                <input class="form-control btn btn-primary" type="submit" value="Tìm kiếm"/>
            </div>
        </div>';
        if ($this->canExport) {
            $string .= '
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label>&nbsp;</label>
                <input class="form-control btn btn-primary" name="export" type="submit" value="Export"/>
            </div>
        </div>
        ';
        }
        $string .= '
    </div>
</form>';

        return $string;
    }

    public function pagination()
    {
        $page = getValue('page');
        $page = ($page <= 1) ? 1 : $page;

        $total = $this->totalRecord;
        $totalPage = ceil($total / ($this->perPage ?: 1));

        if ($totalPage <= 1) {
            return;
        }

        $pageShow = 5;
        $pageShow = ($pageShow > $totalPage) ? $totalPage : $pageShow;

        $startPage = $page - floor($pageShow / 2);
        if ($startPage + $pageShow > $totalPage)
            $startPage = $totalPage - $pageShow + 1;
        $startPage = ($startPage >= 1) ? $startPage : 1;

        $arrayPage = array_fill($startPage, $pageShow, array());

        function pageUrl($page)
        {
            $pageParrams = $_GET;
            $pageParrams['page'] = $page;
            return '?' . http_build_query($pageParrams);
        }

        $string = '<ul class="pagination pagination-sm no-margin pull-right">
<li><a href="' . pageUrl(1) . '">«</a></li>';
        foreach ($arrayPage as $pageNumber => $pageData) {
            $active = ($page == $pageNumber) ? 'active' : '';
            $string .= '
<li class="' . $active . '">
    <a href="' . pageUrl($pageNumber) . '">
        ' . $pageNumber . '
    </a>
</li>';
        }
        $string .= '<li><a href="' . pageUrl($totalPage) . '">»</a></li>
</ul>';

        return $string;

    }

    public function footer()
    {
        $footer = '';
        $footer .= '
<div class="pull-left">
    Tổng số: ' . $this->totalRecord . ' bản ghi.
</div>
        ' . $this->pagination();

        return $footer;
    }

    public function tr($row, $i = 1)
    {

        $id = $row->{$this->idField};

        $is_deleted = false;
        if ($row->deleted_at ?? false) {
            $is_deleted = true;
        }

        $string = '
<tr id="row_' . (int)$id . '" row-id="' . (int)$id . '" class="' . ($is_deleted ? 'is_deleted' : '') . '">
    <td class="text-center">' . $i . '</td>
';
        foreach ($this->fields as $field => $fieldOption) {

            if (!($fieldOption['show'] ?? true)) {
                continue;
            }
            $td_class = 'text-left';
            $field_string = $this->getFieldString($row, $field);

            $string .= '<td class="' . $td_class . '">';
            $string .= $field_string;
            $string .= '</td>';

        }
        $string .= '</tr>';

        return $string;
    }

    public function getFieldString($row, $field)
    {
        $fieldOption = $this->fields[$field];

        $field_string = '';
        if (is_string($fieldOption['type']) && false !== strpos($fieldOption['type'], '|')) {
            $fieldOption['type'] = explode('|', $fieldOption['type']);
        }
        $fieldOption['type'] = (array)$fieldOption['type'];

        $first_functions = [];
        $last_functions = [];
        foreach ($fieldOption['type'] as $type) {
            if (in_array($type, ['center', 'left', 'right'])) {
                $td_class = 'text-' . $type;
            } elseif (is_string($type) && function_exists($type)) {
                $first_functions[] = $type;
            } elseif (is_callable($type)) {
                $last_functions[] = $type;
            } else {
                if (method_exists($this, 'type' . ucfirst($type))) {
                    $last_functions[] = [$this, 'type' . ucfirst($type)];
                }
            }
        }
        foreach ($first_functions as $function) {
            $row->$field = $function($row->$field);
        }
        foreach ($last_functions as $function) {
            $field_string = call_user_func_array($function, [$row, $field, $fieldOption]);
        }

        return $field_string;
    }

    public function fieldValue($row, $field)
    {
        $field = explode('.', $field);
        foreach ($field as $temp) {
            $row = $row->$temp ?? '';
        }

        return $row;
    }

    public function typeString($row, $field)
    {
        return htmlentities($this->fieldValue($row, $field));
    }

    public function typeNumber($row, $field)
    {
        return (int)$this->fieldValue($row, $field);
    }

    public function typeSelect($row, $field, $option)
    {

        $data = isset($option['field'][1]) ? $option['field'][1] : [];

        $string = '<select data-row-id="' . $row->{$this->idField} . '" class="select2 ' . $field . '" id="' . $field . '_' . $row->{$this->idField} . '" name="' . $field . '[' . $row->{$this->idField} . ']">
';
        foreach ($data as $id => $label) {
            if ($this->fieldValue($row, $field) == $id) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $string .= '<option ' . $selected . ' value="' . $id . '">' . $label . '</option>';
        }
        $string .= '
</select>';

        return $string;
    }

    public function typeSelectShow($row, $field, $option)
    {

        $data = isset($option['field'][1]) ? $option['field'][1] : [];

        return isset($data[$this->fieldValue($row, $field)]) ? $data[$this->fieldValue($row, $field)] : 'Chưa rõ';
    }

    public function typeMoney($row, $field)
    {
        return '<div style="text-align: right">' . number_format((int)$this->fieldValue($row, $field)) . 'đ' . '</div>';
    }

    public function typeImage($row, $field)
    {
        return '<img class="data-grid-image" style="max-height: 100px;" src="' . $this->fieldValue($row, $field) . '" />';
    }
    public function typeCheckboxAll($row, $field)
    {
        return '<input type="checkbox" class="checkboxAll" value="' . $this->fieldValue($row, $field) .'" name="chk_item_id'.$row->{$this->idField}.'">';
    }

    public function typeActive($row, $field, $option)
    {
        $condition = isset($option['field'][1]) ? $option['field'][1] : null;

        if ((null !== $condition && is_callable($condition) && call_user_func($condition, $row)) || $this->fieldValue($row, $field)) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        return '<input class="' . $field . ' quick-active" field="' . $field . '" record-id="' . $row->{$this->idField} . '" ' . $checked . ' name="' . $field . '[' . $row->{$this->idField} . ']" id="' . $field . '_' . $row->{$this->idField} . '" type="checkbox" />';
    }

    public function typeActiveShow($row, $field)
    {
        if ($this->fieldValue($row, $field)) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        return '<input class="' . $field . '" disable field="' . $field . '" record-id="' . $row->{$this->idField} . '" ' . $checked . ' name="' . $field . '[' . $row->{$this->idField} . ']" id="' . $field . '_' . $row->{$this->idField} . '" type="checkbox" />';
    }

    public function typeActiveDisabled($row, $field)
    {
        if ($row[$field]) {
            $checked = 'checked';
        } else {
            $checked = '';
        }

        return '<input disabled ' . $checked . ' name="' . $field . '[' . $row[$this->idField] . ']" id="' . $field . '_' . $row[$this->idField] . '" type="checkbox" />';
    }


    public function typeEdit($row)
    {
        $linkEdit = 'edit.php?record_id=' . $row->{$this->idField} . '&page=' . getValue('page', 'int', 'GET', 1) . '&type=' . getValue('type', 'str');

        return '<a href="' . $linkEdit . '">
    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
</a>';
    }

    public function typeDelete($row)
    {
        $linkDelete = 'delete.php?record_id=' . $row->{$this->idField};

        return '<a onclick="return confirm(\'Bạn có chắc muốn xóa bản ghi này?\');" href="' . $linkDelete . '">
    <i class="fa fa-times" aria-hidden="true"></i>
</a>';
    }

    public function typeForceDelete($row)
    {
        $linkDelete = 'delete.php?force=1&record_id=' . $row->{$this->idField};

        return '<a onclick="return confirm(\'Bạn có chắc muốn xóa bản ghi này?\');" href="' . $linkDelete . '">
    <i class="fa fa-times" aria-hidden="true"></i>
</a>';
    }

    public function typeRestore($row)
    {
        $linkDelete = 'restore.php?record_id=' . $row->{$this->idField};

        return '<a title="Khôi phục bản ghi" onclick="return confirm(\'Bạn có chắc muốn khôi phục bản ghi này?\');" href="' . $linkDelete . '">
    <i class="fa fa-times" aria-hidden="true"></i>
</a>';
    }

    public function typeSoftDelete($row)
    {
        if ($row->deleted_at ?? false) {
            return 'Đã xóa';
        }
    }

    public function typeButton($row, $field, $option)
    {
        $options = isset($option['field'][1]) ? $option['field'][1] : [];
        if (empty($options)) {
            return '';
        }

        if (isset($options['conditions']) && is_callable($options['conditions']) && !call_user_func($options['conditions'], $row)) {
            return '';
        }

        return '
<button type="button" class="btn btn-primary" onclick="' . $options['function'] . '(' . $row->{$this->idField} . ')">
  ' . ($options['button_title'] ?? 'Mở') . '
</button>
        ';
    }

    public function typeModal($row, $field, $option)
    {
        $layout_options = isset($option['field'][1]) ? $option['field'][1] : [];
        if (empty($layout_options)) {
            return '';
        }

        if (isset($layout_options['conditions']) && is_callable($layout_options['conditions']) && !call_user_func($layout_options['conditions'], $row)) {
            return '';
        }

        if (is_array($layout_options['vars'] ?? false)) {
            extract($layout_options['vars']);
        }

        return '
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#' . $layout_options['layout'] . '_' . $row->{$this->idField} . '">
  ' . ($layout_options['button_title'] ?? 'Mở') . '
</button>
<div class="modal" id="' . $layout_options['layout'] . '_' . $row->{$this->idField} . '">
  <div class="modal-dialog">
    <div class="modal-content">
        ' . $this->viewRender->view()->make($layout_options['layout'], get_defined_vars())->render() . '
    </div>
  </div>
</div>
        ';
    }

    public function typeHref($row, $field)
    {

        return '<a target="_blank" href="' . $this->fieldValue($row, $field) . '">' . $this->fieldValue($row, $field) . '</a>';
    }

    public function typeDatetime($row, $field, $options)
    {

        $datetime = $this->fieldValue($row, $field);
        if ($datetime) {
            $format = $options['field'][1] ?? 'H:i:s d/m/Y';
            $datetime = new DateTime($datetime);

            return $datetime->format($format);
        }

        return '';
    }


    public function export()
    {
		ini_set("memory_limit","500M");
        disable_debug_bar();
        if(!empty($this->dataExport)){
            $items = $this->dataExport;
        }else{
            $items = $this->model->pagination(1, $this->totalRecord)->all();
        }
        

        $items = $items->data ?: $items;
        $datas = [];
        $i = 1;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $customerData = [['No', 'Phone', 'IP', 'City', 'Total visit', 'Link', 'Time', 'Time on site']];

        //Set header
        $tmpData = ['#'];
        foreach ($this->fields as $field => $fieldOption) {
            if ($fieldOption['export'] ?? false)
                $tmpData[] = $fieldOption['label'];
        }
        $datas[] = $tmpData;

        //Set sheet content
        foreach ($items AS $item) {

            $tmpData = [$i];
            foreach ($this->fields as $field => $fieldOption) {
                if ($fieldOption['export'] ?? false)
                    $tmpData[] = html_entity_decode(strip_tags($this->getFieldString($item, $field)));
                }
            $i++;
            $datas[] = $tmpData;
        }

        $i = 1;
        $tempFirstRow = reset($datas);
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF'];
        foreach ($datas as $data) {
            $j = 0;
            foreach ($data as $value) {
                $sheet->setCellValue($columns[$j] . $i, (string)$value);
                $j++;
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="export.xlsx"');

        $writer->save('php://output');

        die();

    }
}