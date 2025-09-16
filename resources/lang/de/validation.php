<?php

// resources/lang/de/validation.php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines / Validierungs-Sprachzeilen
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    | Die folgenden Sprachzeilen enthalten die Standard-Fehlermeldungen, die von
    | der Validierungsklasse verwendet werden. Einige dieser Regeln haben mehrere Versionen,
    | wie z.B. die Größenregeln. Sie können jede dieser Nachrichten hier anpassen.
    |
    */

    'accepted'        => 'Das Feld :attribute muss akzeptiert werden.',
    'active_url'      => 'Das Feld :attribute ist keine gültige URL.',
    'after'           => 'Das Feld :attribute muss ein Datum nach :date sein.',
    'after_or_equal'  => 'Das Feld :attribute muss ein Datum nach oder gleich :date sein.',
    'alpha'           => 'Das Feld :attribute darf nur Buchstaben enthalten.',
    'alpha_dash'      => 'Das Feld :attribute darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'alpha_num'       => 'Das Feld :attribute darf nur Buchstaben und Zahlen enthalten.',
    'array'           => 'Das Feld :attribute muss ein Array sein.',
    'before'          => 'Das Feld :attribute muss ein Datum vor :date sein.',
    'before_or_equal' => 'Das Feld :attribute muss ein Datum vor oder gleich :date sein.',
    'between'         => [
        'numeric' => 'Das Feld :attribute muss zwischen :min und :max liegen.',
        'file'    => 'Die Datei :attribute muss zwischen :min und :max Kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss zwischen :min und :max Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss zwischen :min und :max Elemente enthalten.',
    ],
    'boolean'         => 'Das Feld :attribute muss wahr oder falsch sein.',
    'confirmed'       => 'Die :attribute Bestätigung stimmt nicht überein.',
    'date'            => 'Das Feld :attribute ist kein gültiges Datum.',
    'date_equals'     => 'Das Feld :attribute muss ein Datum gleich :date sein.',
    'date_format'     => 'Das Feld :attribute entspricht nicht dem Format :format.',
    'different'       => 'Die Felder :attribute und :other müssen unterschiedlich sein.',
    'digits'          => 'Das Feld :attribute muss :digits Ziffern haben.',
    'digits_between'  => 'Das Feld :attribute muss zwischen :min und :max Ziffern haben.',
    'dimensions'      => 'Das Feld :attribute hat ungültige Bildabmessungen.',
    'distinct'        => 'Das Feld :attribute hat einen doppelten Wert.',
    'email'           => 'Das Feld :attribute muss eine gültige E-Mail-Adresse sein.',
    'ends_with'       => 'Das Feld :attribute muss mit einem der folgenden Werte enden: :values.',
    'exists'          => 'Das ausgewählte Feld :attribute ist ungültig.',
    'file'            => 'Das Feld :attribute muss eine Datei sein.',
    'filled'          => 'Das Feld :attribute muss einen Wert haben.',
    'gt'              => [
        'numeric' => 'Das Feld :attribute muss größer als :value sein.',
        'file'    => 'Die Datei :attribute muss größer als :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss länger als :value Zeichen sein.',
        'array'   => 'Das Feld :attribute muss mehr als :value Elemente enthalten.',
    ],
    'gte'             => [
        'numeric' => 'Das Feld :attribute muss größer oder gleich :value sein.',
        'file'    => 'Die Datei :attribute muss größer oder gleich :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss größer oder gleich :value Zeichen sein.',
        'array'   => 'Das Feld :attribute muss :value Elemente oder mehr enthalten.',
    ],
    'image'           => 'Das Feld :attribute muss ein Bild sein.',
    'in'              => 'Das ausgewählte Feld :attribute ist ungültig.',
    'in_array'        => 'Das Feld :attribute existiert nicht in :other.',
    'integer'         => 'Das Feld :attribute muss eine ganze Zahl sein.',
    'ip'              => 'Das Feld :attribute muss eine gültige IP-Adresse sein.',
    'ipv4'            => 'Das Feld :attribute muss eine gültige IPv4-Adresse sein.',
    'ipv6'            => 'Das Feld :attribute muss eine gültige IPv6-Adresse sein.',
    'json'            => 'Das Feld :attribute muss ein gültiger JSON-String sein.',
    'lt'              => [
        'numeric' => 'Das Feld :attribute muss kleiner als :value sein.',
        'file'    => 'Die Datei :attribute muss kleiner als :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss kürzer als :value Zeichen sein.',
        'array'   => 'Das Feld :attribute muss weniger als :value Elemente enthalten.',
    ],
    'lte'             => [
        'numeric' => 'Das Feld :attribute muss kleiner oder gleich :value sein.',
        'file'    => 'Die Datei :attribute muss kleiner oder gleich :value Kilobytes sein.',
        'string'  => 'Das Feld :attribute muss kleiner oder gleich :value Zeichen sein.',
        'array'   => 'Das Feld :attribute darf nicht mehr als :value Elemente enthalten.',
    ],
    'max'             => [
        'numeric' => 'Das Feld :attribute darf nicht größer als :max sein.',
        'file'    => 'Die Datei :attribute darf nicht größer als :max Kilobytes sein.',
        'string'  => 'Das Feld :attribute darf nicht länger als :max Zeichen sein.',
        'array'   => 'Das Feld :attribute darf nicht mehr als :max Elemente enthalten.',
    ],
    'mimes'           => 'Das Feld :attribute muss eine Datei vom Typ :values sein.',
    'mimetypes'       => 'Das Feld :attribute muss eine Datei vom Typ :values sein.',
    'min'             => [
        'numeric' => 'Das Feld :attribute muss mindestens :min sein.',
        'file'    => 'Die Datei :attribute muss mindestens :min Kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss mindestens :min Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss mindestens :min Elemente enthalten.',
    ],
    'multiple_of'     => 'Das Feld :attribute muss ein Vielfaches von :value sein.',
    'not_in'          => 'Das ausgewählte Feld :attribute ist ungültig.',
    'not_regex'       => 'Das Format von :attribute ist ungültig.',
    'numeric'         => 'Das Feld :attribute muss eine Zahl sein.',
    'password'        => 'Das Passwort ist falsch.',
    'present'         => 'Das Feld :attribute muss vorhanden sein.',
    'regex'           => 'Das Format von :attribute ist ungültig.',
    'required'        => 'Das Feld :attribute ist erforderlich.',
    'required_if'     => 'Das Feld :attribute ist erforderlich, wenn :other :value ist.',
    'required_unless' => 'Das Feld :attribute ist erforderlich, es sei denn, :other ist in :values enthalten.',
    'required_with'   => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden ist.',
    'required_with_all' => 'Das Feld :attribute ist erforderlich, wenn :values vorhanden sind.',
    'required_without' => 'Das Feld :attribute ist erforderlich, wenn :values nicht vorhanden ist.',
    'required_without_all' => 'Das Feld :attribute ist erforderlich, wenn keines der Felder :values vorhanden ist.',
    'same'            => 'Die Felder :attribute und :other müssen übereinstimmen.',
    'size'            => [
        'numeric' => 'Das Feld :attribute muss :size sein.',
        'file'    => 'Die Datei :attribute muss :size Kilobytes groß sein.',
        'string'  => 'Das Feld :attribute muss :size Zeichen lang sein.',
        'array'   => 'Das Feld :attribute muss :size Elemente enthalten.',
    ],
    'starts_with'     => 'Das Feld :attribute muss mit einem der folgenden Werte beginnen: :values.',
    'string'          => 'Das Feld :attribute muss ein String sein.',
    'timezone'        => 'Das Feld :attribute muss eine gültige Zeitzone sein.',
    'unique'          => 'Das Feld :attribute ist bereits vergeben.',
    'uploaded'        => 'Das Feld :attribute konnte nicht hochgeladen werden.',
    'url'             => 'Das Format von :attribute ist ungültig.',
    'uuid'            => 'Das Feld :attribute muss eine gültige UUID sein.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines / Benutzerdefinierte Validierungs-Sprachzeilen
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    | Hier können Sie benutzerdefinierte Validierungsnachrichten für Attribute angeben,
    | indem Sie die Konvention "attribute.rule" zur Benennung der Zeilen verwenden.
    | Dies ermöglicht es, schnell eine spezifische benutzerdefinierte Sprachzeile
    | für eine bestimmte Attributregel anzugeben.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'benutzerdefinierte-nachricht',
        ],
        // Beispiel:
        // 'email' => [
        //     'unique' => 'Diese E-Mail-Adresse wird bereits verwendet.',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes / Benutzerdefinierte Validierungsattribute
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    | Die folgenden Sprachzeilen werden verwendet, um unseren Attribut-Platzhalter
    | durch etwas Leserfreundlicheres wie "E-Mail-Adresse" anstelle von "email" zu ersetzen.
    | Dies hilft einfach dabei, unsere Nachrichten ausdrucksstärker zu gestalten.
    |
    */

    'attributes' => [
        // Beispiel:
        // 'email' => 'E-Mail-Adresse',
        // 'password' => 'Passwort',
        // 'name' => 'Name',
        // 'first_name' => 'Vorname',
        // 'last_name' => 'Nachname',
        // 'phone' => 'Telefonnummer',
        // 'address' => 'Adresse',
        // 'city' => 'Stadt',
        // 'country' => 'Land',
        // 'zipcode' => 'Postleitzahl',
        // 'terms' => 'Nutzungsbedingungen',
    ],

];
