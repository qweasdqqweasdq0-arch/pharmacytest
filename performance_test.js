import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
    vus: 10, // محاكاة 10 مستخدمين وهميين في نفس الوقت
    duration: '10s', // مدة الاختبار 10 ثوانٍ
};

export default function () {
    // اختبار رابط البحث عن الأدوية في مشروعك
    // ملاحظة: استبدل localhost:8000 برابط السيرفر إذا كنت تشغله على منفذ آخر
    const res = http.get('http://localhost:8000/patient/search?query=Panadol');
    
    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time < 500ms': (r) => r.timings.duration < 500,
    });
    
    sleep(1);
}

