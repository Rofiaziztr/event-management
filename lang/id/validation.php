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

    'accepted' => 'Harap setujui :attribute.',
    'accepted_if' => 'Harap setujui :attribute ketika :other adalah :value.',
    'active_url' => ':attribute bukan alamat web yang valid.',
    'after' => ':attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => ':attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => ':attribute hanya boleh berisi huruf.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'array' => ':attribute harus berupa daftar.',
    'ascii' => ':attribute hanya boleh berisi karakter dan simbol alfanumerik single-byte.',
    'before' => ':attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => ':attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => ':attribute harus memiliki antara :min dan :max item.',
        'file' => ':attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => ':attribute harus bernilai antara :min dan :max.',
        'string' => ':attribute harus berjumlah antara :min dan :max karakter.',
    ],
    'boolean' => ':attribute harus berupa ya atau tidak.',
    'can' => ':attribute berisi nilai yang tidak sah.',
    'confirmed' => 'Konfirmasi :attribute belum sesuai.',
    'current_password' => 'Password saat ini belum sesuai.',
    'date' => ':attribute bukan tanggal yang valid.',
    'date_equals' => ':attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => ':attribute tidak cocok dengan format :format.',
    'decimal' => ':attribute harus memiliki :decimal tempat desimal.',
    'declined' => ':attribute harus ditolak.',
    'declined_if' => ':attribute harus ditolak ketika :other adalah :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => ':attribute harus berjumlah :digits digit.',
    'digits_between' => ':attribute harus berjumlah antara :min dan :max digit.',
    'dimensions' => 'Ukuran gambar :attribute tidak sesuai.',
    'distinct' => ':attribute memiliki nilai yang sama.',
    'doesnt_end_with' => ':attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with' => ':attribute tidak boleh dimulai dengan salah satu dari berikut: :values.',
    'email' => ':attribute harus berupa alamat email yang valid.',
    'ends_with' => ':attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum' => ':attribute yang dipilih tidak valid.',
    'exists' => ':attribute yang dipilih belum tersedia.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute perlu diisi.',
    'gt' => [
        'array' => ':attribute harus memiliki lebih dari :value item.',
        'file' => ':attribute harus berukuran lebih besar dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai lebih besar dari :value.',
        'string' => ':attribute harus berjumlah lebih dari :value karakter.',
    ],
    'gte' => [
        'array' => ':attribute harus memiliki :value item atau lebih.',
        'file' => ':attribute harus berukuran :value kilobyte atau lebih.',
        'numeric' => ':attribute harus bernilai :value atau lebih.',
        'string' => ':attribute harus berjumlah :value karakter atau lebih.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih belum tersedia.',
    'in_array' => ':attribute belum ada di dalam :other.',
    'integer' => ':attribute harus berupa bilangan bulat.',
    'ip' => ':attribute harus berupa alamat IP yang valid.',
    'ipv4' => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => ':attribute harus berupa alamat IPv6 yang valid.',
    'json' => ':attribute harus berupa teks JSON yang valid.',
    'lowercase' => ':attribute harus berupa huruf kecil.',
    'lt' => [
        'array' => ':attribute harus memiliki kurang dari :value item.',
        'file' => ':attribute harus berukuran kurang dari :value kilobyte.',
        'numeric' => ':attribute harus bernilai kurang dari :value.',
        'string' => ':attribute harus berjumlah kurang dari :value karakter.',
    ],
    'lte' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :value item.',
        'file' => ':attribute harus berukuran :value kilobyte atau kurang.',
        'numeric' => ':attribute harus bernilai :value atau kurang.',
        'string' => ':attribute harus berjumlah :value karakter atau kurang.',
    ],
    'mac_address' => ':attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file' => ':attribute tidak boleh berukuran lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh bernilai lebih dari :max.',
        'string' => ':attribute tidak boleh berjumlah lebih dari :max karakter.',
    ],
    'max_digits' => ':attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'array' => ':attribute harus memiliki setidaknya :min item.',
        'file' => ':attribute harus berukuran setidaknya :min kilobyte.',
        'numeric' => ':attribute harus bernilai setidaknya :min.',
        'string' => ':attribute harus berjumlah setidaknya :min karakter.',
    ],
    'min_digits' => ':attribute harus memiliki setidaknya :min digit.',
    'missing' => ':attribute harus hilang.',
    'missing_if' => ':attribute harus hilang ketika :other adalah :value.',
    'missing_unless' => ':attribute harus hilang kecuali :other adalah :value.',
    'missing_with' => ':attribute harus hilang ketika :values ada.',
    'missing_with_all' => ':attribute harus hilang ketika :values ada.',
    'multiple_of' => ':attribute harus merupakan kelipatan dari :value.',
    'not_in' => ':attribute yang dipilih belum tersedia.',
    'not_regex' => 'Format :attribute tidak sesuai.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute perlu mengandung setidaknya satu huruf.',
        'mixed' => ':attribute perlu mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute perlu mengandung setidaknya satu angka.',
        'symbols' => ':attribute perlu mengandung setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan telah muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => ':attribute harus ada.',
    'prohibited' => ':attribute tidak diizinkan.',
    'prohibited_if' => ':attribute tidak diizinkan ketika :other adalah :value.',
    'prohibited_unless' => ':attribute tidak diizinkan kecuali :other ada di dalam :values.',
    'prohibits' => ':attribute melarang :other untuk hadir.',
    'regex' => 'Format :attribute tidak sesuai.',
    'required' => ':attribute perlu diisi.',
    'required_array_keys' => ':attribute harus berisi entri untuk: :values.',
    'required_if' => ':attribute perlu diisi ketika :other adalah :value.',
    'required_if_accepted' => ':attribute perlu diisi ketika :other diterima.',
    'required_unless' => ':attribute perlu diisi kecuali :other ada di dalam :values.',
    'required_with' => ':attribute perlu diisi ketika :values ada.',
    'required_with_all' => ':attribute perlu diisi ketika :values ada.',
    'required_without' => ':attribute perlu diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute perlu diisi ketika tidak ada :values yang ada.',
    'same' => ':attribute dan :other belum sama.',
    'size' => [
        'array' => ':attribute harus berisi :size item.',
        'file' => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus bernilai :size.',
        'string' => ':attribute harus berjumlah :size karakter.',
    ],
    'starts_with' => ':attribute harus dimulai dengan salah satu dari berikut: :values.',
    'string' => ':attribute harus berupa teks.',
    'timezone' => ':attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah terdaftar.',
    'uploaded' => ':attribute belum berhasil diunggah.',
    'uppercase' => ':attribute harus berupa huruf besar.',
    'url' => ':attribute harus berupa alamat web yang valid.',
    'ulid' => ':attribute harus berupa ULID yang valid.',
    'uuid' => ':attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute". This makes it quick to specify a specific
    | custom language line for a given attribute rule.
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

    'attributes' => [
        'full_name' => 'nama lengkap',
        'email' => 'alamat email',
        'password' => 'password',
        'password_confirmation' => 'konfirmasi password',
        'nip' => 'NIP',
        'position' => 'jabatan',
        'division' => 'divisi',
        'phone_number' => 'nomor telepon',
        'institution' => 'institusi',
        'login' => 'login',
        'current_password' => 'password saat ini',
        'new_password' => 'password baru',
        'new_password_confirmation' => 'konfirmasi password baru',
    ],

];
