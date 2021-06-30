<?php use GDO\Form\GDT_Form;
use GDO\Core\Method;
use GDO\Table\Module_Table;
use GDO\Core\Website;
use GDO\Util\Javascript;
use GDO\Date\GDT_Date;
use GDO\Date\GDT_DateTime;
use GDO\DB\GDT_Int;
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
$editable = $field->editable ? 'true' : 'false';
$paginated = $field->pagemenu ? 'true' : 'false';
$h = $field->headers->name;
foreach ($field->getHeaderFields() as $gdt)
{
    if ($gdt->hidden || (!$gdt->isSerializable()))
    {
        continue;
    }
    $pk = $pk ? $pk : $gdt->name;
    if ($gdt instanceof GDT_DateTime) $type = 'datetime';
    elseif ($gdt instanceof GDT_Date) $type = 'date';
    elseif ($gdt instanceof GDT_Int) $type = 'number';
    else $type = 'string';
    $columns[] = '{
dataField: "'.$gdt->name.'",
caption: "'.$gdt->displayLabel().'",
dataType: "'.$type.'",
allowEditing: '.($gdt->editable?'true':'false').',
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
// $pk = $pk->name;
$name = $field->name;
$script_html = <<<EOS
let store_{$id} = new DevExpress.data.CustomStore({
    key: "$pk",
    load: function (loadOptions) {
        console.log(loadOptions);
        var deferred = $.Deferred(),
        args = { {$h}: {} };
        for (i in loadOptions) {
            if (loadOptions[i]) {
                if (i === 'take') {
                    args.{$h}.ipp = loadOptions[i];
                }
                if (i === 'skip') {
                    args.{$h}.page = loadOptions[i] / {$ipp} + 1;
                }
                if (i === 'filter') {
                    args.{$h}.f = {};
                    let f = loadOptions[i];
                    if (f.filterValue) {
                        args.{$h}.f[f[0]] = f[2];
                    }
                    for (j in loadOptions[i]) {
                        f = loadOptions[i][j];
                        if (f.filterValue) {
                            args.{$h}.f[f[0]] = f[2];
                        }
                    }
                }
            }
        }

        console.log(args);
        $.ajax({
            url: "{$href}",
            dataType: "json",
            data: args,
            success: function(result) {
                deferred.resolve(result.json.{$name}.data, {
                    totalCount: result.json.{$name}.total
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

let source_{$id} = new DevExpress.data.DataSource({
    key: "$pk",
    store: store_{$id},
    paginate: {$paginated},
    pageSize: {$ipp}
});

$(function() {
    $("#{$id}").dxDataGrid({
        dataSource: source_{$id},
        remoteOperations: {
            paging: true,
            filtering: true,
            sorting: true,
            grouping: true,
            summary: true,
            groupPaging: true
        },
        filterRow: {
            visible: true
        },
        headerFilter: {
            visible: true
        },
        editing: {
            mode: "form",
            form: {
                colCount: 4
            },
            allowUpdating: {$editable},
            allowAdding: {$editable},
            allowDeleting: {$editable}
        },
        pager: {
            visible: $paginated,
            showPageSizeSelector: true,
            allowedPageSizes: [10, 20, 50, 100],
            showNavigationButtons: true,
            showInfo: true,
            showPageSizeSelector: true,
            showNavigationButtons: true
        },
        paging: {
            enabled: $paginated,
            pageSize: $ipp
        },
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
