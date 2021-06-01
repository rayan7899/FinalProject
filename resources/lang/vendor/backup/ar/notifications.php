<?php

return [
    'exception_message' => 'رسالة استثناء: :message',
    'exception_trace' => 'تتبع الإستثناء: :trace',
    'exception_message_title' => 'رسالة استثناء',
    'exception_trace_title' => 'تتبع الإستثناء',

    'backup_failed_subject' => 'أخفق النسخ الاحتياطي لـ :application_name',
    'backup_failed_body' => 'مهم: حدث خطأ أثناء النسخ الاحتياطي :application_name',

    'backup_successful_subject' => 'نسخ احتياطي جديد ناجح لـ :application_name',
    'backup_successful_subject_title' => 'نجاح النسخ الاحتياطي الجديد!',
    'backup_successful_body' => ' نسخة احتياطية جديدة لـ :application_name تم إنشاؤها بنجاح على القرص المسمى :disk_name.',

    'cleanup_failed_subject' => 'فشل تنظيف النسخ الاحتياطي للتطبيق :application_name .',
    'cleanup_failed_body' => 'حدث خطأ أثناء تنظيف النسخ الاحتياطية لـ :application_name',

    'cleanup_successful_subject' => 'تنظيف النسخ الاحتياطية لـ :application_name تمت بنجاح',
    'cleanup_successful_subject_title' => 'تنظيف النسخ الاحتياطية تم بنجاح!',
    'cleanup_successful_body' => 'تنظيف النسخ الاحتياطية لـ :application_name على القرص المسمى :disk_name تم بنجاح.',

    'healthy_backup_found_subject' => 'النسخ الاحتياطية لـ :application_name على القرص :disk_name سليمة',
    'healthy_backup_found_subject_title' => 'النسخ الاحتياطية لـ :application_name سليمة',
    'healthy_backup_found_body' => 'تعتبر النسخ الاحتياطية لـ :application_name سليمة. عمل جيد!',

    'unhealthy_backup_found_subject' => 'مهم: النسخ الاحتياطية لـ :application_name غير سليمة',
    'unhealthy_backup_found_subject_title' => 'مهم: النسخ الاحتياطية لـ :application_name غير سليمة. :problem',
    'unhealthy_backup_found_body' => 'النسخ الاحتياطية لـ :application_name على القرص :disk_name غير سليمة.',
    'unhealthy_backup_found_not_reachable' => 'لا يمكن الوصول إلى وجهة النسخ الاحتياطي. :error',
    'unhealthy_backup_found_empty' => 'لا توجد نسخ احتياطية لهذا التطبيق على الإطلاق.',
    'unhealthy_backup_found_old' => 'تم إنشاء أحدث النسخ الاحتياطية في :date وتعتبر قديمة جدا.',
    'unhealthy_backup_found_unknown' => 'عذرا، لا يمكن تحديد سبب دقيق.',
    'unhealthy_backup_found_full' => 'النسخ الاحتياطية تستخدم الكثير من التخزين. الاستخدام الحالي هو :disk_usage وهو أعلى من الحد المسموح به من :disk_limit.',
];
