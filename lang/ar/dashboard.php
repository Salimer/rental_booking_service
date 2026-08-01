<?php

return [
    'title' => 'خدمة التأجير والحجوزات - لوحة الاختبار والبيانات المستقلة',
    'subtitle' => 'معاينة وفحص البيانات المخزنة حصرياً في قاعدة بيانات خدمة التأجير (بدون الاعتماد على المونوليث)',
    
    // Navigation
    'nav' => [
        'overview' => 'نظرة عامة والملخص',
        'properties' => 'العقارات والوحدات والأسعار',
        'bookings' => 'الحجوزات والمدفوعات',
        'coupons' => 'الكوبونات والخصومات',
        'orgs' => 'المؤسسات وطاقم العمل',
        'api_tester' => 'حاسبة الأسعار واختبار الـ API',
    ],

    // Overview Cards
    'metrics' => [
        'total_properties' => 'إجمالي العقارات',
        'total_units' => 'إجمالي الوحدات',
        'total_bookings' => 'إجمالي الحجوزات',
        'active_coupons' => 'الكوبونات النشطة',
        'total_orgs' => 'المؤسسات المسجلة',
        'revenue' => 'إجمالي المبالغ والمستحقات',
    ],

    // Statuses
    'status' => [
        'confirmed' => 'مؤكد',
        'pending' => 'قيد الانتظار',
        'cancelled' => 'ملغى',
        'completed' => 'مكتمل',
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'blocked' => 'مغلق / محجوز يدويًا',
        'available' => 'متاح',
    ],

    // Headers & Tables
    'headers' => [
        'id' => 'المعرف (ID)',
        'property' => 'العقار',
        'unit' => 'الوحدة',
        'org' => 'المؤسسة',
        'city' => 'المدينة',
        'country' => 'الدولة',
        'neighborhood' => 'الحي',
        'type' => 'النوع',
        'base_price' => 'السعر الأساسي',
        'rating' => 'التقييم',
        'guests' => 'أقصى عدد ضيوف',
        'bedrooms' => 'غرف النوم',
        'bathrooms' => 'دورات المياه',
        'customer_id' => 'معرف العميل',
        'check_in' => 'تاريخ الوصول',
        'check_out' => 'تاريخ المغادرة',
        'total_amount' => 'المبلغ الإجمالي',
        'currency' => 'العملة',
        'created_at' => 'تاريخ الإنشاء',
        'actions' => 'الإجراءات',
        'code' => 'كود الخصم',
        'discount_type' => 'نوع الخصم',
        'discount_value' => 'قيمة الخصم',
        'usage' => 'الاستخدام',
        'valid_until' => 'صالح حتى',
    ],

    // Tabs
    'tabs' => [
        'properties' => 'العقارات',
        'units' => 'الوحدات السكنية',
        'prices' => 'جدول الأسعار المخصصة',
        'availabilities' => 'الإتاحات والحظر اليدوي',
        'amenities' => 'المرافق والتجهيزات',
        'all_bookings' => 'جميع الحجوزات',
        'payments' => 'سجل المدفوعات',
        'status_logs' => 'سجل تغير الحالات',
        'holds' => 'حجوزات التثبيت الموقت (Holds)',
        'coupons_list' => 'قسائم الخصم',
        'gateway_discounts' => 'خصومات بوابات الدفع',
        'orgs_list' => 'قائمة المؤسسات',
        'org_staff' => 'طاقم العمل والصلحيات',
        'org_settings' => 'إعدادات المؤسسة والتسوية',
    ],

    // API Tester
    'tester' => [
        'title' => 'حاسبة تقدير أسعار الحجز الحية',
        'subtitle' => 'قم باختبار حساب تكلفة الحجز شاملاً الخصومات والضريبة والعمولة محلياً',
        'select_unit' => 'اختر الوحدة السكنية',
        'check_in_date' => 'تاريخ الوصول (Check-in)',
        'check_out_date' => 'تاريخ المغادرة (Check-out)',
        'coupon_code' => 'كود الخصم (اختياري)',
        'calculate_btn' => 'احسب التكلفة والتقدير الآن',
        'breakdown_title' => 'تفاصيل الحساب الناتج من الخدمة:',
        'raw_json' => 'استجابة الـ API بتنسيق JSON',
    ],
    
    // General
    'empty' => 'لا توجد بيانات مخزنة حالياً في هذا الجدول.',
    'db_info' => 'قاعدة البيانات المتصلة:',
    'env_info' => 'بيئة التشغيل:',
];
