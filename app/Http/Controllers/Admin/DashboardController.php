<?php

namespace App\Http\Controllers\Admin;

use App\Models\GalleryImage;
use App\Models\Inquiry;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\Post;

final class DashboardController extends AdminController
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'publishedPosts' => Post::published()->count(),
            'visibleImages' => GalleryImage::visible()->count(),
            'newAppointments' => Inquiry::where('kind', 'appointment')->where('status', 'new')->count(),
            'newMessages' => Inquiry::where('kind', 'contact')->where('status', 'new')->count(),
            'pageCount' => Page::count(), 'mediaCount' => MediaAsset::count(),
        ]);
    }
}
