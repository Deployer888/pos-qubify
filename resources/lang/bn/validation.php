<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ' :attribute গ্রহণ করা আবশ্যক',
    'active_url' => ' :attribute টি বৈধ URL নয়।',
    'after' => ' :attribute তারিখের পরে আরেকটি :তারিখ হতে হবে।',
    'after_or_equal' => ' :attribute তারিখের পরে বা সমান হতে হবে :date',
    'alpha' => ' :attribute শুধুমাত্র বর্ণ থাকতে পারে.',
    'alpha_dash' => ' :attribute শুধুমাত্র বর্ণ, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num' => ' :attribute শুধুমাত্র বর্ণ এবং সংখ্যা থাকতে পারে।',
    'array' => ' :attribute একটি অ্যারে হতে হবে।',
    'before' => ' :attribute তারিখের আগে আরেকটি :তারিখ হতে হবে।',
    'before_or_equal' => ' :attribute তারিখের পরে বা সমান হতে হবে :date.',
    'between' => [
        'numeric' => ' :attribute :min এবং :max এর মধ্যে হতে হবে',
        'file' => ' :attribute :min and :max কিলোবাইট এর মধ্যে হতে হবে.',
        'string' => ' :attribute :min and :max ক্যারেকটার এর মধ্যে হতে হবে.',
        'array' => ' :attribute :min and :max আইটেম এর মধ্যে হতে হবে.',
    ],
    'boolean' => ' :attribute ঘরটি অবশ্যই সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ' :attribute নিশ্চিতকরণ  সঠিক নয়',
    'date' => ' :attribute সঠিক তারিখ নয়',
    'date_equals' => ' :attribute তারিখের সমান হতে হবে :date.',
    'date_format' => ' :attribute ফরম্যাটের সাথে ম্যাচ হচ্ছে না :format.',
    'different' => ' :attribute এবং : ভিন্ন হতে হবে',
    'digits' => ' :attribute সংখ্যা হতে হবে :digits.',
    'digits_between' => ' :attribute :min এবং :max সংখ্যার মাঝে হতে হবে.',
    'dimensions' => ' :attribute ছবির পরিমাপ ঠিক নেই.',
    'distinct' => ' :attribute ঘরটির একটি ডুপ্লিকেট মান আছে.',
    'email' => ' :attribute ভ্যালিড ইমেইল থাকতে হবে',
    'ends_with' => ' :attribute অবশ্যই শেষ হতে হবে following: :values.',
    'exists' => ' বাছাইকৃত :attribute ঠিক নয়.',
    'file' => ' :attribute ফাইল হতে হবে.',
    'filled' => ' :attribute ঘরটির অবশ্যই মান থাকতে হবে.',
    'gt' => [
        'numeric' => ' :attribute এর থেকে বড় হতে হবে :value.',
        'file' => ' :attribute এর থেকে বড় হতে হবে :value kilobytes.',
        'string' => ' :attribute এর থেকে বড় হতে হবে :value characters.',
        'array' => ' :attribute এর থেকে বড় হতে হবে :value items.',
    ],
    'gte' => [
        'numeric' => ' :attribute এর থেকে বড় বা সমান হতে হবে :value.',
        'file' => ' :attribute এর থেকে বড় বা সমান হতে হবে :value kilobytes.',
        'string' => ' :attribute এর থেকে বড় বা সমান হতে হবে :value characters.',
        'array' => ' :attribute থাকতে হবে :value items or more.',
    ],
    'image' => ' :attribute অবশ্যই ছবি হতে হবে.',
    'in' => ' selected :attribute ঠিক নয়.',
    'in_array' => ' :attribute ঘরটি নেই :other.',
    'integer' => ' :attribute একটি পূর্ণসংখ্যা হতে হবে.',
    'ip' => ' :attribute সঠিক আইপি এড্রেস হতে হবে.',
    'ipv4' => ' :attribute একটি বৈধ IPv4 ঠিকানা হতে হবে.',
    'ipv6' => ' :attribute একটি বৈধ IPv6 ঠিকানা হতে হবে.',
    'json' => ' :attribute একটি বৈধ JSON স্ট্রিং হতে হবে.',
    'lt' => [
        'numeric' => ' :attribute এর থেকে ছোট হতে হবে :value.',
        'file' => ' :attribute এর থেকে ছোট হতে হবে :value kilobytes.',
        'string' => ' :attribute এর থেকে ছোট হতে হবে :value characters.',
        'array' => ' :attribute এর থেকে ছোট হতে হবে :value items.',
    ],
    'lte' => [
        'numeric' => ' :attribute এর থেকে ছোট বা সমান হতে হবে :value.',
        'file' => ' :attribute এর থেকে ছোট বা সমান হতে হবে :value kilobytes.',
        'string' => ' :attribute এর থেকে ছোট বা সমান হতে হবে :value characters.',
        'array' => ' :attribute এর থেকে বেশি নয় :value items.',
    ],
    'max' => [
        'numeric' => ' :attribute এর চেয়ে বেশি নাও হতে পারে :max.',
        'file' => ' :attribute এর চেয়ে বেশি নাও হতে পারে :max kilobytes.',
        'string' => ' :attribute এর চেয়ে বেশি নাও হতে পারে :max characters.',
        'array' => ' :attribute এর চেয়ে বেশি নাও হতে পারে :max items.',
    ],
    'mimes' => ' :attribute টাইপের ফাইল হতে হবে: :values.',
    'mimetypes' => ' :attribute টাইপের ফাইল হতে হবে: :values.',
    'min' => [
        'numeric' => ' :attribute নূন্যতম হতে হবে :min.',
        'file' => ' :attribute নূন্যতম হতে হবে :min kilobytes.',
        'string' => ' :attribute নূন্যতম হতে হবে :min characters.',
        'array' => ' :attribute নূন্যতম হতে হবে :min items.',
    ],
    'multiple_of' => ' :attribute একাধিক হতে হবে :value',
    'not_in' => 'বাছাইকৃত :attribute সঠিক নয়.',
    'not_regex' => ' :attribute ফরম্যাট সঠিক নয়.',
    'numeric' => ' :attribute অবশ্যই একটি সংখ্যা হবে.',
    'password' => 'পাসওয়ার্ড সঠিক নয়.',
    'present' => ' :attribute ঘর থাকতে হবে.',
    'regex' => ' :attribute ফরম্যাট সঠিক নয়.',
    'required' => ' :attribute ঘরটি পূরণ করতে হবে.',
    'required_if' => ' :attribute ঘরটি পূরণ করতে হবে যখন :other হচ্ছে :value.',
    'required_unless' => ' :attribute ঘরটি পূরণ করতে হবে যদি না :other হচ্ছে :values.',
    'required_with' => ' :attribute ঘরটি পূরণ করতে হবে যখন :values হচ্ছে present.',
    'required_with_all' => ' :attribute ঘরটি পূরণ করতে হবে যখন :values হচ্ছে present.',
    'required_without' => ' :attribute ঘরটি পূরণ করতে হবে যখন :values থাকবে না present.',
    'required_without_all' => ' :attribute ঘরটি পূরণ করতে হবে যখন কোনটিই :values হবে present.',
    'same' => ' :attribute and :other মিল হতে হবে.',
    'size' => [
        'numeric' => ' :attribute হতে হবে :size.',
        'file' => ' :attribute হতে হবে :size kilobytes.',
        'string' => ' :attribute হতে হবে :size characters.',
        'array' => ' :attribute থাকতে হবে :size items.',
    ],
    'starts_with' => ' :attribute শুরু করতে হবে: :values.',
    'string' => ' :attribute স্ট্রিং হতে হবে.',
    'timezone' => ' :attribute সঠিক জোন হতে হবে.',
    'unique' => ' :attribute ইতিমধ্যে নেয়া হয়েছে.',
    'uploaded' => ' :attribute আপলোড করতে ব্যার্থ.',
    'url' => ' :attribute ফরম্যাট সঠিক নয়.',
    'uuid' => ' :attribute অবশ্যই সঠিক  UUID হতে হবে.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
