<?php declare(strict_types = 1);

// odsl-/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiCoincidencia.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\PuiCoincidencia
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.1-554a8520aa45073436ece272afacbe8ee76cd9fedbe2470ff733e0ac648d42e9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\PuiCoincidencia',
        'filename' => '/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiCoincidencia.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\PuiCoincidencia',
    'shortName' => 'PuiCoincidencia',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => NULL,
    'attributes' => 
    array (
    ),
    'startLine' => 8,
    'endLine' => 48,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Illuminate\\Database\\Eloquent\\Model',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'table' => 
      array (
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'pui_coincidencias\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 33,
            'startFilePos' => 186,
            'endTokenPos' => 33,
            'endFilePos' => 204,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 43,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'pui_reporte_id\', \'fase_busqueda\', \'curp\', \'nombre_completo\', \'domicilio\', \'evento\', \'payload_enviado\', \'respuesta_pui\', \'http_code\', \'notificado_en\']',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 23,
            'startTokenPos' => 42,
            'startFilePos' => 234,
            'endTokenPos' => 74,
            'endFilePos' => 471,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 12,
        'endLine' => 23,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'casts' => 
      array (
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'nombre_completo\' => \'array\', \'domicilio\' => \'array\', \'evento\' => \'array\', \'payload_enviado\' => \'array\', \'respuesta_pui\' => \'array\', \'notificado_en\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 32,
            'startTokenPos' => 83,
            'startFilePos' => 498,
            'endTokenPos' => 127,
            'endFilePos' => 716,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 32,
        'startColumn' => 5,
        'endColumn' => 6,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      'reporte' => 
      array (
        'name' => 'reporte',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 34,
        'endLine' => 37,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'currentClassName' => 'App\\Models\\PuiCoincidencia',
        'aliasName' => NULL,
      ),
      'scopeFase' => 
      array (
        'name' => 'scopeFase',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'fase' => 
          array (
            'name' => 'fase',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 39,
            'endLine' => 39,
            'startColumn' => 39,
            'endColumn' => 50,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 39,
        'endLine' => 42,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'currentClassName' => 'App\\Models\\PuiCoincidencia',
        'aliasName' => NULL,
      ),
      'scopeExitosas' => 
      array (
        'name' => 'scopeExitosas',
        'parameters' => 
        array (
          'query' => 
          array (
            'name' => 'query',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 44,
            'endLine' => 44,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 44,
        'endLine' => 47,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiCoincidencia',
        'implementingClassName' => 'App\\Models\\PuiCoincidencia',
        'currentClassName' => 'App\\Models\\PuiCoincidencia',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));