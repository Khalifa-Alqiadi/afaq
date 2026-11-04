<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Content adapted from the supplied AFAQ Arabic and English company profiles. */
class AfaqContentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $now = now();
            $sections = [
                'services' => ['الخدمات', 'Services', [
                    ['الدراسة والتصميم', 'Study and design', 'ندرس الأحمال والاستهلاك، ونعاين الموقع، ونحدد سعة النظام وإنتاجه المتوقع وجدواه المالية. المخرج: تقرير فني ومالي يشمل التكلفة وفترة استرداد رأس المال.', 'We analyse loads and consumption, survey the site, size the system, and assess generation and financial feasibility. Deliverable: a technical and financial report covering cost and payback.'],
                    ['التوريد', 'Supply', 'نورد الألواح الشمسية والبطاريات والمحوّلات والمعدات الكهربائية وفق شروط ضمان الشركات المصنّعة. المخرج: قائمة بالمكونات ومواصفاتها ومصادرها وشروط ضمانها.', 'We supply solar panels, batteries, inverters and electrical equipment under manufacturer warranty terms. Deliverable: a component list with specifications, sources and warranties.'],
                    ['التركيب والربط', 'Installation and connection', 'نتولى التركيب والأعمال الكهربائية والربط وفق نوع النظام، ثم الاختبار والتشغيل. المخرج: نظام جاهز للعمل ومحضر اختبار وتشغيل.', 'We carry out installation, electrical works and connections, followed by testing and commissioning. Deliverable: a working system and commissioning record.'],
                    ['التشغيل والصيانة', 'Operation and maintenance', 'نتابع أداء النظام وننفذ الصيانة الدورية وفق عقد التشغيل والصيانة. المخرج: تقارير أداء تقارن الإنتاج الفعلي بالمتوقع.', 'We monitor performance and carry out scheduled maintenance under an O&M contract. Deliverable: reports comparing actual and expected output.'],
                ]],
                'components' => ['مكونات المنظومة', 'System components', [
                    ['الألواح الشمسية', 'Solar panels', 'نختارها وفق مساحة التركيب وظروف الموقع. الموردون: AACE وRonma.', 'Selected for the installation area and site conditions. Suppliers: AACE and Ronma.'],
                    ['البطاريات', 'Batteries', 'بطاريات ليثيوم تُحدد سعتها وفق الأحمال ومدة التشغيل المطلوبة. الموردون: Goshin وStar Charge.', 'Lithium batteries sized for the required loads and operating duration. Suppliers: Goshin and Star Charge.'],
                    ['المحوّلات', 'Inverters', 'نختارها وفق تكوين المنظومة ومتطلبات الربط بالشبكة عند اتصالها بها. المورد: Star Charge.', 'Selected for the system configuration and grid connection requirements where applicable. Supplier: Star Charge.'],
                    ['البنية التحتية', 'Infrastructure', 'هياكل التثبيت والقواعد والكابلات ولوحات الربط والحماية بمواصفات تناسب الموقع.', 'Mounting structures, foundations, cabling, connection and protection panels specified for each site.'],
                ]],
                'systems' => ['أنواع الأنظمة', 'System types', [
                    ['مرتبط بالشبكة', 'On-grid', 'يعمل بالتوازي مع الشبكة العامة ويغطي جزءًا من استهلاك النهار دون الحاجة إلى بطاريات.', 'Runs alongside the public grid and covers part of daytime consumption without batteries.'],
                    ['غير مرتبط بالشبكة', 'Off-grid', 'يعمل مستقلًا عن الشبكة وتوفر البطاريات الطاقة خارج ساعات الإشعاع؛ مناسب للمواقع البعيدة.', 'Runs independently of the grid, with batteries supplying power outside sunlight hours; suited to remote sites.'],
                    ['هجين', 'Hybrid', 'يجمع الطاقة الشمسية والشبكة والتخزين بالبطاريات، ويمكن تهيئته لتغذية أحمال محددة عند انقطاع الشبكة.', 'Combines solar, grid and battery storage, and can supply selected loads during an outage.'],
                ]],
                'sectors' => ['القطاعات الرئيسية', 'Key sectors', [
                    ['التجاري', 'Commercial', 'مراكز التسوق والفنادق والمكاتب ذات احتياجات التبريد والإنارة والتشغيل.', 'Shopping centres, hotels and offices with cooling, lighting and operational needs.'],
                    ['الصناعي', 'Industrial', 'المصانع والمستودعات وفق مساحة الأسطح والأحمال وساعات التشغيل.', 'Factories and warehouses, matched to roof area, loads and operating hours.'],
                    ['الحكومي', 'Government', 'المباني العامة والمدارس والمرافق ضمن مشاريع الطاقة المتجددة وكفاءة الطاقة.', 'Public buildings, schools and facilities in renewable energy and efficiency projects.'],
                    ['السكني', 'Residential', 'الفلل والمجمعات السكنية وفق الاستهلاك والمساحة واحتياجات التخزين.', 'Villas and residential compounds, matched to consumption, space and storage needs.'],
                    ['الزراعي', 'Agricultural', 'المزارع ومضخات الري ومخازن التبريد، بما في ذلك المواقع البعيدة أو المعتمدة على الديزل.', 'Farms, irrigation pumps and cold stores, including remote or diesel powered sites.'],
                ]],
            ];

            $moduleIds = [];
            foreach ($sections as $key => [$ar, $en, $items]) {
                $slug = 'afaq-'.$key;
                $id = DB::table('webmaster_sections')->where('seo_url_slug_en', $slug)->value('id');
                $values = ['title_ar' => $ar, 'title_en' => $en, 'seo_url_slug_ar' => $slug, 'seo_url_slug_en' => $slug, 'type' => 0, 'status' => 1, 'sections_status' => 0, 'row_no' => 100 + count($moduleIds), 'updated_at' => $now];
                if ($id) DB::table('webmaster_sections')->where('id', $id)->update($values);
                else $id = DB::table('webmaster_sections')->insertGetId($values + ['created_at' => $now]);
                $moduleIds[$key] = $id;
                foreach ($items as $index => [$itemAr, $itemEn, $bodyAr, $bodyEn]) {
                    $itemSlug = $slug.'-'.($index + 1);
                    $this->topic($id, $itemSlug, $itemAr, $itemEn, $bodyAr, $bodyEn, $index + 1, $now);
                }
            }
            foreach (DB::table('permissions')->where('id', 1)->get(['id', 'data_sections']) as $permission) {
                $ids = array_filter(explode(',', (string) $permission->data_sections));
                DB::table('permissions')->where('id', $permission->id)->update(['data_sections' => implode(',', array_unique(array_merge($ids, array_values($moduleIds))))]);
            }

            $pageModule = DB::table('webmaster_sections')->where('type', 10)->orderBy('id')->value('id');
            if (! $pageModule) {
                $pageModule = DB::table('webmaster_sections')->insertGetId(['title_ar' => 'صفحات الموقع', 'title_en' => 'Site pages', 'seo_url_slug_ar' => 'afaq-pages', 'seo_url_slug_en' => 'afaq-pages', 'type' => 10, 'status' => 1, 'created_at' => $now, 'updated_at' => $now]);
            }
            $home = $this->topic($pageModule, 'afaq-home', 'آفاق للطاقة والأعمال المتكاملة', 'AFAQ for Energy & Integrated Solution', 'نبتكر اليوم، ليستدام الغد. حلول الطاقة الشمسية وتخزين الطاقة في سلطنة عُمان.', 'Innovating today, sustaining tomorrow. Solar and energy storage solutions in the Sultanate of Oman.', 1, $now);
            $this->topic($pageModule, 'afaq-about', 'عن آفاق', 'About AFAQ', 'نحن آفاق، شركة عُمانية متخصصة في حلول الطاقة الشمسية وتخزين الطاقة. نصمم الأنظمة ونورد مكوناتها ونتولى تركيبها وتشغيلها والأعمال الكهربائية المرتبطة بها. نخدم المشاريع في أنحاء سلطنة عُمان من فرعينا في مسقط وصلالة.', 'We are AFAQ, an Omani company specialising in solar and energy storage. We design systems, supply components, and carry out installation, commissioning and associated electrical works. We serve projects across Oman through our branches in Muscat and Salalah.', 2, $now);
            $this->topic($pageModule, 'afaq-value', 'القيمة الاقتصادية', 'Economic value', 'نساعد أصحاب المنشآت على تقييم الاستثمار في الطاقة الشمسية بدراسة الاستهلاك وتكلفة النظام والإنتاج المتوقع وفترة استرداد رأس المال. نوضح الجدوى قبل التنفيذ بأرقام تخص كل مشروع.', 'We help facility owners assess solar investment through consumption, cost, expected generation and payback analysis. Each decision is based on project specific figures.', 3, $now);
            $this->topic($pageModule, 'afaq-engagement', 'خيارات التعاقد', 'Engagement options', 'التوريد: نوفر المكونات للمقاولين وشركات التركيب وأصحاب المنشآت وفق المواصفات والكميات المطلوبة. تنفيذ المشاريع المتكاملة: نتولى التصميم والتوريد والتركيب والاختبار والتشغيل ضمن نطاق عمل واضح ومسؤولية موحدة أمام المالك.', 'Supply: we provide components to contractors, installers and facility owners in the required specifications and quantities. Complete project delivery: we manage design, supply, installation, testing and commissioning within a defined scope and single point of responsibility.', 4, $now);
            $this->topic($pageModule, 'afaq-advantages', 'ما نقدمه', 'What we offer', 'اتفاقيات توريد محلية ودولية، وألواح AACE مصنّعة في عُمان، ودعم محلي لمطالبات ضمان الألواح وفق شروط المصنّع، ومساهمة في القيمة المحلية المضافة. نخدم المشاريع في أنحاء السلطنة من مسقط وصلالة بفريق يضم التصميم الهندسي والإشراف والتركيب والتشغيل.', 'Local and international supply agreements, Oman made AACE panels, local support for panel warranty claims under manufacturer terms, and in-country value. We serve projects across Oman from Muscat and Salalah with design, supervision, installation and commissioning capabilities.', 5, $now);
            $this->topic($pageModule, 'afaq-contact', 'تواصل معنا', 'Contact us', 'الهاتف: +968 9190 7789\nالبريد الإلكتروني: info@afaq-solutions.com\nمسقط · صلالة، سلطنة عُمان', 'Phone: +968 9190 7789\nEmail: info@afaq-solutions.com\nMuscat · Salalah, Sultanate of Oman', 6, $now);

            $area = DB::table('webmaster_banners')->where('title_en', 'AFAQ Home Hero')->value('id');
            $areaValues = ['title_ar' => 'بنرات آفاق الرئيسية', 'title_en' => 'AFAQ Home Hero', 'type' => 1, 'status' => 1, 'desc_status' => 1, 'link_status' => 1, 'row_no' => 100, 'updated_at' => $now];
            if ($area) DB::table('webmaster_banners')->where('id', $area)->update($areaValues);
            else $area = DB::table('webmaster_banners')->insertGetId($areaValues + ['created_at' => $now]);
            foreach ([
                ['طاقة بإتقان هندسي', 'Energy. Engineered.', 'حلول الطاقة الشمسية والتخزين في سلطنة عُمان.', 'Solar and storage solutions across Oman.', 'afaq-profile-page-1.png'],
                ['نبتكر اليوم، ليستدام الغد', 'Innovating today, sustaining tomorrow', 'من الدراسة والتصميم إلى التركيب والتشغيل والصيانة.', 'From study and design to installation, commissioning and maintenance.', 'afaq-profile-page-2.png'],
                ['حلول لكل موقع', 'Solutions for every site', 'من أسطح المباني إلى محطات الطاقة الشمسية.', 'From building rooftops to solar power plants.', 'afaq-profile-page-3.png'],
            ] as $index => [$ar, $en, $descAr, $descEn, $image]) {
                $key = 'afaq-hero-'.($index + 1);
                $id = DB::table('banners')->where('section_id', $area)->where('row_no', $index + 1)->where('title_en', $en)->value('id');
                $values = ['section_id' => $area, 'title_ar' => $ar, 'title_en' => $en, 'details_ar' => $descAr, 'details_en' => $descEn, 'file_ar' => $image, 'file_en' => $image, 'link_url' => '/afaq-about', 'row_no' => $index + 1, 'status' => 1, 'updated_at' => $now];
                if ($id) DB::table('banners')->where('id', $id)->update($values);
                else DB::table('banners')->insert($values + ['created_at' => $now]);
            }

            $blocks = [
                ['hero', 2, 'آفاق للطاقة', 'AFAQ Energy', '', ''],
                ['about', 0, 'عن آفاق', 'About AFAQ', 'شركة عُمانية نصمم ونورد ونركب حلول الطاقة الشمسية وتخزينها، ونخدم المشاريع من مسقط وصلالة.', 'An Omani company designing, supplying and installing solar and storage solutions, serving projects from Muscat and Salalah.'],
                ['services', 3, 'نطاق عملنا', 'Our scope of work', 'الدراسة والتصميم، التوريد، التركيب والربط، التشغيل والصيانة.', 'Study and design, supply, installation and connection, operation and maintenance.'],
                ['systems', 3, 'أنواع الأنظمة', 'System types', 'حلول مرتبطة بالشبكة، مستقلة عنها، وهجينة وفق احتياجات الموقع.', 'On-grid, off-grid and hybrid solutions suited to each site.'],
                ['components', 3, 'مكونات المنظومة', 'System components', 'ألواح شمسية وبطاريات ومحوّلات وبنية تحتية مناسبة لكل مشروع.', 'Panels, batteries, inverters and infrastructure selected for each project.'],
                ['value', 0, 'قرار استثماري مبني على دراسة', 'An investment decision informed by analysis', 'نقيّم الاستهلاك والتكلفة والإنتاج المتوقع وفترة الاسترداد قبل التنفيذ.', 'We assess consumption, cost, expected output and payback before implementation.'],
                ['engagement', 0, 'خيارات التعاقد', 'Engagement options', 'توريد المكونات أو تنفيذ المشروع المتكامل ضمن نطاق عمل واضح.', 'Component supply or complete project delivery within a defined scope.'],
                ['advantages', 0, 'ما نقدمه', 'What we offer', 'توريد محلي ودولي، ألواح مصنّعة في عُمان، ودعم ضمان محلي.', 'Local and international supply, Oman made panels and local warranty support.'],
                ['sectors', 3, 'القطاعات التي نخدمها', 'Sectors we serve', 'حلول للقطاعات التجارية والصناعية والحكومية والسكنية والزراعية.', 'Solutions for commercial, industrial, government, residential and agricultural sectors.'],
                ['contact', 0, 'ابدأ مشروعك معنا', 'Start your project with us', 'تواصل معنا في مسقط أو صلالة: +968 9190 7789 — info@afaq-solutions.com', 'Contact our Muscat or Salalah teams: +968 9190 7789 — info@afaq-solutions.com'],
            ];
            foreach ($blocks as $index => [$key, $type, $ar, $en, $descAr, $descEn]) {
                $name = 'afaq-'.$key;
                $content = ['title_ar' => $ar, 'title_en' => $en, 'desc_ar' => $descAr, 'desc_en' => $descEn];
                if ($type === 0) { $content['details_ar'] = '<p>'.$descAr.'</p>'; $content['details_en'] = '<p>'.$descEn.'</p>'; $content['desc_ar'] = ''; $content['desc_en'] = ''; }
                elseif ($type === 2) { $content += ['banner_area_id' => $area, 'banner_style' => 'slider']; }
                else { $content += ['module_id' => $moduleIds[$key], 'records_count' => 8, 'records_order' => 0, 'view_style' => 'grid']; }
                $values = ['topic_id' => $home, 'block_name' => $name, 'type' => $type, 'content' => json_encode($content, JSON_UNESCAPED_UNICODE), 'title_status' => 1, 'desc_status' => $type === 0 ? 0 : 1, 'row_no' => $index + 1, 'status' => 1, 'updated_at' => $now];
                $id = DB::table('topic_blocks')->where('topic_id', $home)->where('block_name', $name)->value('id');
                if ($id) DB::table('topic_blocks')->where('id', $id)->update($values);
                else DB::table('topic_blocks')->insert($values + ['created_at' => $now]);
            }

            // A landing page's visible body belongs to topic_blocks, not topics.details_*.
            // Keep the page records as routes/metadata; put every chapter in editable blocks.
            $pageBlocks = [
                'afaq-about' => [
                    ['identity', 'من نحن', 'Who we are', 'نحن آفاق، شركة عُمانية متخصصة في حلول الطاقة الشمسية وتخزين الطاقة. نصمم الأنظمة ونورد مكوناتها ونتولى تركيبها وتشغيلها والأعمال الكهربائية المرتبطة بها.', 'We are AFAQ, an Omani company specialising in solar and energy storage solutions. We design systems, supply components, and carry out installation, commissioning and associated electrical works.'],
                    ['reach', 'نطاق خدمتنا', 'Where we work', 'نعمل مع أصحاب المنشآت والمقاولين والمطورين، ونخصص خدماتنا لكل مشروع من توريد المكونات إلى الأنظمة المتكاملة للأسطح ومحطات الطاقة الشمسية. نخدم المشاريع في أنحاء السلطنة من فرعينا في مسقط وصلالة.', 'We work with facility owners, contractors and developers, tailoring our services from component supply to complete rooftop and solar plant systems. We serve projects across Oman from Muscat and Salalah.'],
                ],
                'afaq-value' => [
                    ['assessment', 'قرار مبني على دراسة', 'An informed investment decision', 'نساعد أصحاب المنشآت على تقييم الاستثمار في الطاقة الشمسية من خلال دراسة الاستهلاك وتكلفة النظام والإنتاج المتوقع وفترة استرداد رأس المال.', 'We help facility owners assess solar investment through consumption, system cost, expected generation and payback analysis.'],
                    ['outcome', 'جدوى تخص مشروعك', 'Project-specific value', 'يمكن أن يخفض التوليد في الموقع الاعتماد على الشبكة وتكلفة التشغيل وفق حجم النظام ونمط الاستهلاك. نوضح هذه الجدوى قبل التنفيذ بأرقام تخص كل مشروع.', 'On-site generation can reduce grid reliance and operating costs depending on system size and consumption patterns. We assess this potential before implementation using project-specific figures.'],
                ],
                'afaq-engagement' => [
                    ['supply', 'التوريد', 'Supply', 'نورد المكونات للمقاولين وشركات التركيب وأصحاب المنشآت وفق مواصفات المشروع والكميات المطلوبة.', 'We supply components to contractors, installers and facility owners in the specifications and quantities required for each project.'],
                    ['delivery', 'تنفيذ المشاريع المتكاملة', 'Complete project delivery', 'نتولى التصميم والتوريد والتركيب والاختبار والتشغيل ضمن نطاق عمل واضح ومسؤولية موحدة أمام المالك.', 'We manage design, supply, installation, testing and commissioning within a defined scope, providing one point of responsibility for the owner.'],
                ],
                'afaq-advantages' => [
                    ['sourcing', 'التوريد والقيمة المحلية', 'Sourcing and in-country value', 'نعمل عبر اتفاقيات توريد محلية ودولية. ونورد ألواح AACE المصنّعة في عُمان بما يدعم المحتوى المحلي للمشاريع والمناقصات.', 'We work through local and international supply agreements and supply AACE panels made in Oman, supporting local sourcing for projects and tenders.'],
                    ['support', 'الدعم والتنفيذ', 'Support and delivery', 'نتابع مطالبات ضمان الألواح محليًا وفق شروط الشركة المصنّعة. ويجمع فريقنا التصميم الهندسي والإشراف والتركيب والتشغيل، ونخدم المشاريع من مسقط وصلالة.', 'We handle panel warranty claims in Oman under manufacturer terms. Our team covers engineering design, supervision, installation and commissioning, serving projects from Muscat and Salalah.'],
                ],
                'afaq-contact' => [
                    ['details', 'تواصل معنا', 'Contact us', 'الهاتف: +968 9190 7789<br>البريد الإلكتروني: info@afaq-solutions.com<br>مسقط · صلالة، سلطنة عُمان', 'Phone: +968 9190 7789<br>Email: info@afaq-solutions.com<br>Muscat · Salalah, Sultanate of Oman'],
                ],
            ];
            foreach ($pageBlocks as $slug => $chapters) {
                $pageId = DB::table('topics')->where('seo_url_slug_en', $slug)->value('id');
                DB::table('topics')->where('id', $pageId)->update(['details_ar' => null, 'details_en' => null]);
                foreach ($chapters as $order => [$key, $ar, $en, $bodyAr, $bodyEn]) {
                    $name = 'afaq-'.$key;
                    $content = json_encode(['title_ar' => $ar, 'title_en' => $en, 'desc_ar' => '', 'desc_en' => '', 'details_ar' => '<p>'.$bodyAr.'</p>', 'details_en' => '<p>'.$bodyEn.'</p>'], JSON_UNESCAPED_UNICODE);
                    $values = ['topic_id' => $pageId, 'block_name' => $name, 'type' => 0, 'content' => $content, 'title_status' => 1, 'desc_status' => 0, 'row_no' => $order + 1, 'status' => 1, 'updated_at' => $now];
                    $id = DB::table('topic_blocks')->where('topic_id', $pageId)->where('block_name', $name)->value('id');
                    if ($id) DB::table('topic_blocks')->where('id', $id)->update($values);
                    else DB::table('topic_blocks')->insert($values + ['created_at' => $now]);
                }
            }
            DB::table('topics')->where('id', $home)->update(['details_ar' => null, 'details_en' => null]);

            $menu = DB::table('menus')->where('father_id', 0)->where('title_en', 'AFAQ Main Menu')->value('id');
            $menuValues = ['father_id' => 0, 'title_ar' => 'قائمة آفاق الرئيسية', 'title_en' => 'AFAQ Main Menu', 'type' => 0, 'status' => 1, 'row_no' => 100, 'updated_at' => $now];
            if ($menu) DB::table('menus')->where('id', $menu)->update($menuValues);
            else $menu = DB::table('menus')->insertGetId($menuValues + ['created_at' => $now]);
            foreach ([
                ['الرئيسية', 'Home', '/'], ['عن آفاق', 'About', 'afaq-about'],
                ['الخدمات', 'Services', 'afaq-services'], ['المكونات', 'Components', 'afaq-components'],
                ['الأنظمة', 'Systems', 'afaq-systems'], ['القطاعات', 'Sectors', 'afaq-sectors'],
                ['تواصل معنا', 'Contact', 'afaq-contact'],
            ] as $order => [$ar, $en, $link]) {
                $values = ['father_id' => $menu, 'title_ar' => $ar, 'title_en' => $en, 'type' => 1, 'link' => $link, 'status' => 1, 'row_no' => $order + 1, 'updated_at' => $now];
                $id = DB::table('menus')->where('father_id', $menu)->where('title_en', $en)->value('id');
                if ($id) DB::table('menus')->where('id', $id)->update($values);
                else DB::table('menus')->insert($values + ['created_at' => $now]);
            }

            // These settings make the seeded landing page, hero and navigation visible.
            $settingsId = DB::table('webmaster_settings')->orderBy('id')->value('id');
            if ($settingsId) DB::table('webmaster_settings')->where('id', $settingsId)->update(['homepage_type' => 1, 'landing_page_id' => $home, 'home_banners_section_id' => $area, 'header_menu_id' => $menu]);
        });
    }

    private function topic(int $module, string $slug, string $ar, string $en, string $bodyAr, string $bodyEn, int $order, $now): int
    {
        $values = ['webmaster_id' => $module, 'seo_url_slug_ar' => $slug, 'seo_url_slug_en' => $slug, 'title_ar' => $ar, 'title_en' => $en, 'details_ar' => nl2br($bodyAr), 'details_en' => nl2br($bodyEn), 'row_no' => $order, 'status' => 1, 'updated_at' => $now];
        $id = DB::table('topics')->where('seo_url_slug_en', $slug)->value('id');
        if ($id) DB::table('topics')->where('id', $id)->update($values);
        else $id = DB::table('topics')->insertGetId($values + ['created_at' => $now]);
        return $id;
    }
}
