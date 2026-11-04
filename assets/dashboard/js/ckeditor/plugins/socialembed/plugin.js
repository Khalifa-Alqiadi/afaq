(function () {
    CKEDITOR.plugins.add('socialembed', {
        init: function (editor) {
            var arabic = editor.langCode && editor.langCode.indexOf('ar') === 0;
            var labels = arabic
                ? {tweet: 'تضمين تغريدة', facebook: 'تضمين منشور فيسبوك', prompt: 'ألصق رابط المنشور', invalid: 'رابط المنشور غير صالح', facebookLink: 'تعذر الوصول إلى المنشور. تأكد أن الرابط مباشر أو رابط مشاركة لمنشور عام'}
                : {tweet: 'Embed X post', facebook: 'Embed Facebook post', prompt: 'Paste the post URL', invalid: 'Invalid post URL', facebookLink: 'Could not reach the post. Use a direct or share URL for a public post'};

            function insertFacebook(url) {
                var src = 'https://www.facebook.com/plugins/post.php?href=' + encodeURIComponent(url.href) + '&show_text=true&width=500';
                editor.insertHtml('<div class="facebook-post-embed"><iframe src="' + CKEDITOR.tools.htmlEncode(src) + '" width="500" height="700" style="border:0;max-width:100%" scrolling="yes" frameborder="0" allowfullscreen="true" loading="lazy"></iframe><p><a href="' + CKEDITOR.tools.htmlEncode(url.href) + '" target="_blank" rel="noopener noreferrer">' + labels.facebook + '</a></p></div><p>&nbsp;</p>');
            }

            function insert(type) {
                var value = window.prompt(labels.prompt);
                if (value === null) return;
                var url;
                try { url = new URL(value.trim()); } catch (error) { window.alert(labels.invalid); return; }
                if (url.protocol !== 'https:') { window.alert(labels.invalid); return; }

                var host = url.hostname.toLowerCase().replace(/^www\./, '');
                if (type === 'tweet') {
                    if (!['x.com', 'twitter.com', 'mobile.twitter.com'].includes(host) || !/^\/[A-Za-z0-9_]+\/status\/\d+\/?$/.test(url.pathname)) {
                        window.alert(labels.invalid); return;
                    }
                    editor.insertHtml('<blockquote class="twitter-tweet"><a href="' + CKEDITOR.tools.htmlEncode(url.origin + url.pathname) + '">' + CKEDITOR.tools.htmlEncode(url.origin + url.pathname) + '</a></blockquote><p>&nbsp;</p>');
                } else {
                    if (['facebook.com', 'm.facebook.com', 'web.facebook.com'].includes(host) && /^\/share\/([A-Za-z0-9]{8,32})\/?$/.test(url.pathname)) {
                        var token = url.pathname.split('/')[2];
                        fetch(editor.config.facebookShareResolver.replace('__TOKEN__', token), {credentials: 'same-origin'})
                            .then(function (response) { if (!response.ok) throw new Error('resolve'); return response.json(); })
                            .then(function (result) { insertFacebook(new URL(result.url)); })
                            .catch(function () { window.alert(labels.facebookLink); });
                        return;
                    }
                    if (!['facebook.com', 'm.facebook.com', 'web.facebook.com'].includes(host) ||
                        !(/\/posts\/[^/]+\/?$/.test(url.pathname) || ['/permalink.php', '/story.php'].includes(url.pathname))) {
                        window.alert(labels.facebookLink); return;
                    }
                    insertFacebook(url);
                }
            }

            editor.addCommand('insertTweet', {exec: function () { insert('tweet'); }});
            editor.addCommand('insertFacebookPost', {exec: function () { insert('facebook'); }});
            editor.ui.addButton('EmbedTweet', {label: labels.tweet, command: 'insertTweet', toolbar: 'insert,30', icon: this.path + 'icons/embedtweet.svg'});
            editor.ui.addButton('EmbedFacebookPost', {label: labels.facebook, command: 'insertFacebookPost', toolbar: 'insert,31', icon: this.path + 'icons/embedfacebookpost.svg'});
        }
    });
}());
