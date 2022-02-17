@extends('admin::layouts.content')

@section('page_title')
    {{ __('articles::app.articles.title') }}
@stop

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h1>{{ __('articles::app.articles.title') }}</h1>
            </div>

            <div class="page-action">
                <a href="{{ route('admin.articles.create') }}" class="btn btn-lg btn-primary">
                    {{ __('articles::app.articles.add-title') }}
                </a>
            </div>
        </div>

        {!! view_render_event('bagisto.admin.articles.list.before') !!}

        <div class="page-content">
            {!! app('ACME\Articles\DataGrids\ArticleDataGrid')->render() !!}
        </div>

        {!! view_render_event('bagisto.admin.articles.list.after') !!}
    </div>
@stop

@push('scripts')
    <script>
        $(document).ready(function() {
            $("input[type='checkbox']").change(deleteFunction);
        });

        var deleteFunction = function(e, type) {
            if (type == 'delete') {
                var indexes = $(e.target).parent().attr('id');
            } else {
                $("input[type='checkbox']").attr('disabled', true);

                var formData = {};
                $.each($('form').serializeArray(), function(i, field) {
                    formData[field.name] = field.value;
                });

                var indexes = formData.indexes;
            }
        }

    </script>
@endpush
