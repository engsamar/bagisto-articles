<?php

namespace ACME\Articles\Datagrids;

use Illuminate\Support\Facades\DB;
use Webkul\Ui\DataGrid\DataGrid;

class ArticleDataGrid extends DataGrid
{
    protected $index = 'article_id';

    protected $sortOrder = 'desc';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('articles as article')
            ->select(
                'article.id as article_id',
                'article_t.name',
                'article.position',
                'article.status',
                'article_t.locale',
            )
            ->leftJoin('article_translations as article_t', function ($leftJoin) {
                $leftJoin->on('article.id', '=', 'article_t.article_id')->where('article_t.locale', app()->getLocale());
            })

            ->groupBy('article.id');


        $this->addFilter('status', 'article.status');
        $this->addFilter('article_id', 'article.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index'      => 'article_id',
            'label'      => trans('admin::app.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('admin::app.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'position',
            'label'      => trans('admin::app.datagrid.position'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('admin::app.datagrid.status'),
            'type'       => 'boolean',
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
            'wrapper'    => function ($value) {
                if ($value->status == 1) {
                    return trans('admin::app.datagrid.active');
                } else {
                    return trans('admin::app.datagrid.inactive');
                }
            },
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'title'  => trans('admin::app.datagrid.edit'),
            'method' => 'GET',
            'route'  => 'admin.articles.edit',
            'icon'   => 'icon pencil-lg-icon',
        ]);

        $this->addAction([
            'title'        => trans('admin::app.datagrid.delete'),
            'method'       => 'POST',
            'route'        => 'admin.articles.delete',
            'confirm_text' => trans('ui::app.datagrid.massaction.delete'),
            'icon'         => 'icon trash-icon',
            'function'     => 'deleteFunction($event, "delete")'
        ]);

        // $this->addMassAction([
        //     'type'   => 'delete',
        //     'label'  => trans('admin::app.datagrid.delete'),
        //     'action' => route('admin.articles.massdelete'),
        //     'method' => 'POST',
        // ]);
    }
}