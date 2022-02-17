@extends('admin::layouts.content')

@section('page_title')
    {{ __('articles::app.articles.edit-title') }}
@stop

@section('content')
    <div class="content">
        @php
            $locale = core()->getRequestedLocaleCode();
        @endphp

        <form method="POST" action="" @submit.prevent="onSubmit" enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link"
                            onclick="window.location = '{{ route('admin.articles.index') }}'"></i>

                        {{ __('articles::app.articles.edit-title') }}
                    </h1>

                    <div class="control-group">
                        <select class="control" id="locale-switcher" onChange="window.location.href = this.value">
                            @foreach (core()->getAllLocales() as $localeModel)

                                <option
                                    value="{{ route('admin.articles.update', $article->id) . '?locale=' . $localeModel->code }}"
                                    {{ $localeModel->code == $locale ? 'selected' : '' }}>
                                    {{ $localeModel->name }}
                                </option>

                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="page-action">
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('articles::app.articles.save-btn-title') }}
                    </button>
                </div>
            </div>

            <div class="page-content">
                <div class="form-container">
                    @csrf()
                    <input name="_method" type="hidden" value="PUT">

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.before', ['category' => $article]) !!}

                    <accordian :title="'{{ __('articles::app.articles.general') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.before', ['category' => $article]) !!}

                            <div class="control-group"
                                :class="[errors.has('{{ $locale }}[name]') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('articles::app.articles.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name"
                                    name="{{ $locale }}[name]"
                                    value="{{ old($locale)['name'] ?? ($article->translate($locale)['name'] ?? '') }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.name') }}&quot;"
                                    v-slugify-target="'slug'" />
                                <span class="control-error"
                                    v-if="errors.has('{{ $locale }}[name]')">@{{ errors . first('{!!$locale!!}[name]') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('status') ? 'has-error' : '']">
                                <label for="status" class="required">{{ __('articles::app.articles.visible') }}</label>
                                <select class="control" v-validate="'required'" id="status" name="status"
                                    data-vv-as="&quot;{{ __('articles::app.articles.visible') }}&quot;">
                                    <option value="1" {{ $article->status ? 'selected' : '' }}>
                                        {{ __('articles::app.articles.yes') }}
                                    </option>
                                    <option value="0" {{ $article->status ? '' : 'selected' }}>
                                        {{ __('articles::app.articles.no') }}
                                    </option>
                                </select>
                                <span class="control-error"
                                    v-if="errors.has('status')">@{{ errors . first('status') }}</span>
                            </div>

                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position"
                                    class="required">{{ __('articles::app.articles.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position"
                                    name="position" value="{{ old('position') ?: $article->position }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.position') }}&quot;" />
                                <span class="control-error"
                                    v-if="errors.has('position')">@{{ errors . first('position') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.controls.after', ['category' => $article]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.general.after', ['category' => $article]) !!}


                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.description_images.before', ['category' => $article]) !!}

                    <accordian :title="'{{ __('articles::app.articles.description-and-images') }}'" :active="true">
                        <div slot="body">

                            <description></description>

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('articles::app.articles.image') }}</label>

                                <image-wrapper :button-label="'{{ __('articles::app.articles.add-image-btn-title') }}'"
                                    input-name="image" :multiple="false" :images='"{{ $article->image_url }}"'>
                                </image-wrapper>

                                <span class="control-error" v-if="{!! $errors->has('image.*') !!}">
                                    @foreach ($errors->get('image.*') as $key => $message)
                                        @php echo str_replace($key, 'Image', $message[0]); @endphp
                                    @endforeach
                                </span>

                            </div>


                        </div>
                    </accordian>


                    <accordian :title="'{{ __('articles::app.articles.seo') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.before', ['category' => $article]) !!}

                            <div class="control-group">
                                <label for="meta_title">{{ __('articles::app.articles.meta_title') }}</label>
                                <input type="text" class="control" id="meta_title" name="{{ $locale }}[meta_title]"
                                    value="{{ old($locale)['meta_title'] ?? ($article->translate($locale)['meta_title'] ?? '') }}" />
                            </div>

                            <div class="control-group"
                                :class="[errors.has('{{ $locale }}[slug]') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('articles::app.articles.slug') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="slug"
                                    name="{{ $locale }}[slug]"
                                    value="{{ old($locale)['slug'] ?? ($article->translate($locale)['slug'] ?? '') }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.slug') }}&quot;" v-slugify />
                                <span class="control-error"
                                    v-if="errors.has('{{ $locale }}[slug]')">@{{ errors . first('{!!$locale!!}[slug]') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('articles::app.articles.meta_description') }}</label>
                                <textarea class="control" id="meta_description"
                                    name="{{ $locale }}[meta_description]">{{ old($locale)['meta_description'] ?? ($article->translate($locale)['meta_description'] ?? '') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('articles::app.articles.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords"
                                    name="{{ $locale }}[meta_keywords]">{{ old($locale)['meta_keywords'] ?? ($article->translate($locale)['meta_keywords'] ?? '') }}</textarea>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.controls.after', ['category' => $article]) !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.edit_form_accordian.seo.after', ['category' => $article]) !!}

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="description-template">

        <div class="control-group" :class="[errors.has('{{ $locale }}[description]') ? 'has-error' : '']">
                                            <label for="description" :class="isRequired ? 'required' : ''">{{ __('articles::app.articles.description') }}</label>
                                            <textarea v-validate="isRequired ? 'required' : ''" class="control" id="description" name="{{ $locale }}[description]" data-vv-as="&quot;{{ __('articles::app.articles.description') }}&quot;">{{ old($locale)['description'] ?? ($article->translate($locale)['description'] ?? '') }}</textarea>
                                            <span class="control-error" v-if="errors.has('{{ $locale }}[description]')">@{{ errors . first('{!!$locale!!}[description]') }}</span>
                                        </div>

                                    </script>

    <script>
        $(document).ready(function() {
            tinymce.init({
                selector: 'textarea#description',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true
            });
        });

        Vue.component('description', {

            template: '#description-template',

            inject: ['$validator'],

            data: function() {
                return {
                    isRequired: true,
                }
            },

            created: function() {
                var this_this = this;

                $(document).ready(function() {
                    $('#display_mode').on('change', function(e) {
                        if ($('#display_mode').val() != 'products_only') {
                            this_this.isRequired = true;
                        } else {
                            this_this.isRequired = false;
                        }
                    })

                    if ($('#display_mode').val() != 'products_only') {
                        this_this.isRequired = true;
                    } else {
                        this_this.isRequired = false;
                    }
                });
            }
        })

    </script>
@endpush
