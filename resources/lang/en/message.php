<?php

return [
    "greeting" => "Dear :username!",
    "regards"  => "Regards",
    "company_name" => "Bereshit",
    "button_trouble_text" => "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" .
                             'into your web browser:',

    "user_create_account" => [
        "title"    => "New account",
        "line_1"   => "You account credentials:",
        "line_2"   => "We recommend you to change password in few days by security reason.",
        "email"    => "email: :email",
        "password" => "password: :password",
    ],

    "user_reset_password" => [
        "title"  => "Password reset",
        "line_1" => "You are receiving this email because we received a password reset request for your account.",
        "action" => "Reset password"
    ],

    "user_change_password" => [
        "title"    => "New password",
        "line_1"   => "Your password has been changed.",
        "password" => "Your new password: :password",
        "email"    => "email: :email",
        "action"   => "Go to the website",
    ],

    "end_course" => [
        "email"    => [
            "subject" => 'End of course',
            "line_1"  => "Course :course_name is coming to an end. Please fill out our survey.",
            "action"  => "Go to survey",
        ],
        "database" => [
            "title"  => 'End of course',
            "line_1" => 'Course :course_name is coming to an end. Please fill out our survey.',
        ]
    ],

    "start_course" => [
        "email"    => [
            "subject" => "Start of course",
            "line_1"  => "Tomorrow will start course :course_name.",
        ],
        "database" => [
            "title"  => "Start of course",
            "line_1" => "Tomorrow will start course :course_name.",
        ]

    ],

    "course_student_status_completed" => [
        "email"    => [
            "subject" => "Course status change",
            "line_1"  => "You have successfully completed the course :course_name.",
            "line_2"  => "You have not completed the course :course_name",
            "action"  => "Go to course",
        ],
        "database" => [
            "title"  => "Course status change",
            "line_1" => "You have successfully completed the course :course_name.",
            "line_2" => "You have not completed the course :course_name",
        ]

    ],

    "end_homework" => [
        "email"    => [
            "subject" => "End of homework",
            "line_1"  => "Homework :homework_name  for course :course_name, closed.",
            "line_2"  => "You have received your homework report successfully.",
            "line_3"  => "You have not sent a homework report. You can create a ticket to unblock it.",
            "action"  => "Go to homework for unblock",
        ],
        "database" => [
            "title"  => "End of homework",
            "line_1" => "Homework :homework_name  for course :course_name, closed.",
            "line_2" => "You have received your homework report successfully.",
            "line_3" => "You have not sent a homework report. You can create a ticket to unblock it.",
        ]
    ],

    "start_homework" => [
        "email"    => [
            "subject" => 'Start homework',
            "line_1" =>  "We remind you that you must submit a reading report to :lesson_name, starting today at :homework_start_time until :homework_end_date at :homework_end_time.",
            "line_2" => "Homework :homework_name for course :course_name opens :homework_start_date.",
            "action"  => "Go to homework",
        ],
        "database" => [
            "title"  => 'Start homework',
            "line_1" =>  "We remind you that you must submit a reading report to :lesson_name, starting today at :homework_start_time until :homework_end_date at :homework_end_time.",
            "line_2" => "Homework :homework_name for course :course_name opens :homework_start_date.",
        ]
    ],

    "homework_report" => [
        "email"    => [
            "subject" => 'Homework report',
            "line_1"  => "For homework we remind you that you must submit a reading report to :lesson_name, until :homework_end_date at :homework_end_time",
            "action"  => "Go to homework",
        ],
        "database" => [
            "title"  => 'Homework report',
            "line_1" => "For homework we remind you that you must submit a reading report to :lesson_name, until :homework_end_date at :homework_end_time",
        ]

    ],

    "homework_report_access_student" => [
        "email"    => [
            "subject" => "Access to the students homework report",
            "line_1"  => "You got additional access to homework :homework_name.",
            "line_2"  => "You can send you homework till :homework_additional_access_close_time.",
            "action"  => "Go to homework"
        ],
        "database" => [
            "title"  => "Access to the students homework report",
            "line_1" => "You got additional access to homework :homework_name.",
            "line_2" => "You can send you homework till :homework_additional_access_close_time.",
        ]

    ],

    "homework_report_uploaded" => [
        "email"    => [
            "subject" => "Homework report uploaded",
            "line_1"  => "Homework report uploaded by the student :student_name to homework :homework_name course :course_name.",
            "action"  => "Go to homework upload",
        ],
        "database" => [
            "title"  => "Homework report uploaded",
            "line_1" => "Homework report uploaded by the student :student_name to homework :homework_name course :course_name.",
        ]
    ],

    "homework_report_comment" => [
        "email"    => [
            "subject"  => "New homework report comment",
            "line_1" => "The teacher left a new comment to your homework report - :homework_name, the course - :course_name.",
            "action" => "Go to homework report comment",
        ],
        "database" => [
            "title"  => "New homework report comment",
            "line_1" => "The teacher left a new comment to your homework report - :homework_name, the course - :course_name.",
        ],
    ],

    "start_lesson" => [
        "email"    => [
            "subject" => "Start of the lesson",
            "line_1"  => "Tomorrow will start lesson :lesson_name from course :course_name.",
            "action"  => "Go to lesson",
        ],
        "database" => [
            "title"  => "Start of the lesson",
            "line_1" => "Tomorrow will start lesson :lesson_name from course :course_name.",
        ]
    ],

    "ticket_created_super_admin" => [
        "email"    => [
            "subject" => "Get a new ticket",
            "line_1" => "You got new ticket from :full_name.",
            "line_2" => ":ticket_subject -  :ticket_priority",
            "action" => "Go to ticket",
        ],
        "database" => [
            "title" => "Get a new ticket",
            "line_1" => "You got new ticket from :full_name.",
            "line_2" => ":ticket_subject -  :ticket_priority",
        ]

    ],

    "ticket_expired_super_admin" => [
        "email"    => [
            "subject" => "Ticket expiration",
            "line_1"  => "You have expired tickets.",
            "line_2"  => ":ticket_subject  -  :ticket_priority - assigned on :ticket_assign_to_fullname  -  :ticket_url",
        ],
        "database" => [
            "title"  => "Ticket expiration",
            "line_1" => "You have expired tickets.",
            "line_2" => ":ticket_subject  -  :ticket_priority - assigned on :ticket_assign_to_fullname  -  :ticket_url",
        ]
    ],

    "course_add_student" => [
        "email"    => [
            "subject" => "Addition to the course",
            "line_1"  => "You can sign a  contract for the course - :course_name.",
            "action"  => "Go to sign a contract",
        ],
        "database" => [
            "title"  => "Addition to the course",
            "line_1" => "You can sign a  contract for the course - :course_name.",
        ],
    ],

    "curriculum_add_student" => [
        "email"    => [
            "subject" => "Addition to the curriculum",
            "line_1"  => "You can sign a :years_of_study contract for the curriculum - :curriculum_name.",
            "action"  => "Go to sign a contract",
        ],
        "database" => [
            "title"  => "Addition to the curriculum",
            "line_1" => "You can sign a :years_of_study contract for the curriculum - :curriculum_name.",
        ],
    ],

    "curriculum_sign_contract" => [
        "email"    => [
            "subject" => "Signed contract",
            "line_1"  => "The contract for the curriculum :curriculum_name signed.",
            "line_2"  => "The contract for the curriculum :curriculum_name signed by student :student_name.",
            "action"  => "Go to the curriculum",
        ],
        "database" => [
            "title"  => "Signed contract",
            "line_1" => "The contract for the curriculum :curriculum_name signed.",
            "line_2" => "The contract for the curriculum :curriculum_name signed by student :student_name.",
        ],
    ],

    "course_sign_contract" => [
        "email"    => [
            "subject" => "Signed contract",
            "line_1"  => "The contract for the course :course_name signed. You have been granted full access to this course.",
            "line_2"  => "Contract to the course :course_name, to the curriculum :curriculum_name is signed by the student :student_name.",
            "action"  => "Go to the course",
        ],
        "database" => [
            "title"  => "Signed contract",
            "line_1" => "The contract for the course :course_name signed. You have been granted full access to this course.",
            "line_2"  => "Contract to the course :course_name, to the curriculum :curriculum_name is signed by the student :student_name.",
        ],
    ],
];
