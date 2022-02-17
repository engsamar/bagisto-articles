@extends('admin::layouts.content')

@section('page_title')
    {{ __('articles::app.articles.add-title') }}
@stop

@section('content')
    <div class="content">

        <form method="POST" action="{{ route('admin.articles.store') }}" @submit.prevent="onSubmit"
            enctype="multipart/form-data">

            <div class="page-header">
                <div class="page-title">
                    <h1>
                        <i class="icon angle-left-icon back-link"
                            onclick="window.location = '{{ route('admin.articles.index') }}'"></i>

                        {{ __('articles::app.articles.add-title') }}
                    </h1>
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
                    <input type="hidden" name="locale" value="all" />

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.before') !!}

                    <accordian :title="'{{ __('articles::app.articles.general') }}'" :active="true">
                        <div slot="body">

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.before') !!}

                            <div class="control-group" :class="[errors.has('name') ? 'has-error' : '']">
                                <label for="name" class="required">{{ __('articles::app.articles.name') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="name" name="name"
                                    value="{{ old('name') }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.name') }}&quot;"
                                    v-slugify-target="'slug'" />
                                <span class="control-error" v-if="errors.has('name')">@{{ errors . first('name') }}</span>
                            </div>


                            <div class="control-group" :class="[errors.has('position') ? 'has-error' : '']">
                                <label for="position"
                                    class="required">{{ __('articles::app.articles.position') }}</label>
                                <input type="text" v-validate="'required|numeric'" class="control" id="position"
                                    name="position" value="{{ old('position') }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.position') }}&quot;" />
                                <span class="control-error"
                                    v-if="errors.has('position')">@{{ errors . first('position') }}</span>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.general.after') !!}


                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.description_images.before') !!}

                    <accordian :title="'{{ __('articles::app.articles.description-and-images') }}'" :active="true">
                        <div slot="body">



                            <description></description>

                            <div class="control-group {!! $errors->has('image.*') ? 'has-error' : '' !!}">
                                <label>{{ __('articles::app.articles.image') }}</label>

                                <image-wrapper :button-label="'{{ __('articles::app.articles.add-image-btn-title') }}'"
                                    input-name="image" :multiple="false"></image-wrapper>

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

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.controls.before') !!}

                            <div class="control-group">
                                <label for="meta_title">{{ __('articles::app.articles.meta_title') }}</label>
                                <input type="text" class="control" id="meta_title" name="meta_title"
                                    value="{{ old('meta_title') }}" />
                            </div>

                            <div class="control-group" :class="[errors.has('slug') ? 'has-error' : '']">
                                <label for="slug" class="required">{{ __('articles::app.articles.slug') }}</label>
                                <input type="text" v-validate="'required'" class="control" id="slug" name="slug"
                                    value="{{ old('slug') }}"
                                    data-vv-as="&quot;{{ __('articles::app.articles.slug') }}&quot;" v-slugify />
                                <span class="control-error"
                                    v-if="errors.has('slug')">@{{ errors . first('slug') }}</span>
                            </div>

                            <div class="control-group">
                                <label for="meta_description">{{ __('articles::app.articles.meta_description') }}</label>
                                <textarea class="control" id="meta_description"
                                    name="meta_description">{{ old('meta_description') }}</textarea>
                            </div>

                            <div class="control-group">
                                <label for="meta_keywords">{{ __('articles::app.articles.meta_keywords') }}</label>
                                <textarea class="control" id="meta_keywords"
                                    name="meta_keywords">{{ old('meta_keywords') }}</textarea>
                            </div>

                            {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.controls.after') !!}

                        </div>
                    </accordian>

                    {!! view_render_event('bagisto.admin.catalog.category.create_form_accordian.seo.after') !!}

                </div>
            </div>

        </form>
    </div>
@stop

@push('scripts')
    <script src="{{ asset('vendor/webkul/admin/assets/js/tinyMCE/tinymce.min.js') }}"></script>

    <script type="text/x-template" id="description-template">

        <div class="control-group" :class="[errors.has('description') ? 'has-error' : '']">
                                                                <label for="description" :class="isRequired ? 'required' : ''">{{ __('articles::app.articles.description') }}</label>
                                                                <textarea v-validate="isRequired ? 'required' : ''"  class="control" id="description" name="description" data-vv-as="&quot;{{ __('articles::app.articles.description') }}&quot;">{{ old('description') }}</textarea>
                                                                <span class="control-error" v-if="errors.has('description')">@{{ errors . first('description') }}</span>
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
                });
            }
        })

    </script>
@endpush
