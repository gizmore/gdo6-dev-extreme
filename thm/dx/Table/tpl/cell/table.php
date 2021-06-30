<?php use GDO\Form\GDT_Form;
use GDO\Core\Method;
use GDO\Table\Module_Table;
use GDO\Core\Website;
use GDO\Util\Javascript;
/**
 * This template does quite a lot ugly js generation for a nice dxgrid experience.
 */
/** @var $field \GDO\Table\GDT_Table **/
/** @var $form GDT_Form **/
?>
<div <?=$field->htmlID()?>></div>
<?php
$href = $_SERVER['REQUEST_URI'] . '&fmt=json';
$id = $field->id();
$ipp = $field->pagemenu ? Module_Table::instance()->cfgItemsPerPage() : $field->getResult()->numRows();
$columns = [];
$pk = null;
$loadError = t('err_load_data');
foreach ($field->getHeaderFields() as $gdt)
{
    if ($gdt->hidden || (!$gdt->isSerializable()))
    {
        continue;
    }
    $pk = $pk ? $pk : $gdt;
    $columns[] = '{ dataField: "'.$gdt->name.'", caption: "'.$gdt->displayLabel().'",
allowGrouping: '.($gdt->groupable?'true':'false').',
cellTemplate: function (container, options) {
  let v = options.value;
  if (typeof v === "string" || v instanceof String) {
    $("<span>"+options.value+"</span>").appendTo(container);
  }
  else if (v !== null && v !== undefined) {
    container.append(v);
  }
} }'.PHP_EOL;
}
$columns = implode(',', $columns);
$pk = $pk->name;
$name = $field->name;
$script_html = <<<EOS
var cstore_{$id} = new DevExpress.data.CustomStore({
    key: "$pk",
//     update: function (loadOptions) {
//         let args = {};
//         for (i in loadOptions) {
//             if (i in loadOptions && isNotEmpty(loadOptions[i])) {
//                 args[i] = loadOptions[i];
//             }
//         }
//         let deferred = $.Deferred();
//         $.ajax({
//             url: "{$href}&update=1",
//             dataType: "json",
//             data: args,
//             success: function(result) {
//                 deferred.resolve(result.json.{$name}.data, {
//                     totalCount: result.json.{$name}.total,
// //                     summary: result.summary,
// //                     groupCount: result.groupCount
//                 });
//             },
//             error: function() {
//                 deferred.reject('{$loadError}');
//             },

//         return deferred.promise();
//     },
    load: function (loadOptions) {
        var deferred = $.Deferred(),
            args = {};

        [
            "skip",
            "take",
            "requireTotalCount",
            "requireGroupCount",
            "sort",
            "filter",
            "totalSummary",
            "group",
            "groupSummary"
        ].forEach(function(i) {
            if (i in loadOptions && isNotEmpty(loadOptions[i]))
                args[i] = JSON.stringify(loadOptions[i]);
        });
console.log(args);
        $.ajax({
            url: "{$href}",
            dataType: "json",
            data: args,
            success: function(result) {
                deferred.resolve(result.json.{$name}.data, {
                    totalCount: result.json.{$name}.total,
//                     summary: result.summary,
//                     groupCount: result.groupCount
                });
            },
            error: function() {
                deferred.reject('{$loadError}');
            },
            timeout: 5000
        });

        return deferred.promise();
    }
});

let store_{$id} = DevExpress.data.AspNet.createStore({
    key: "{$pk}",
    loadUrl: "{$href}",
    insertUrl: "{$href}&create=true",
    updateUrl: "{$href}&update=true",
    deleteUrl: "{$href}&delete=true",
    onBeforeSend: function(method, ajaxOptions) {
        ajaxOptions.xhrFields = { withCredentials: true };
    }
});


$(function() {
    $("#{$id}").dxDataGrid({
        dataSource: store_{$id},
        editing: {
            mode: "cell",
            allowUpdating: true,
            allowAdding: true,
            allowDeleting: true
        },
        pager: {
            visible: true,
            showPageSizeSelector: true,
            allowedPageSizes: [10, 20, 50, 100],
            showNavigationButtons: true,
              showInfo: true,
            showPageSizeSelector: true,
            showNavigationButtons: true
      },
        remoteOperations: false,
        searchPanel: {
            visible: true,
            highlightCaseSensitive: true
        },
        groupPanel: { visible: true },
        grouping: {
            autoExpandAll: false
        },
        columnAutoWidth: true,
        allowColumnReordering: true,
        rowAlternationEnabled: true,
        showBorders: true,
        columns: [ {$columns} ]
    });
});
EOS;
Javascript::addJavascriptInline($script_html);
