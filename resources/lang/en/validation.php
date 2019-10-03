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

    'accepted'             => 'The :attribute must be accepted.',
    'active_url'           => 'The :attribute is not a valid URL.',
    'after'                => 'The :attribute must be a date after :date.',
    'after_or_equal'       => 'The :attribute must be a date after or equal to :date.',
    'alpha'                => 'The :attribute may only contain letters.',
    'alpha_dash'           => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'            => 'The :attribute may only contain letters and numbers.',
    'array'                => 'The :attribute must be an array.',
    'before'               => 'The :attribute must be a date before :date.',
    'before_or_equal'      => 'The :attribute must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file'    => 'The :attribute must be between :min and :max kilobytes.',
        'string'  => 'The :attribute must be between :min and :max characters.',
        'array'   => 'The :attribute must have between :min and :max items.',
    ],
    'boolean'              => 'The :attribute field must be true or false.',
    'confirmed'            => 'The :attribute confirmation does not match.',
    'date'                 => 'The :attribute is not a valid date.',
    'date_format'          => 'The :attribute does not match the format :format.',
    'different'            => 'The :attribute and :other must be different.',
    'digits'               => 'The :attribute must be :digits digits.',
    'digits_between'       => 'The :attribute must be between :min and :max digits.',
    'dimensions'           => 'The :attribute has invalid image dimensions.',
    'distinct'             => 'The :attribute field has a duplicate value.',
    'email'                => 'The :attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => 'The :attribute must be a file.',
    'filled'               => 'The :attribute field must have a value.',
    'image'                => 'The :attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => 'The :attribute field does not exist in :other.',
    'integer'              => 'The :attribute must be an integer.',
    'ip'                   => 'The :attribute must be a valid IP address.',
    'ipv4'                 => 'The :attribute must be a valid IPv4 address.',
    'ipv6'                 => 'The :attribute must be a valid IPv6 address.',
    'json'                 => 'The :attribute must be a valid JSON string.',
    'max'                  => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'                => 'The :attribute must be a file of type: :values.',
    'mimetypes'            => 'The :attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'numeric'              => 'The :attribute must be a number.',
    'present'              => 'The :attribute field must be present.',
    'regex'                => 'The :attribute format is invalid.',
    'required'             => 'The :attribute field is required.',
    'required_if'          => 'The :attribute field is required when :other is :value.',
    'required_unless'      => 'The :attribute field is required unless :other is in :values.',
    'required_with'        => 'The :attribute field is required when :values is present.',
    'required_with_all'    => 'The :attribute field is required when :values is present.',
    'required_without'     => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same'                 => 'The :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute must be a string.',
    'timezone'             => 'The :attribute must be a valid zone.',
    'unique'               => 'The :attribute has already been taken.',
    'uploaded'             => 'The :attribute failed to upload.',
    'url'                  => 'The :attribute format is invalid.',

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
        'email' => [
            'noactive' => 'Podany adres e-mail nie został zweryfikowany. </br> Sprawdź swoją pocztę (w tym folder spam) i kliknij przycisk weryfikacyjny lub <a href="/sendagain">wyślij link weryfikacyjny ponownie.</a>',
            'active' => 'Użytkownik jest już zarejestrowany. Zaloguj się lub użyj funkcji przypominania hasła.',
            'existemail' => 'Błędny adres e-mail lub adres e-mail nie istnieje.',
            'password_confirmed.same' => 'Hasło i potwierdzenie hasła muszą być identyczne.',
            'password.min' => 'Hasło musi się składać przynajmniej z 6 znaków.',
        ],
        'profileEmail' => [
            'noactive' => 'Podany adres e-mail nie został zweryfikowany. </br> Sprawdź swoją pocztę (w tym folder spam) i kliknij przycisk weryfikacyjny lub <a href="/sendagain">wyślij link weryfikacyjny ponownie.</a>',
            'active' => 'Użytkownik jest już zarejestrowany. Zaloguj się lub użyj funkcji przypominania hasła.',
            'existemail' => 'Błędny adres e-mail lub adres e-mail nie istnieje.',
            'password_confirmed.same' => 'Hasło i potwierdzenie hasła muszą być identyczne.',
            'password.min' => 'Hasło musi się składać przynajmniej z 6 znaków.',
        ],
        'password_confirmed' => [
            'same' => 'Hasło i potwierdzenie hasła muszą być identyczne.',
            'password.min' => 'Hasło musi się składać przynajmniej z 6 znaków.',
        ],
        'password' => [
            'min' => 'Hasło musi się składać przynajmniej z 6 znaków.',
        ],
        'email_newsletter' => [
            'noactive' => 'Wypisałeś się już z newslettera. Jeśli chciałbyś powrócić do nas, w wiadomości informującej o wypisaniu dostałeś link za pomocą którego możesz się zapisać ponownie.',
            'active' => 'Jesteś już zapisany.'
        ],
        'campaignDateFrom' => [
            'edited_date' => 'Nie można ustawić wcześniejszej daty niż data rozpoczęcia kampanii lub wcześniejszej niż dzisiejsza data.',
            'date_from_to' => 'Data rozpoczęcia kampanii nie może być późniejsza niż data zakończenia kampanii.',
            'disable_when_published' => 'Nie można edytować daty rozpoczęcia kampani jeśli jest ona opublikowana i juz się rozpoczęła.'
        ],
        'campaignDateTo' => [
            'edited_date' => 'Nie można ustawić wcześniejszej daty niż data rozpoczęcia kampanii.'
        ],
        'checkbox_*' => [
            'required' => 'Pole wymagane.',
            'required_without' => 'Pole wymagane.',
            'checkbox_option' => 'Nie istnieje opcja odpowiedzi o podanym identyfikatorze przypisana do tego pytania.'
        ],
        'checkbox_*_inne_text' => [
            'min' => 'Minimalna ilość znaków 5.',
            'max' => 'Maksymalna ilość znaków 500.',
        ],
        'radio_*' => [
            'required' => 'Pole wymagane.',
            'radio_option' => 'Nie istnieje opcja odpowiedzi o podanym identyfikatorze przypisana do tego pytania.'
        ],
        'radio_*_inne_text' => [
            'required_if' => 'Pole wymaga wpisania tekstu.',
            'max' => 'Maksymalna ilość znaków 500.',
        ],
        'text_*' => [
            'required' => 'Pole wymagane.',
            'max' => 'Maksymalna ilość znaków 500.',
            'min' => 'Minimalna ilość znaków 2.',
        ],
        'rating_*' => [
            'required' => 'Pole wymagane.',
        ],
        'captcha' => [
            'required' => 'Captcha jest wymagana.',
            'accepted' => 'Zła Captcha, proszę spróbować jeszcze raz.'
        ],
        'g-recaptcha-response' => [
            'required' => 'Captcha jest wymagana.',
        ],
        'id' => [
            'usersplace' => 'To miejsce nie należy do Ciebie.'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
