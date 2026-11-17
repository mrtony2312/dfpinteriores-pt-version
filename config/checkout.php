<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Virement bancaire (seul moyen de paiement proposé au checkout)
    |--------------------------------------------------------------------------
    */
    'bank' => [
        'holder' => env('BANK_ACCOUNT_HOLDER', 'DFP Interiores, Unipessoal Lda'),
        'iban' => env('BANK_IBAN', 'PT50 0000 0000 0000 0000 0000 0'),
        'bic' => env('BANK_BIC', ''),
        'bank_name' => env('BANK_NAME', ''),
        'nif' => env('BANK_NIF', '504074571'),
        'nib' => env('BANK_NIB', ''),
    ],

];
