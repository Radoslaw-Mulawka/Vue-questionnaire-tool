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

    'accepted'             => 'To pole musi zostać zaakceptowane.',
    'active_url'           => 'To pole nie jest poprawnym adresem URL.',
    'after'                => 'To pole musi być datą po :date.',
    'after_or_equal'       => 'To pole musi być datą po lub równą do :date.',
    'alpha'                => 'To pole może zawierać tylko litery.',
    'alpha_dash'           => 'To pole może zawierać tylko litery, cyfry i podkreślnik.',
    'alpha_num'            => 'To pole może zawierać tylko litery i cyfry.',
    'array'                => 'To pole musi być tablicą.',
    'before'               => 'To pole musi być datą przed :date.',
    'before_or_equal'      => 'To pole musi być datą przed lub równą do :date.',
    'between'              => [
        'numeric' => 'To pole musi być liczbą z zakresu od :min do :max.',
        'file'    => 'Rozmiar pliku :attribute musi mieścić sę w zakresie od :min do :max kilobajtów.',
        'string'  => 'To pole zmusi zawierać od :min do :max znaków.',
        'array'   => 'To pole musi zawierać od :min do :max przedmiotów (pozycji).',
    ],
    'boolean'              => 'To pole musi być wartością logiczną prawda (true) lub fałsz (false).',
    'confirmed'            => 'Niepoprawne potwierdzenie pola :attribute.',
    'date'                 => 'To pole nie jest poprawną datą.',
    'date_format'          => 'Data powinna mieć format :format.',
    'different'            => 'To pole i pole :other powinny być różne.',
    'digits'               => 'To pole powinno składać się z :digits cyfr.',
    'digits_between'       => 'To pole powinno mieścić się w zakresie od :min do :max cyfr.',
    'dimensions'           => 'Zdjęcie ma nieprawidłowe wymiary.',
    'distinct'             => 'To pole ma zduplikowaną wartość.',
    'email'                => 'To pole musi być poprawnym adresem e-mail.',
    'exists'               => 'To pole nie jest poprawne.',
    'file'                 => 'To pole musi być plikiem.',
    'filled'               => 'To pole musi mieć uzupełnioną wartość.',
    'image'                => 'To pole musi być zdjęciem.',
    'in'                   => 'Wybrana wartość pola jest nieprawidłowa.',
    'in_array'             => 'To pole nie istnieje w :other.',
    'integer'              => 'To pole musi być liczbą całkowitą.',
    'ip'                   => 'To pole musi być poprawnym adresem IP.',
    'ipv4'                 => 'To pole musi być poprawnym adresem IP w wersji 4 (IPv4).',
    'ipv6'                 => 'To pole musi być poprawnym adresem IP w wersji 6 (IPv6).',
    'json'                 => 'To pole musi być prawidłowym ciągiem JSON.',
    'max'                  => [
        'numeric' => 'To pole nie może być większe niż :max.',
        'file'    => 'Rozmiar pliku nie może być większy niż :max kilobajtów.',
        'string'  => 'To pole nie może zawierać więcej niż :max znaków.',
        'array'   => 'To pole nie może mieć więcej niż :max pozycji.',
    ],
    'mimes'                => 'To pole musi być plikiem o typie: :values.',
    'mimetypes'            => 'To pole musi być plikiem o typie: :values.',
    'min'                  => [
        'numeric' => 'To pole musi być większe lub równe :min.',
        'file'    => 'Rozmiar pliku musi być większy bądź równy :min kilobajtów.',
        'string'  => 'To pole musi zawierać minimum :min znaków.',
        'array'   => 'To pole musi posiadać minimum :min pozycji.',
    ],
    'not_in'               => 'Wybrana wartość pola jest nieprawidłowa.',
    'numeric'              => 'To pole musi być liczbą.',
    'present'              => 'To pole musi być obecne/pokazane.',
    'regex'                => 'Format pola jest nieprawidłowy.',
    'required'             => 'To pole jest wymagane.',
    'required_if'          => 'To pole jest wymagane jeśli :other ma wartość :value.',
    'required_unless'      => 'To pole jest wymagane, chyba że pole :other ma wartość :values.',
    'required_with'        => 'To pole jest wymagane gdy :values jest uzupełnione.',
    'required_with_all'    => 'To pole jest wymagane gdy :values są uzupełnione.',
    'required_without'     => 'To pole jest wymagane gdy :values nie jest uzupełnione.',
    'required_without_all' => 'To pole jest wymagane gdy żadne z :values nie są uzupełnione.',
    'same'                 => 'To pole i :other muszą być takie same.',
    'size'                 => [
        'numeric' => 'To pole musi mieć rozmiar :size.',
        'file'    => 'Rozmiar pliku musi mieć :size kilobajtów.',
        'string'  => 'To pole musi zawierać :size znaków.',
        'array'   => 'To pole musi zawierać :size pozycji.',
    ],
    'string'               => 'To pole musi być ciągiem znaków (string).',
    'timezone'             => 'To pole musi być prawidłową strefą czasową.',
    'unique'               => 'To pole zostało już zajęte.',
    'uploaded'             => 'Nie udało się przesłać pliku.',
    'url'                  => 'Format pola jest nieprawidłowy.',
  
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
            'noactive' => 'Podany adres e-mail nie został zweryfikowany. </br> Sprawdź swoją pocztę (w tym folder spam) i kliknij przycisk weryfikacyjny lub <a style="text-decoration:underline !important" href="/sendagain">wyślij link weryfikacyjny ponownie.</a>',
            'active' => 'Użytkownik jest już zarejestrowany. </br> Zaloguj się lub użyj funkcji przypominania hasła.',
            'existemail' => 'Błędny adres e-mail lub adres e-mail nie istnieje.',
            'password_confirmed.same' => 'Hasło i potwierdzenie hasła muszą być identyczne.',
            'password.min' => 'Hasło musi się składać przynajmniej z 6 znaków.',
        ],
      'profileEmail' => [
        'noactive' => 'Podany adres e-mail nie został zweryfikowany. </br> Sprawdź swoją pocztę (w tym folder spam) i kliknij przycisk weryfikacyjny lub <a href="/sendagain">wyślij link weryfikacyjny ponownie.</a>',
            'active' => 'Użytkownik jest już zarejestrowany. </br> Zaloguj się lub użyj funkcji przypominania hasła.',
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
