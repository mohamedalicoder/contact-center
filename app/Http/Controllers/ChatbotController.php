<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    protected $responses = [
        'greetings' => [
            'hi' => 'مرحباً! كيف يمكنني مساعدتك اليوم؟',
            'hello' => 'أهلاً وسهلاً! كيف يمكنني خدمتك؟',
            'hey' => 'مرحباً! كيف يمكنني المساعدة؟',
            'good morning' => 'صباح الخير! كيف يمكنني مساعدتك اليوم؟',
            'good afternoon' => 'مساء الخير! كيف يمكنني خدمتك؟',
            'good evening' => 'مساء الخير! كيف يمكنني المساعدة؟',
            'السلام عليكم' => 'وعليكم السلام ورحمة الله وبركاته! كيف يمكنني مساعدتك؟',
            'مرحبا' => 'أهلاً وسهلاً! كيف يمكنني خدمتك اليوم؟'
        ],
        'farewell' => [
            'bye' => 'مع السلامة! يومك سعيد!',
            'goodbye' => 'إلى اللقاء! اعتني بنفسك!',
            'see you' => 'نتطلع لمساعدتك مرة أخرى!',
            'thanks' => 'العفو! لا تتردد في طلب المساعدة إذا احتجت شيئاً.',
            'شكرا' => 'العفو! سعدنا بخدمتك!',
            'مع السلامة' => 'في أمان الله! نتطلع لخدمتك مرة أخرى!'
        ],
        'customer_service' => [
            'agent' => 'يمكنك التواصل مع أحد ممثلي خدمة العملاء عبر الضغط على "التحدث مع موظف" في القائمة.',
            'complaint' => 'نأسف لسماع ذلك. يمكنك تقديم شكوى عبر نظامنا وسيتم التعامل معها في أقرب وقت.',
            'feedback' => 'نقدر آراءكم وملاحظاتكم. يمكنك تقديم الملاحظات عبر نموذج التغذية الراجعة في حسابك.',
            'waiting time' => 'وقت الانتظار المتوقع هو 5-10 دقائق. نقدر صبركم.',
            'وقت الانتظار' => 'وقت الانتظار المتوقع حالياً هو 5-10 دقائق. شكراً لصبركم.',
            'شكوى' => 'نأسف لذلك. يمكنك تقديم شكوى وسيتم التعامل معها بأسرع وقت ممكن.',
            'موظف' => 'للتحدث مع موظف خدمة العملاء، اضغط على زر "التحدث مع موظف".'
        ],
        'system_info' => [
            'login' => 'لتسجيل الدخول، اذهب إلى صفحة تسجيل الدخول وأدخل بريدك الإلكتروني وكلمة المرور.',
            'register' => 'للتسجيل في النظام، انقر على "تسجيل جديد" وأدخل بياناتك الأساسية.',
            'password' => 'إذا نسيت كلمة المرور، يمكنك استعادتها عبر رابط "نسيت كلمة المرور" في صفحة تسجيل الدخول.',
            'features' => 'نظامنا يوفر: إدارة المكالمات، تتبع الشكاوى، تقارير الأداء، وإدارة فريق العمل.',
            'تسجيل' => 'للتسجيل في النظام، اضغط على "تسجيل جديد" وأدخل بياناتك.',
            'كلمة المرور' => 'هل نسيت كلمة المرور؟ يمكنك استعادتها من صفحة تسجيل الدخول.',
            'المميزات' => 'نظامنا يقدم: إدارة مركز الاتصال، متابعة الشكاوى، التقارير، وإدارة الموظفين.'
        ],
        'technical_support' => [
            'error' => 'إذا واجهت أي مشكلة تقنية، يرجى التواصل مع الدعم الفني على الرقم 123456.',
            'bug' => 'هل وجدت خطأ في النظام؟ يرجى إبلاغنا بالتفاصيل وسنقوم بمعالجته.',
            'slow' => 'إذا كان النظام بطيئاً، جرب تحديث الصفحة أو مسح ذاكرة التخزين المؤقت.',
            'مشكلة' => 'هل تواجه مشكلة تقنية؟ اتصل بالدعم الفني على الرقم 123456.',
            'بطيء' => 'النظام بطيء؟ جرب تحديث الصفحة أو مسح ذاكرة التخزين المؤقت.',
            'خطأ' => 'وجدت خطأ؟ أخبرنا بالتفاصيل وسنعمل على حله فوراً.'
        ],
        'contact' => [
            'contact' => 'يمكنك التواصل مع فريق الدعم على support@example.com',
            'email' => 'راسلنا على: support@example.com',
            'phone' => 'اتصل بنا على: 123456',
            'address' => 'عنواننا: شارع الرئيسي، المدينة',
            'اتصل' => 'للتواصل معنا: هاتف: 123456 أو بريد: support@example.com',
            'العنوان' => 'تجدنا في: شارع الرئيسي، المدينة',
            'الدعم' => 'فريق الدعم متاح 24/7 على الرقم 123456'
        ],
        'default' => [
            'أنا هنا لمساعدتك! هل يمكنك توضيح طلبك؟',
            'يسعدني مساعدتك. هل يمكنك إعطاء المزيد من التفاصيل؟',
            'لم أفهم طلبك بشكل كامل. هل يمكنك إعادة صياغته؟',
            'دعني أساعدك. ما هي المعلومات التي تبحث عنها بالتحديد؟'
        ]
    ];

    protected $cacheDuration = 3600; // Cache for 1 hour

    public function bot()
    {
        return view('chat.bot');
    }

    public function chat(Request $request)
    {
        $message = $request->input('message');
        if (!$message) {
            return response()->json([
                'response' => 'عذراً، لم أتلق أي رسالة. كيف يمكنني مساعدتك؟'
            ]);
        }

        $response = $this->findResponse(strtolower($message));
        return response()->json(['response' => $response]);
    }

    public function response(Request $request)
    {
        $message = $request->input('message');
        
        if (empty($message)) {
            return response()->json(['error' => 'Message is required'], 400);
        }

        $response = $this->findResponse($message);
        
        return response()->json(['response' => $response]);
    }

    protected function findResponse($message)
    {
        $message = trim(mb_strtolower($message, 'UTF-8'));

        // Keywords mapping for better matching
        $keywords = [
            // خدمة العملاء
            'خدمة العملاء' => 'customer_service',
            'خدمه العملاء' => 'customer_service',
            'خدمة عملاء' => 'customer_service',
            'مساعدة' => 'customer_service',
            'مساعده' => 'customer_service',
            'عاوز مساعدة' => 'customer_service',
            'عايز مساعدة' => 'customer_service',
            'محتاج مساعدة' => 'customer_service',
            'احتاج مساعدة' => 'customer_service',
            'ممكن تساعدني' => 'customer_service',
            'عندي استفسار' => 'customer_service',
            'عندي سؤال' => 'customer_service',
            'استفسار' => 'customer_service',
            'سؤال' => 'customer_service',
            
            // المشاكل التقنية
            'مشكلة' => 'technical_support',
            'مشكله' => 'technical_support',
            'حل مشكلة' => 'technical_support',
            'حل مشكله' => 'technical_support',
            'عطل' => 'technical_support',
            'خطأ' => 'technical_support',
            'عندي مشكلة' => 'technical_support',
            'عندي مشكله' => 'technical_support',
            'في مشكلة' => 'technical_support',
            'النظام مش شغال' => 'technical_support',
            'مش شغال' => 'technical_support',
            'لا يعمل' => 'technical_support',
            'معطل' => 'technical_support',
            'بطيء' => 'technical_support',
            
            // التواصل
            'تواصل' => 'contact',
            'اتصال' => 'contact',
            'عاوز اتواصل' => 'contact',
            'عايز اتصل' => 'contact',
            'رقم الهاتف' => 'contact',
            'رقم التليفون' => 'contact',
            'الايميل' => 'contact',
            'البريد' => 'contact',
            'عنوان' => 'contact',
            'موقعكم' => 'contact',
            'فين مكانكم' => 'contact',
            
            // الشكاوى
            'شكوى' => 'complaint',
            'شكوه' => 'complaint',
            'عاوز اشتكي' => 'complaint',
            'عايز اشتكي' => 'complaint',
            'تقديم شكوى' => 'complaint',
            'مش راضي' => 'complaint',
            'خدمة سيئة' => 'complaint',
            'سيء' => 'complaint',
            
            // الخدمات
            'خدمات' => 'services',
            'خدماتكم' => 'services',
            'ايه الخدمات' => 'services',
            'العروض' => 'services',
            'الباقات' => 'services',
            'الاسعار' => 'services',
            'كام سعر' => 'services',
            'التكلفة' => 'services'
        ];

        // Check for exact keyword matches first
        foreach ($keywords as $keyword => $category) {
            if (str_contains($message, $keyword)) {
                switch ($category) {
                    case 'customer_service':
                        return 'يمكنني مساعدتك في خدمة العملاء. اختر ما تريد:
                        1. التحدث مع موظف خدمة عملاء مباشرة
                        2. تقديم شكوى أو اقتراح
                        3. الاستفسار عن خدماتنا وعروضنا
                        4. معرفة حالة طلبك السابق
                        
                        يمكنك اختيار رقم الخدمة أو كتابة طلبك مباشرة.';

                    case 'technical_support':
                        return 'سأساعدك في حل المشكلة التقنية. من فضلك أخبرني:
                        1. مشكلة في تسجيل الدخول أو كلمة المرور
                        2. مشكلة في النظام أو بطء في الأداء
                        3. مشكلة في الاتصال أو تحميل الصفحات
                        4. رسائل خطأ تظهر لك
                        
                        اختر رقم المشكلة أو اشرح المشكلة بالتفصيل وسأساعدك في حلها.';

                    case 'contact':
                        return 'يمكنك التواصل معنا عبر:
                        1. خدمة العملاء المباشرة (متاحة 24/7)
                        2. البريد الإلكتروني: support@example.com
                        3. رقم الهاتف: 123456 (من 9 صباحاً حتى 9 مساءً)
                        4. واتساب: +123456789
                        5. زيارة مقرنا: العنوان الرئيسي
                        
                        اختر طريقة التواصل المناسبة لك وسأقدم لك التفاصيل.';

                    case 'complaint':
                        return 'نأسف لعدم رضاك عن الخدمة. يمكنك:
                        1. تقديم شكوى رسمية (سيتم الرد خلال 24 ساعة)
                        2. التحدث مباشرة مع مشرف خدمة العملاء
                        3. إرسال تفاصيل شكواك عبر البريد الإلكتروني
                        4. طلب استرداد أو تعويض
                        
                        اختر ما يناسبك وسأوجهك للخطوات التالية.';

                    case 'services':
                        return 'نقدم مجموعة متنوعة من الخدمات:
                        1. خدمات مركز الاتصال المتكاملة
                        2. إدارة علاقات العملاء
                        3. خدمات الدعم الفني
                        4. حلول الأعمال المخصصة
                        
                        لمعرفة تفاصيل وأسعار أي خدمة، اذكر رقمها وسأشرح لك كل التفاصيل.';
                }
            }
        }

        // Check for Arabic greetings
        if (str_contains($message, 'هلا') || 
            str_contains($message, 'مرحبا') || 
            str_contains($message, 'السلام') ||
            str_contains($message, 'صباح') ||
            str_contains($message, 'مساء') ||
            str_contains($message, 'اهلا') ||
            str_contains($message, 'هاي') ||
            str_contains($message, 'ازيك') ||
            str_contains($message, 'عامل ايه')) {
            return 'أهلاً وسهلاً! أنا المساعد الذكي الخاص بمركز خدمة العملاء. كيف يمكنني مساعدتك اليوم؟ يمكنني:
            1. الرد على استفساراتك
            2. توجيهك لخدمة العملاء
            3. حل المشكلات التقنية
            4. تقديم معلومات عن خدماتنا
            
            كيف يمكنني مساعدتك؟';
        }

        // Check all response categories as fallback
        foreach ($this->responses as $category => $responses) {
            foreach ($responses as $key => $response) {
                if (str_contains($message, $key)) {
                    return $response;
                }
            }
        }

        // Default response if no match is found
        return 'عذراً، لم أفهم طلبك بالضبط. يمكنني مساعدتك في:
        1. خدمة العملاء والاستفسارات
        2. الدعم الفني وحل المشكلات
        3. معلومات عن خدماتنا وأسعارنا
        4. تقديم شكوى أو اقتراح
        5. التواصل مع فريقنا
        
        اختر رقم الخدمة التي تريدها أو اشرح طلبك بطريقة أخرى.';
    }
}
