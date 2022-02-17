<?php

namespace ACME\Articles\Models;

use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Support\Facades\Storage;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Core\Eloquent\TranslatableModel;
use ACME\Articles\Contracts\Article as ArticleContract;

class Article extends TranslatableModel implements ArticleContract
{
    use NodeTrait;

    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'url_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    protected $with = ['translations'];

    /**
     * Get image url for the article image.
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Get image url for the article image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }
}