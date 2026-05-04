<?php declare(strict_types = 1);

// odsl-/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiEvidenciaSeguridad.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\PuiEvidenciaSeguridad
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.1-8ffe5c0e59eeadfa2a2dcbe41a4dc42c6f71a2ccce1df8fcf50e0706e9c2cbf0',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\PuiEvidenciaSeguridad',
        'filename' => '/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiEvidenciaSeguridad.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\PuiEvidenciaSeguridad',
    'shortName' => 'PuiEvidenciaSeguridad',
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
    'endLine' => 49,
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
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'pui_evidencia_seguridad\'',
          'attributes' => 
          array (
            'startLine' => 10,
            'endLine' => 10,
            'startTokenPos' => 33,
            'startFilePos' => 192,
            'endTokenPos' => 33,
            'endFilePos' => 216,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 10,
        'endLine' => 10,
        'startColumn' => 5,
        'endColumn' => 49,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'empresa_id\', \'tipo_reporte\', \'herramienta\', \'version_herramienta\', \'ambiente_ejecucion\', \'fecha_ejecucion\', \'urls_validadas\', \'resultado_global\', \'archivo\', \'observaciones\']',
          'attributes' => 
          array (
            'startLine' => 12,
            'endLine' => 23,
            'startTokenPos' => 42,
            'startFilePos' => 246,
            'endTokenPos' => 74,
            'endFilePos' => 507,
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
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'urls_validadas\' => \'array\', \'fecha_ejecucion\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 25,
            'endLine' => 28,
            'startTokenPos' => 83,
            'startFilePos' => 534,
            'endTokenPos' => 99,
            'endFilePos' => 618,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 25,
        'endLine' => 28,
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
      'empresa' => 
      array (
        'name' => 'empresa',
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
        'startLine' => 30,
        'endLine' => 33,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'currentClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'aliasName' => NULL,
      ),
      'scopeTipo' => 
      array (
        'name' => 'scopeTipo',
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
            'startLine' => 35,
            'endLine' => 35,
            'startColumn' => 31,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'tipo' => 
          array (
            'name' => 'tipo',
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
            'startLine' => 35,
            'endLine' => 35,
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
        'startLine' => 35,
        'endLine' => 38,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'currentClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'aliasName' => NULL,
      ),
      'scopeAmbiente' => 
      array (
        'name' => 'scopeAmbiente',
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
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 35,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'ambiente' => 
          array (
            'name' => 'ambiente',
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
            'startLine' => 40,
            'endLine' => 40,
            'startColumn' => 43,
            'endColumn' => 58,
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
        'startLine' => 40,
        'endLine' => 43,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'currentClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'aliasName' => NULL,
      ),
      'scopeAprobados' => 
      array (
        'name' => 'scopeAprobados',
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
            'startLine' => 45,
            'endLine' => 45,
            'startColumn' => 36,
            'endColumn' => 41,
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
        'startLine' => 45,
        'endLine' => 48,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'implementingClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
        'currentClassName' => 'App\\Models\\PuiEvidenciaSeguridad',
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