<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Support\Facades\File;
use App\Models\Banner;
use App\Mail\NotificationEmail;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Topic;
use App\Models\TopicCategory;
use App\Models\TopicField;
use App\Models\User;
use App\Models\Webmail;
use App\Models\WebmasterSection;
use App\Models\WebmasterSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Mail;
use Redirect;
use Helper;
use Auth;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CustomController extends Controller
{

    public function custom_page()
    {
        $PageTitle = "Custom page";

        return view('frontEnd.custom.page', [
            "PageTitle" => $PageTitle,
        ]);
    }

    public function resolveFacebookShare(string $token)
    {
        $url = Cache::remember('facebook-share:'.$token, now()->addDay(), function () use ($token) {
            try {
                $response = Http::withOptions(['allow_redirects' => false])
                    ->timeout(6)
                    ->head('https://www.facebook.com/share/'.$token.'/');
                $location = $response->header('Location');
                $parts = parse_url((string) $location);
                if (!in_array(strtolower($parts['host'] ?? ''), ['www.facebook.com', 'facebook.com'], true)
                    || !preg_match('~^/[0-9]+/posts/[^/]+/?$~', $parts['path'] ?? '')) {
                    return null;
                }
                return 'https://www.facebook.com'.rtrim($parts['path'], '/').'/';
            } catch (\Throwable $exception) {
                return null;
            }
        });

        return $url ? response()->json(['url' => $url]) : response()->json(['message' => 'Post URL unavailable'], 422);
    }

}
