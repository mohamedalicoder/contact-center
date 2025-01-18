<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ChatBotController extends Controller
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

    public function chat(Request $request)
    {
        Log::info('Received chat request', ['message' => $request->message]);

        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        try {
            // Generate a cache key based on the message
            $cacheKey = 'chat_' . md5($request->message);

            // Try to get response from cache
            if (Cache::has($cacheKey)) {
                Log::info('Returning cached response');
                return response()->json([
                    'success' => true,
                    'response' => Cache::get($cacheKey),
                    'cached' => true
                ]);
            }

            // Get response based on message content
            $response = $this->getResponse($request->message);

            // Cache the response
            Cache::put($cacheKey, $response, $this->cacheDuration);

            Log::info('Generated response', [
                'response_length' => strlen($response)
            ]);

            return response()->json([
                'success' => true,
                'response' => $response,
                'cached' => false
            ]);
            
        } catch (\Exception $e) {
            Log::error('Chat Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ غير متوقع: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function getResponse($message)
    {
        // Convert message to lowercase for better matching
        $message = strtolower(trim($message));

        // Check for greetings
        foreach ($this->responses['greetings'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Check for customer service related queries
        foreach ($this->responses['customer_service'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Check for system information queries
        foreach ($this->responses['system_info'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Check for technical support queries
        foreach ($this->responses['technical_support'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Check for farewells
        foreach ($this->responses['farewell'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // Check for contact information requests
        foreach ($this->responses['contact'] as $key => $response) {
            if (str_contains($message, $key)) {
                return $response;
            }
        }

        // If no specific match is found, return a random default response
        return $this->responses['default'][array_rand($this->responses['default'])];
    }

    public function bot()
    {
        return view('chat.bot');
    }
}
