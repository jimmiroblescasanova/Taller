<?php

return [

    'preload_roles' => true,

    'preload_permissions' => true,

    'navigation_section_group' => 'Seguridad', // Default uses language constant

    'team_model' => \App\Models\Team::class,
    
    'scope_to_tenant' => true,

    /*
     * Set as false to remove from navigation.
     */
    'should_register_on_navigation' => [
        'permissions' => false,
        'roles' => true,
    ],

    'guard_names' => [
        'web' => 'web',
        // 'api' => 'api',
    ],

    'toggleable_guard_names' => [
        'roles' => [
            'isToggledHiddenByDefault' => true,
        ],
        'permissions' => [
            'isToggledHiddenByDefault' => true,
        ],
    ],

    'default_guard_name' => 'web',

    'model_filter_key' => 'return \'%\'.$value;', // Eg: 'return \'%\'.$key.'\%\';'

    'user_name_column' => 'name',

    /*
     * Icons to use for navigation
     */
    'icons' => [
        'role_navigation' => 'heroicon-o-lock-closed',
        'permission_navigation' => 'heroicon-o-lock-closed',
    ],

    /*
     *  Navigation items order - int value, false  restores the default position
     */

    'sort' => [
        'role_navigation' => false,
        'permission_navigation' => false
    ],

    'generator' => [

        'guard_names' => [
            'web',
            // 'api',
        ],

        'permission_affixes' => [

            /*
             * Permissions Aligned with Policies.
             * DO NOT change the keys unless the genericPolicy.stub is published and altered accordingly
             */
            'viewAnyPermission' => 'listar',
            'viewPermission' => 'ver',
            'createPermission' => 'crear',
            'updatePermission' => 'actualizar',
            'deletePermission' => 'eliminar',
            'restorePermission' => 'recuperar',
            'forceDeletePermission' => 'forzar-eliminacion',

            /*
             * Additional Resource Permissions
             */
            // 'replicate',
            // 'reorder',
        ],

        /*
         * returns the "name" for the permission.
         *
         * $permission which is an iteration of [permission_affixes] ,
         * $model The model to which the $permission will be concatenated
         *
         * Eg: 'permission_name' => 'return $permissionAffix . ' ' . Str::kebab($modelName),
         *
         * Note: If you are changing the "permission_name" , It's recommended to run with --clean to avoid duplications
         */
        'permission_name' => 'return $permissionAffix . \' \' . __(\'model.\'.$modelName);',

        /*
         * Permissions will be generated for the models associated with the respective Filament Resources
         */
        'discover_models_through_filament_resources' => false,

        /*
         * Include directories which consists of models.
         */
        'model_directories' => [
            app_path('Models'),
            //app_path('Domains/Forum')
        ],

        /*
         * Define custom_models
         */
        'custom_models' => [
            //
        ],

        /*
         * Define excluded_models
         */
        'excluded_models' => [
            \App\Models\EstimateItem::class,
            \App\Models\OrderItem::class,
        ],

        'excluded_policy_models' => [
            \App\Models\User::class,
        ],

        /*
         * Define any other permission that should be synced with the DB
         */
        'custom_permissions' => [
            'procesar ordenes',
            'sincronizar contpaqi',
        ],

        'user_model' => \App\Models\User::class,

        'policies_namespace' => 'App\Policies',
    ],
];
