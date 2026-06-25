<?php

namespace Modules\Formtools\Http\Controllers\Home;

use Modules\Formtools\Models\FormPage;
use Modules\Formtools\Support\PageSchemaRenderer;
use Modules\ModulesController;

class PageController extends ModulesController
{
    public function show(string $slug)
    {
        $page = FormPage::query()
            ->with('model')
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return $this->renderPage($page);
    }

    public function legacyRedirect(string $slug)
    {
        $page = FormPage::query()
            ->where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return redirect($page->getPublicUrl(), 301);
    }

    public function renderPage(FormPage $page, bool $isPreview = false, array $options = [])
    {
        $page->loadMissing('model');

        $userAgent = strtolower((string) request()->userAgent());
        $device = 'desktop';
        if (strpos($userAgent, 'ipad') !== false || strpos($userAgent, 'tablet') !== false) {
            $device = 'tablet';
        } elseif (
            strpos($userAgent, 'mobile') !== false
            || strpos($userAgent, 'android') !== false
            || strpos($userAgent, 'iphone') !== false
        ) {
            $device = 'mobile';
        }

        $authUser = auth()->user();
        $publicUrl = trim((string) ($options['publicUrl'] ?? ''));

        return view('formtools::home.page.show', [
            'page' => $page,
            'isPreview' => $isPreview,
            'publicUrl' => $publicUrl !== '' ? $publicUrl : $page->getPublicUrl(),
            'layoutHtml' => PageSchemaRenderer::render($page->layout_schema ?: '', [
                'page_id' => $page->id,
                'page_slug' => $page->slug,
                'page_model_identification' => $page->model->identification ?? '',
                'auth_check' => auth()->check(),
                'auth_user_name' => $this->extractAuthUserName($authUser),
                'auth_user_avatar' => $this->extractAuthUserAvatar($authUser),
                'query' => request()->query(),
                'device' => $device,
            ]),
        ]);
    }

    private function extractAuthUserName($user): string
    {
        if (!$user) {
            return '';
        }

        foreach (['nickname', 'name', 'username', 'realname', 'real_name'] as $field) {
            $value = trim((string) data_get($user, $field, ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    private function extractAuthUserAvatar($user): string
    {
        if (!$user) {
            return '';
        }

        foreach (['avatar', 'headimg', 'head_img', 'photo', 'image', 'thumb'] as $field) {
            $value = trim((string) data_get($user, $field, ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }
}
