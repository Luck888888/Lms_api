<?php

return [
    "greeting" => "שלום :username!",
    "regards"  => "בברכה",
    "company_name" => "צוות מרכז בראשית",
    "button_trouble_text" => "במידה ויש בעיה ללחוץ על כפתור “:actionText“, העתק והדבק את כתובת האתר לדפדפן שלך:",

    "user_create_account" => [
        "title"    => "חשבון חדש",
        "line_1"   => "אישורי החשבון שלך:",
        "line_2"   => "אנו ממליצים לך לשנות את הסיסמה בכמה ימים על ידי סיבה ביטחונית.",
        "email"    => "דואר אלקטרוני: :email",
        "password" => "סיסמא: :password",
    ],

    "user_reset_password" => [
        "title"  => "איפוס סיסמה",
        "line_1" => "ליצירת סיסמה חדשה לחץ ״אופוס סיסמה״",
        "action" => "איפוס סיסמה"
    ],

    "user_change_password" => [
        "title"    => "סיסמה חדשה",
        "line_1"   => "הסיסמה שלך שונתה.",
        "password" => "סיסמה חדשה: :password ",
        "email"    => "דואר אלקטרוני: :email",
        "action"   => "כניסה לחשבון",
    ],

    "end_course" => [
        "email"    => [
            "subject" => 'סוף כמובן',
            "line_1"  => ":course_name שם הקורס מגיע לסיומו. אנא מלא את הסקר שלנו",
            "action"  => "עבור לסקר",
        ],
        "database" => [
            "title"  => 'סוף כמובן',
            "line_1" => ':course_name שם הקורס מגיע לסיומו. אנא מלא את הסקר שלנו',
        ]
    ],

    "start_course" => [
        "email"    => [
            "subject" => "התחלה כמובן",
            "line_1"  => "מחר יתחיל :course_name.",
        ],
        "database" => [
            "title"  => "התחלה כמובן",
            "line_1" => "מחר יתחיל :course_name.",
        ]
    ],

    "course_student_status_completed" => [
        "email"    => [
            "subject" => "שינוי סטטוס הקורס",
            "line_1"  => "סיימת בהצלחה את :course_name שם הקורס.",
            "line_2"  => "לא סיימת את :course_name שם הקורס",
            "action"  => "לך לשיעורי בית",
        ],
        "database" => [
            "title"  => "שינוי סטטוס הקורס",
            "line_1" => "סיימת בהצלחה את :course_name שם הקורס.",
            "line_2" => "לא סיימת את :course_name שם הקורס",
        ]
    ],

    "end_homework" => [
        "email" => [
            "subject" => "סוף שיעורי הבית",
            "line_1" => "שיעורי :homework_name שיעורי בית_שם :course_name שם קורס, סגור.",
            "line_2" => "קיבלת את דוח שיעורי הבית שלך בהצלחה.",
            "line_3" => "לא שלחת דוח שיעורי בית. אתה יכול ליצור כרטיס כדי לבטל את חסימתו.",
            "action" => "עבור לשיעורי בית לביטול החסימה",
        ],
        "database" => [
            "title"  => "סוף שיעורי הבית",
            "line_1" => "שיעורי :homework_name שיעורי בית_שם :course_name שם קורס, סגור.",
            "line_2" => "קיבלת את דוח שיעורי הבית שלך בהצלחה.",
            "line_3" => "לא שלחת דוח שיעורי בית. אתה יכול ליצור כרטיס כדי לבטל את חסימתו.",
        ]
    ],

    "start_homework" => [
        "email"    => [
            "subject" => 'התחל שיעורי בית',
            "line_1" => "אנו מזכירים לך שעליך להגיש דו“ח קריאה ל-:lesson_name, החל מהיום ב-:homework_start_time ועד ה-:homework_end_date בשעה :homework_end_time.",
            "line_2" => "שיעורי בית :homework_name שם קורס :course_name התחל שיעורי בית :homework_start_date",
            "action" => "לשיעורי הבית",
        ],
        "database" => [
            "title"  => 'התחל שיעורי בית',
            "line_1" => "אנו מזכירים לך שעליך להגיש דו“ח קריאה ל-:lesson_name, החל מהיום ב-:homework_start_time ועד ה-:homework_end_date בשעה :homework_end_time.",
            "line_2" => "שיעורי בית :homework_name שם קורס :course_name התחל שיעורי בית :homework_start_date",
        ]
    ],

    "homework_report" => [
        "email"    => [
            "subject" => 'דוח שיעורי בית',
            "line_1"  => "אנו מזכירים לך שעליך להגיש דו“ח קריאה ל-:lesson_name, עד ה-:homework_end_date בשעה :homework_end_time.",
            "action"  => "לשיעורי הבית",
        ],
        "database" => [
            "title"  => 'דוח שיעורי בית',
            "line_1"  => "אנו מזכירים לך שעליך להגיש דו“ח קריאה ל-:lesson_name, עד ה-:homework_end_date בשעה :homework_end_time.",
        ]
    ],

    "homework_report_access_student" => [
        "email"    => [
            "subject" => "גישה לדוח שיעורי הבית של התלמידים",
            "line_1"  => "קיבלת גישה נוספת לשיעורי בית שיעורי :homework_name.",
            "line_2"  => "אתה יכול לשלוח לך שיעורי בית עד שיעורי :homework_additional_access_close_time.",
            "action"  => "לך לשיעורי בית"
        ],
        "database" => [
            "title"  => "גישה לדוח שיעורי הבית של התלמידים",
            "line_1" => "קיבלת גישה נוספת לשיעורי בית שיעורי :homework_name.",
            "line_2" => "אתה יכול לשלוח לך שיעורי בית עד שיעורי :homework_additional_access_close_time.",
        ]
    ],

    "homework_report_uploaded" => [
        "email"    => [
            "subject" => "דוח שיעורי בית שהועלה",
            "line_1"  => "דוח שיעורי בית שהועלה על ידי התלמיד :student_name הסטודנט לשיעורי בית :homework_name שם בית :course_name.",
            "action"  => "עבור להעלאת שיעורי בית",
        ],
        "database" => [
            "title"  => "דוח שיעורי בית שהועלה",
            "line_1" => "דוח שיעורי בית שהועלה על ידי התלמיד :student_name הסטודנט לשיעורי בית :homework_name שם בית :course_name.",
        ]

    ],

    "homework_report_comment" => [
        "email"    => [
            "subject" => "תגובה חדשה של דוח שיעורי בית",
            "line_1"  => "המורה השאיר תגובה חדשה לדוח שיעורי הבית שלך -:homework_name, הקורס -:course_name.",
            "action"  => "עבור להערת דוח שיעורי בית"
        ],
        "database" => [
            "title"  => "תגובה חדשה של דוח שיעורי בית",
            "line_1" => "המורה השאיר תגובה חדשה לדוח שיעורי הבית שלך -:homework_name, הקורס -:course_name.",
        ],
    ],

    "start_lesson" => [
        "email"    => [
            "subject" => "תחילת השיעור",
            "line_1"  => "מחר :lesson_name בקורס :course_name.",
            "action"  => "עבור לשיעור",
        ],
        "database" => [
            "title"  => "תחילת השיעור",
            "line_1" => "מחר :lesson_name בקורס :course_name.",
        ]
    ],

    "ticket_created_super_admin" => [
        "email"    => [
            "subject" => "קבל כרטיס חדש",
            "line_1"  => "יש לך כרטיס חדש :full_name.",
            "line_2"  => ":ticket_subject  -  :ticket_priority",
            "action"  => "עבור לכרטיס",
        ],
        "database" => [
            "title"  => "קבל כרטיס חדש",
            "line_1" => "יש לך כרטיס חדש :full_name.",
            "line_2" => ":ticket_subject  -  :ticket_priority",
        ]
    ],

    "ticket_expired_super_admin" => [
        "email"    => [
            "subject" => "פקיעת הכרטיס",
            "line_1"  => "פג תוקפם של הכרטיסים.",
            "line_2"  => ":ticket_subject  -  :ticket_priority - מוקצה על :ticket_assign_to_fullname  -  :ticket_url"
        ],
        "database" => [
            "title"  => "פקיעת הכרטיס",
            "line_1" => "פג תוקפם של הכרטיסים.",
            "line_2" => ":ticket_subject  -  :ticket_priority - מוקצה על :ticket_assign_to_fullname  -  :ticket_url"
        ]
    ],

    "course_add_student" => [
        "email"    => [
            "subject" => "תוספת לקורס",
            "line_1"  => "אתה  שנים_כמות חוזה לתכנית הלימודים -:course_name.",
            "action"  => "עבור לחתום על חוזה",
        ],
        "database" => [
            "title"  => "תוספת לקורס",
            "line_1" => "אתה  שנים_כמות חוזה לתכנית הלימודים -:course_name.",
        ],
    ],

    "curriculum_add_student" => [
        "email"    => [
            "subject" => "תוספת לתכנית הלימודים",
            "line_1"  => "אתה :years_of_study שנים_כמות חוזה לתכנית הלימודים -:curriculum_name.",
            "action"  => "עבור לתוכנית הלימודים",
        ],
        "database" => [
            "title"  => "תוספת לתכנית הלימודים",
            "line_1" => "אתה :years_of_study שנים_כמות חוזה לתכנית הלימודים -:curriculum_name.",
        ],
    ],

    "curriculum_sign_contract" => [
        "email"    => [
            "subject" => "חוזה חתום",
            "line_1"  => "החוזה לתכנית :curriculum_name שם התוכנית חתום.",
            "line_2"  => "החוזה לתכנית הלימודים :curriculum_name חתום על :student_name שם הסטודנט.",
            "action"  => "עבור לחוזה הלימודים",
        ],
        "database" => [
            "title"  => "חוזה חתום",
            "line_1" => "החוזה לתכנית :curriculum_name שם התוכנית חתום.",
            "line_2" => "החוזה לתכנית הלימודים :curriculum_name חתום על :student_name שם הסטודנט.",
        ],
    ],

    "course_sign_contract" => [
        "email"    => [
            "subject" => "חוזה חתום",
            "line_1"  => ":course_name שם הקורס חתום  ניתנה לך גישה מלאה לקורס זה.",
            "line_2"  => ". :curriculum_name  שם הקורס , לתכנית הלימודים :course_name התוכנית חתום על ידי :student_name שם הסטודנט",
            "action"  => "עבור לקורס",
        ],
        "database" => [
            "title"  => "חוזה חתום",
            "line_1" => ":course_name שם הקורס חתום ניתנה לך גישה מלאה לקורס זה.",
            "line_2"  => ". :curriculum_name  שם הקורס , לתכנית הלימודים :course_name התוכנית חתום על ידי :student_name שם הסטודנט",
        ],
    ],
];
