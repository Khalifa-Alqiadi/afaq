<script>
document.addEventListener('DOMContentLoaded', function () {
    var hasTweet = !!document.querySelector('blockquote.twitter-tweet');
    var hasFacebook = !!document.querySelector('.fb-post, iframe[src^="https://www.facebook.com/plugins/post.php"]');
    if (!hasTweet && !hasFacebook) return;

    if (hasTweet) {
        var twitterScript = document.createElement('script');
        twitterScript.src = 'https://platform.twitter.com/widgets.js';
        twitterScript.async = true;
        twitterScript.charset = 'utf-8';
        document.body.appendChild(twitterScript);
    }

    if (!hasFacebook) return;

    function resolveFacebook(url) {
        var parsed = new URL(url);
        var match = parsed.pathname.match(/^\/share\/([A-Za-z0-9]{8,32})\/?$/);
        if (!match) return Promise.resolve(parsed.href);
        var endpoint = @json(route('facebookShareResolve', ['token' => '__TOKEN__'])).replace('__TOKEN__', match[1]);
        return fetch(endpoint)
            .then(function (response) { if (!response.ok) throw new Error('resolve'); return response.json(); })
            .then(function (result) { return result.url; });
    }

    function setPost(frame, link, url) {
        resolveFacebook(url).then(function (resolvedUrl) {
            frame.src = 'https://www.facebook.com/plugins/post.php?href=' + encodeURIComponent(resolvedUrl) + '&show_text=true&width=500';
            if (link) link.href = resolvedUrl;
        }).catch(function () {
            // Keep the direct link available when Facebook rejects the shared URL.
            frame.remove();
        });
    }

    // Convert content saved while the SDK widget was in use.
    document.querySelectorAll('.fb-post').forEach(function (post) {
        try {
            var oldLink = post.querySelector('a[href]');
            var postUrl = post.dataset.href || (oldLink && oldLink.href);
            if (!postUrl) return;
            var wrapper = document.createElement('div');
            wrapper.className = 'facebook-post-embed';
            var frame = document.createElement('iframe');
            frame.width = '500';
            frame.height = '700';
            frame.loading = 'lazy';
            frame.setAttribute('scrolling', 'yes');
            frame.setAttribute('frameborder', '0');
            frame.setAttribute('allowfullscreen', 'true');
            var link = oldLink || document.createElement('a');
            link.href = postUrl;
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            link.textContent = @json(__('backend.facebook'));
            wrapper.appendChild(frame);
            wrapper.appendChild(link);
            post.replaceWith(wrapper);
            setPost(frame, link, postUrl);
        } catch (error) { /* Leave unrelated content unchanged. */ }
    });

    // Refresh older iframe embeds and expand them to show the complete post.
    document.querySelectorAll('iframe[src^="https://www.facebook.com/plugins/post.php"]').forEach(function (frame) {
        try {
            var postUrl = new URL(frame.src).searchParams.get('href');
            if (!postUrl) return;
            frame.width = '500';
            frame.height = '700';
            frame.setAttribute('scrolling', 'yes');
            var wrapper = frame.closest('.facebook-post-embed');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'facebook-post-embed';
                frame.parentNode.insertBefore(wrapper, frame);
                wrapper.appendChild(frame);
            }
            var link = wrapper.querySelector('a[href]');
            if (!link) {
                link = document.createElement('a');
                link.href = postUrl;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.textContent = @json(__('backend.facebook'));
                wrapper.appendChild(link);
            }
            setPost(frame, link, postUrl);
        } catch (error) { /* Leave unrelated frames unchanged. */ }
    });
});
</script>
<style>
.facebook-post-embed{display:block;max-width:500px;margin:1.25rem auto;text-align:center}
.facebook-post-embed iframe{display:block;width:100%!important;max-width:500px!important;height:700px!important;margin:0 auto;border:0}
.facebook-post-embed>a,.facebook-post-embed>p>a{display:inline-block;margin-top:.5rem}
</style>
