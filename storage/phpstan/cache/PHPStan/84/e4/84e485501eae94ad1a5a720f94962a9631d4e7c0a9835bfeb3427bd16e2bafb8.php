<?php declare(strict_types = 1);

// odsl-/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiReporte.php-PHPStan\BetterReflection\Reflection\ReflectionClass-App\Models\PuiReporte
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-6.65.0.9-8.4.1-de4735491a4cd979cecf2bd64fbf4e0d68c3728549563868d7805d45eb811a7c',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'App\\Models\\PuiReporte',
        'filename' => '/Applications/XAMPP/xamppfiles/htdocs/Aplicacion/pui-instituciones/pui-instituciones/app/Models/PuiReporte.php',
      ),
    ),
    'namespace' => 'App\\Models',
    'name' => 'App\\Models\\PuiReporte',
    'shortName' => 'PuiReporte',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property Empresa|null $empresa
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 13,
    'endLine' => 52,
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
        'declaringClassName' => 'App\\Models\\PuiReporte',
        'implementingClassName' => 'App\\Models\\PuiReporte',
        'name' => 'table',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '\'pui_reportes\'',
          'attributes' => 
          array (
            'startLine' => 15,
            'endLine' => 15,
            'startTokenPos' => 40,
            'startFilePos' => 277,
            'endTokenPos' => 40,
            'endFilePos' => 290,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 15,
        'endLine' => 15,
        'startColumn' => 5,
        'endColumn' => 38,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'fillable' => 
      array (
        'declaringClassName' => 'App\\Models\\PuiReporte',
        'implementingClassName' => 'App\\Models\\PuiReporte',
        'name' => 'fillable',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'empresa_id\', \'id_busqueda\', \'curp\', \'fecha_desaparicion\', \'carpeta_investigacion\', \'fase_actual\', \'estatus\', \'es_prueba\', \'payload_original\', \'alta_en\', \'baja_en\', \'busqueda_historica_finalizada_en\', \'ultima_busqueda_continua_en\']',
          'attributes' => 
          array (
            'startLine' => 17,
            'endLine' => 31,
            'startTokenPos' => 49,
            'startFilePos' => 320,
            'endTokenPos' => 90,
            'endFilePos' => 662,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 17,
        'endLine' => 31,
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
        'declaringClassName' => 'App\\Models\\PuiReporte',
        'implementingClassName' => 'App\\Models\\PuiReporte',
        'name' => 'casts',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[\'payload_original\' => \'array\', \'es_prueba\' => \'boolean\', \'fecha_desaparicion\' => \'date\', \'alta_en\' => \'datetime\', \'baja_en\' => \'datetime\', \'busqueda_historica_finalizada_en\' => \'datetime\', \'ultima_busqueda_continua_en\' => \'datetime\']',
          'attributes' => 
          array (
            'startLine' => 33,
            'endLine' => 41,
            'startTokenPos' => 99,
            'startFilePos' => 689,
            'endTokenPos' => 150,
            'endFilePos' => 985,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 33,
        'endLine' => 41,
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
        'startLine' => 43,
        'endLine' => 46,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiReporte',
        'implementingClassName' => 'App\\Models\\PuiReporte',
        'currentClassName' => 'App\\Models\\PuiReporte',
        'aliasName' => NULL,
      ),
      'coincidencias' => 
      array (
        'name' => 'coincidencias',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Illuminate\\Database\\Eloquent\\Relations\\HasMany',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 48,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'App\\Models',
        'declaringClassName' => 'App\\Models\\PuiReporte',
        'implementingClassName' => 'App\\Models\\PuiReporte',
        'currentClassName' => 'App\\Models\\PuiReporte',
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