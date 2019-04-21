<?php

return [
    'model' => 'Usuário|[2,*] Usuários',
    'name' => 'Nome',
    'email' => 'E-mail',
    'password' => 'Senha',
    'password_confirm' => 'Repita a Senha',
    'avatar' => 'Avatar',
    'role' => [
        'name' => 'Perfil',
        'admin' => 'Administrador',
        'common' => 'Comum',
    ],
    'errors' => [
        'password_confirm' => 'Deve ser igual à Senha',
        'email_used' => 'Este e-mail já está sendo utilizado',
    ],
    'block' => 'Bloquear',
    'unblock' => 'Desbloquear',
];