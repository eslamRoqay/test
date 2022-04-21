<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('image', '<img class="img-thumbnail" src="{{$image}}" style="height: 75px; width: 75px;">')
            ->addColumn('action', 'dashboard.user.parts.action')
            ->addColumn('status', 'dashboard.user.parts.status')
            ->addColumn('address', 'dashboard.user.parts.address')
            ->addColumn('id', function ($data) {
                return "<input type='checkbox' name='data[]' class='data-item' value='{$data['id']}'/> ";
            })
            ->rawColumns(['status','address','action','id','image']);
    }


    public function query(User $model)
    {
        return $model->newQuery()->orderBy('created_at','desc');
    }


    public function html()
    {
        return $this->builder()
                    ->setTableId('user-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->lengthMenu(
                [
                    [2,10, 25, 50, -1],
                    ['2 صـفوف','10 صـفوف', '25 صـف', '50 صـف', 'كل الصـفوف']
                ])
            ->parameters([
                'language' => [ app()->getLocale()=='en' ? : 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Arabic.json']

            ])
            ->parameters([
                'initComplete' => "function () {
                    this.api().columns([2]).every(function () {
                        var column = this;
                        var input = document.createElement(\"input\");
                        $(input).attr( 'class', 'form-control');
                        $(input).attr( 'placeholder', 'اسم العميل');
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                    this.api().columns([3]).every(function () {
                        var column = this;
                        var input = document.createElement(\"input\");
                        $(input).attr( 'class', 'form-control');
                        $(input).attr( 'placeholder', 'اسم الهاتف');
                        $(input).appendTo($(column.footer()).empty())
                        .on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }",
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::make('id')
                ->title("<input type='checkbox' id='DataSelect' class='select-checkbox'>")
                ->orderable(false)
                ->width(30),
            Column::make('image')->title('الصورة'),
            Column::make('name')->title('الاسم'),
            Column::make('phone')->title('رقم الهاتف'),
            Column::make('status')->title('التفعيل'),
            Column::make('address')->title('العناوين'),
            Column::make('action')->title('الاجرائات'),
        ];
    }

    protected function filename()
    {
        return 'User_' . date('YmdHis');
    }
}
