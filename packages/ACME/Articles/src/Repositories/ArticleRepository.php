<?php

namespace ACME\Articles\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use ACME\Articles\Models\ArticleTranslationProxy;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return 'ACME\Articles\Contracts\Article';
    }

    /**
     * Create Article.
     *
     * @param  array  $data
     * @return \ACME\Articles\Contracts\Article
     */
    public function create(array $data)
    {
        // Event::dispatch('article.create.before');

        if (isset($data['locale']) && $data['locale'] == 'all') {
            $model = app()->make($this->model());

            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if (isset($data[$attribute])) {
                        $data[$locale->code][$attribute] = $data[$attribute];
                        $data[$locale->code]['locale_id'] = $locale->id;
                    }
                }
            }
        }

        $article = $this->model->create($data);

        $this->uploadImages($data, $article);

        // Event::dispatch('article.create.after', $article);

        return $article;
    }


    /**
     * Checks slug is unique or not based on locale.
     *
     * @param  int  $id
     * @param  string  $slug
     * @return bool
     */
    public function isSlugUnique($id, $slug)
    {
        $exists = ArticleTranslationProxy::modelClass()::where('article_id', '<>', $id)
            ->where('slug', $slug)
            ->limit(1)
            ->select(DB::raw(1))
            ->exists();

        return $exists ? false : true;
    }

    /**
     * Retrive Article from slug.
     *
     * @param string $slug
     * @return \ACME\Articles\Contracts\Article
     */
    public function findBySlugOrFail($slug)
    {
        $article = $this->model->whereTranslation('slug', $slug)->first();

        if ($article) {
            return $article;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model),
            $slug
        );
    }

    /**
     * Retrive Article from slug.
     *
     * @param string $slug
     * @return \ACME\Articles\Contracts\Article
     */
    public function findBySlug($slug)
    {
        $article = $this->model->whereTranslation('slug', $slug)->first();

        if ($article) {
            return $article;
        }
    }

    /**
     * Find by path.
     *
     * @param  string  $urlPath
     * @return \ACME\Articles\Contracts\Article
     */
    public function findByPath(string $urlPath)
    {
        return $this->model->whereTranslation('url_path', $urlPath)->first();
    }

    /**
     * Update Article.
     *
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return \ACME\Articles\Contracts\Article
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $article = $this->find($id);

        // Event::dispatch('article.update.before', $id);

        $data = $this->setSameAttributeValueToAllLocale($data, 'slug');

        $article->update($data);

        $this->uploadImages($data, $article);



        // Event::dispatch('article.update.after', $id);

        return $article;
    }

    /**
     * Delete Article.
     *
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        // Event::dispatch('article.delete.before', $id);

        parent::delete($id);

        // Event::dispatch('article.delete.after', $id);
    }

    /**
     * Upload Article's images.
     *
     * @param  array  $data
     * @param  \ACME\Articles\Contracts\Article  $article
     * @param  string $type
     * @return void
     */
    public function uploadImages($data, $article, $type = "image")
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type . '.' . $imageId;
                $dir = 'Article/' . $article->id;

                if ($request->hasFile($file)) {
                    if ($article->{$type}) {
                        Storage::delete($article->{$type});
                    }

                    $article->{$type} = $request->file($file)->store($dir);
                    $article->save();
                }
            }
        } else {
            if ($article->{$type}) {
                Storage::delete($article->{$type});
            }

            $article->{$type} = null;
            $article->save();
        }
    }

    /**
     * Get partials.
     *
     * @param  array|null  $columns
     * @return array
     */
    public function getPartial($columns = null)
    {
        $categories = $this->model->all();

        $trimmed = [];

        foreach ($categories as $key => $article) {
            if ($article->name != null || $article->name != "") {
                $trimmed[$key] = [
                    'id'   => $article->id,
                    'name' => $article->name,
                    'slug' => $article->slug,
                ];
            }
        }

        return $trimmed;
    }

    /**
     * Set same value to all locales in Article.
     *
     * To Do: Move column from the `Article_translations` to `Article` table. And remove
     * this created method.
     *
     * @param  array  $data
     * @param  string $attributeNames
     * @return array
     */
    private function setSameAttributeValueToAllLocale(array $data, ...$attributeNames)
    {
        $requestedLocale = core()->getRequestedLocaleCode();

        $model = app()->make($this->model());

        foreach ($attributeNames as $attributeName) {
            foreach (core()->getAllLocales() as $locale) {
                foreach ($model->translatedAttributes as $attribute) {
                    if ($attribute === $attributeName) {
                        $data[$locale->code][$attribute] = $data[$requestedLocale][$attribute];
                    }
                }
            }
        }

        return $data;
    }
}