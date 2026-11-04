@if(Helper::GeneralWebmasterSettings("text_editor") ==2)
    <script src="{{ asset("assets/dashboard/js/ckeditor/ckeditor.js") }}"></script>
    <script>
        CKEDITOR.addCss('body { font-family: "Cairo", Arial, sans-serif; font-size: 18px; }');
        CKEDITOR.plugins.addExternal('socialembed', '{{ asset('assets/dashboard/js/ckeditor/plugins/socialembed/') }}/', 'plugin.js');
        CKEDITOR.editorConfig = function (config) {
            config.language = '{{ @Helper::currentLanguage()->code }}';
            config.height = 500;
            config.uiColor = '#ffffff';
            config.toolbarCanCollapse = true;
            config.filebrowserImageBrowseUrl = '/file-manager/ckeditor';
            config.contentsCss = '{{ URL::asset('assets/dashboard/css/bootstrap/dist/css/bootstrap.min.css') }}?v={{ Helper::system_version() }}';
            // config.extraPlugins = ['youtube', 'toc'];
            // config.extraAllowedContent = '*(*)';
            // Remove the entire forms plugin and toolbar items:
            config.removePlugins = 'forms,divarea,div,exportpdf';
            config.extraPlugins = 'youtube,toc,codesnippet,socialembed';
            config.facebookShareResolver = @json(route('facebookShareResolve', ['token' => '__TOKEN__']));
            config.extraAllowedContent = '*(*) ; blockquote(twitter-tweet) a[href,target,rel] ; div(fb-post)[data-href,data-width,data-show-text] ; iframe[!src,width,height,scrolling,frameborder,allowfullscreen,loading]{style}';
            // إزالة خصائص الخط والألوان عند اللصق مع الحفاظ على التنسيقات البسيطة
            config.pasteFromWordRemoveFontStyles = true;
            config.pasteFromWordRemoveStyles = true;

            //  تفعيل التغليف التلقائي للفقرات
            config.autoParagraph = true;
            config.enterMode = CKEDITOR.ENTER_P;

            //  إزالة div من قائمة التنسيقات (Format Dropdown)
            config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address';




            // تعيين اسم الخط الافتراضي الظاهر في قائمة الخطوط بالشريط (Dropdown Label)
            config.font_defaultLabel = 'Cairo';

            // تعيين الحجم الافتراضي الظاهر في قائمة الأحجام
            config.fontSize_defaultLabel = '18px';

        };
        CKEDITOR.on('instanceReady', function(evt) {
        var editor = evt.editor;

        // إعادة توجيه معالج Enter للتأكد من إنتاج p
        editor.dataProcessor.dataFilter.addRules({
            elements: {
                div: function(element) {
                    // تحويل أوسمة div العادية في النص إلى p
                    if (!element.attributes['class'] && !element.attributes['id']) {
                        element.name = 'p';
                    }
                }
            }
        });
    });
    </script>
@elseif(Helper::GeneralWebmasterSettings("text_editor") ==1)
    @if(!@$StopEditorCode)
        @include('dashboard.layouts.tinymce')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                initTinyMCE();
            });
        </script>
    @endif
@else
    <link rel="stylesheet" href="{{ asset('assets/dashboard/js/summernote/dist/summernote.css') }}">
    <script src="{{ asset("assets/dashboard/js/summernote/dist/summernote.js") }}"></script>
    <script>
        var activeSummernote = null;
        $(document).ready(function () {
            // File manager button (image icon)
            const FMButton = function (context) {
                const ui = $.summernote.ui;
                const button = ui.button({
                    contents: '<i class="note-icon-picture"></i> ',
                    tooltip: '{{ __("backend.fileManager") }}',
                    click: function () {
                        activeSummernote = context;
                        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                        let y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;
                        let w = (x * 0.8);
                        let h = (y * 0.8);
                        var l = (screen.width / 2) - (w / 2);
                        var t = (screen.height / 2) - (h / 2);
                        window.open('/file-manager/summernote', 'fm', 'width=' + w + ',height=' + h + ', top=' + t + ', left=' + l);
                    }
                });
                return button.render();
            };

            $('.summernote').summernote({
                tabsize: 2,
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['height', ['height']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['hr','link', 'fm', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontSizes: ['8', '9', '10', '11', '12', '13', '14', '16', '18', '20', '24', '30', '36', '32', '48', '64', '82', '150'],
                buttons: {
                    fm: FMButton
                }
            });
        });

        // set file link
        function fmSetLink(url) {
            if (activeSummernote) {
                activeSummernote.invoke('editor.insertImage', url);
            }
        }
    </script>
@endif
