<?php
/*
// install.php - Script para crear la estructura del proyecto Karting

// Obtener el directorio actual donde está install.php
$baseDir = __DIR__;

// Función para crear directorios recursivamente
function createDirectory($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
        echo "Directorio creado: " . $path . "\n";
    }
}

// Función para crear archivo vacío
function createEmptyFile($path) {
    if (!file_exists($path)) {
        file_put_contents($path, "<?php\n// " . basename($path) . "\n");
        echo "Archivo creado: " . $path . "\n";
    }
}

// Estructura de directorios y archivos
$structure = [
    // Archivos raíz
    'index.php',
    'login.php',
    'logout.php',

    // Directorios y archivos de config
    'config' => [
        'database.php',
        'config.php',
        'env.php'
    ],

    // Directorios y archivos de API
    'api' => [
        'preparador' => [
            'salidasApi.php',
            '5ultimasApi.php',
            '5mejoresApi.php',
            'tipsApi.php'
        ],
        'piloto' => [
            'salidasApi.php',
            '5ultimasApi.php',
            '5mejoresApi.php',
            'tipsApi.php'
        ],
        'mecanico' => [
            'salidasApi.php'
        ]
    ],

    // Directorios y archivos de vistas
    'view' => [
        'layouts' => [
            'dashboard.php',
            'preparador.php',
            'mecanico.php',
            'piloto.php'
        ],
        'dashboard' => [
            'usuarios' => ['usuariosJS.php', 'usuariosMD.php', 'usuariosV.php'],
            'circuitos' => ['circuitosJS.php', 'circuitosMD.php', 'circuitosV.php'],
            'trazados' => ['trazadosJS.php', 'trazadosMD.php', 'trazadosV.php']
        ],
        'preparador' => [
            'salidas' => ['salidasJS.php', 'salidasMD.php', 'salidasV.php'],
            '5ultimas' => ['5ultimasJS.php', '5ultimasMD.php', '5ultimasV.php'],
            '5mejores' => ['5mejoresJS.php', '5mejoresMD.php', '5mejoresV.php'],
            'tips' => ['tipsJS.php', 'tipsMD.php', 'tipsV.php']
        ],
        'piloto' => [
            'salidas' => ['salidasJS.php', 'salidasMD.php', 'salidasV.php'],
            '5ultimas' => ['5ultimasJS.php', '5ultimasMD.php', '5ultimasV.php'],
            '5mejores' => ['5mejoresJS.php', '5mejoresMD.php', '5mejoresV.php'],
            'tips' => ['tipsJS.php', 'tipsMD.php', 'tipsV.php']
        ],
        'mecanico' => [
            'salidas' => ['salidasJS.php', 'salidasMD.php', 'salidasV.php']
        ]
    ],

    // Directorios y archivos de controladores
    'controllers' => [
        'BaseController.php',
        'authController.php',
        'dashboard' => [
            'dash_usuariosController.php',
            'dash_circuitosController.php',
            'dash_trazadosController.php'
        ],
        'preparador' => [
            'pre_salidasController.php',
            'pre_5ultimasController.php',
            'pre_5mejoresController.php',
            'pre_tipsController.php'
        ],
        'piloto' => [
            'pil_salidasController.php',
            'pil_5ultimasController.php',
            'pil_5mejoresController.php',
            'pil_tipsController.php'
        ],
        'mecanico' => [
            'mec_salidasController.php'
        ]
    ],

    // Directorios y archivos de helpers
    'helpers' => [
        'exportHelper.php',
        'datatableHelper.php',
        'authHelper.php'
    ],

    // Directorios públicos
    'public' => [
        'css' => [],
        'js' => [],
        'images' => []
    ],

    // Directorios de rutas
    'routes' => [
        'web.php',
        'api.php'
    ],

    // Directorios de pruebas
    'tests' => [
        'unit' => [],
        'functional' => [],
        'integration' => []
    ]
];

// Función recursiva para crear la estructura
function createStructure($structure, $basePath = '') {
    foreach ($structure as $key => $value) {
        $path = $basePath . '/' . (is_numeric($key) ? $value : $key);
        
        if (is_array($value)) {
            createDirectory($path);
            createStructure($value, $path);
        } else {
            createEmptyFile($path);
        }
    }
}

// Crear la estructura
echo "Iniciando creación de estructura del proyecto...\n";
createStructure($structure, $baseDir);
echo "Estructura del proyecto creada exitosamente!\n";
*/
?>