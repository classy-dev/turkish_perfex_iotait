<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'order_no',
    'name',
//     db_prefix() . 'currencies_exchange.name',
];
$sIndexColumn = 'cate_id';
$sTable       = db_prefix() . 'stock_categories';

//$join = [];
$join = [
//     'LEFT JOIN ' . db_prefix() . 'currencies_exchange ON ' . db_prefix() . 'currencies_exchange.id = ' . db_prefix() . 'pricing_categories.default_currency',
];

$additionalSelect = [
    db_prefix() . 'stock_categories.cate_id',

];

$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, [], $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];

        $attributes = [
            'data-toggle'             => 'modal',
            'data-target'             => '#stock_category_modal',
            'data-id'                 => $aRow['cate_id'],
        ];

        if ($aColumns[$i] == 'name') {
            $_data = '<span class="name"><a href="#" ' . _attributes_to_string($attributes) . '>' . $_data . '</a></span>';
        }
        $row[] = $_data;
    }
    $options = icon_btn('#' . $aRow['cate_id'], 'pencil-square-o', 'btn-default', $attributes);


    // $row[]              = $options .= icon_btn('warehouses/stock_category_delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
    $row[]              = $options;
    $output['aaData'][] = $row;
}
