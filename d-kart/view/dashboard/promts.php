<?php
$prompts = [
    [
        'id' => 1,
        'nombre' => 'Recomendaciones para Hoy',
        'template' => "Analiza la última sesión de karting. Los datos son:\n\n{datos_sesion}\n\n
📌 **Objetivo de la Consulta:** 
🔹 **primero de todo compara la fecha y el clima actual.**  
🔹 **Quiero recomendaciones para mejorar tiempos y estabilidad.**  
🔹 **¿Hay novedades en presiones de neumáticos o ajustes de carburación?**  
🔹 **¿Cómo afecta la temperatura actual al rendimiento?**  
🔹 **¿Qué ajustes hacer para mejorar aceleración o estabilidad?** 
🔹 **Si no tienes alguna informacion que requieres, pregunta antes y luego con la respuesta terminas con tu analisis y recomendacion.
antes nada** 
🔹 **Hoy estamos en el kartodromo ** ",
        'requiere_datos' => true,
        'query' => "
            SELECT 
                sk.session_datetime,
                t.nombre as trazado,
                sk.categoria,
                sk.num_kart,
                sk.piloto,
                sk.temp_ambiente,
                sk.temp_pista,
                sk.vel_max,
                sk.temp_motor,
                sk.rpm_max,
                sk.rpm_min,
                sk.aire,
                sk.aguja,
                sk.chicler,
                sk.mejor_tiempo,
                sk.eje,
                sk.caster,
                sk.ackermann,
                sk.camber,
                sk.toe_out,
                sk.vueltas_neumaticos
            FROM sesiones_karting sk
            JOIN trazados t ON sk.id_trazado = t.id
            ORDER BY sk.session_datetime DESC
            LIMIT 1
        "
    ],
    [
        'id' => 2,
        'nombre' => 'Ultimo set del kart',
        'template' => "Estos son los datos de la última sesión:\n\n{datos_sesion}\n\nAnaliza:\n1. Recuerda estos datos para agregar si es necesario\n2. Ten a la mano la temperatura ambiente actual del kartodromo\n3. Impacto de las condiciones ambientales actuales\n4. Sugerencias de mejora basadas en la comparación de la ultima salida y a los cambios ambientales actuales\n5. ",
        'requiere_datos' => true,
        'query' => "
            SELECT 
                sk.session_datetime,
                t.nombre as trazado,
                sk.categoria,
                sk.num_kart,
                sk.piloto,
                sk.temp_ambiente,
                sk.temp_pista,
                sk.vel_max,
                sk.temp_motor,
                sk.rpm_max,
                sk.rpm_min,
                sk.aire,
                sk.aguja,
                sk.chicler,
                sk.mejor_tiempo,
                sk.eje,
                sk.caster,
                sk.ackermann,
                sk.camber,
                sk.toe_out,
                sk.vueltas_neumaticos
            FROM sesiones_karting sk
            JOIN trazados t ON sk.id_trazado = t.id
            ORDER BY sk.session_datetime DESC
            LIMIT 2
        "
    ],
    [
        'id' => 3,
        'nombre' => 'generar script SQL',
        'template' =>   "Genera una consulta SQL para insertar registros en la tabla sesiones_karting. La consulta debe contener los siguientes campos:\n
session_datetime: Fecha y hora exacta de la sesión.
id_trazado: Identificador único del trazado en el que se realizó la sesión.
categoria: Categoría del karting (ejemplo: baby, micro max, mini max, etc.).
num_kart: Número del kart utilizado en la sesión.
piloto: Nombre del piloto que participó.
temp_ambiente: Temperatura ambiente en grados Celsius.
temp_pista: Temperatura de la pista en grados Celsius.
vel_max: Velocidad máxima alcanzada en km/h.
temp_motor: Temperatura del motor en grados Celsius.
rpm_max: Revoluciones por minuto máximas registradas.
rpm_min: Revoluciones por minuto mínimas registradas.
aire: Configuración del aire del carburador.
aguja: Posición de la aguja en el carburador.
chicler: Tamaño del chiclé utilizado.
mejor_tiempo: Mejor tiempo registrado en formato 00:00.00.
eje: Tipo de eje utilizado en la sesión.
caster: Ajuste de ángulo del caster.
ackermann: Configuración del sistema de dirección Ackermann.
camber: Ángulo de caída de las ruedas.
toe_out: Ajuste de divergencia de las ruedas.
vueltas_neumaticos: Cantidad de vueltas recorridas por los neumáticos.\n
Asegúrate de que el mejor tiempo siempre se registre en el formato 00:00.00 y de que todos los valores sean consistentes con los datos reales de la sesión.
                        ",
        'requiere_datos' => false,
        'query' => ""
    ]
];
?>

<div id="pro_modalPrompts" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🤖 Asistente IA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="pro_prompt" class="form-label">Seleccionar Prompt</label>
                    <select id="pro_prompt" class="form-select" onchange="pro_cargarPrompt()">
                        <option value="">Seleccione un prompt...</option>
                        <?php foreach ($prompts as $prompt): ?>
                            <option value="<?php echo $prompt['id']; ?>">
                                <?php echo htmlspecialchars($prompt['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="pro_textarea" class="form-label">Prompt Generado</label>
                    <textarea id="pro_textarea" class="form-control" rows="15" 
                             style="font-family: monospace;" readonly></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="pro_copiarPrompt()">
                    <i class="fas fa-copy me-2"></i>Copiar
                </button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const promptsData = <?php echo json_encode($prompts); ?>;

function pro_abrirModalPrompts() {
    const modal = new bootstrap.Modal(document.getElementById('pro_modalPrompts'));
    modal.show();
    document.getElementById('pro_prompt').value = '';
    document.getElementById('pro_textarea').value = '';
}

function pro_cargarPrompt() {
    const promptId = parseInt(document.getElementById('pro_prompt').value);
    const textarea = document.getElementById('pro_textarea');
    textarea.value = 'Cargando...';

    if (!promptId) {
        textarea.value = '';
        return;
    }

    const prompt = promptsData.find(p => p.id === promptId);
    if (!prompt) {
        textarea.value = 'Error: Prompt no encontrado';
        return;
    }

    $.ajax({
        url: 'controllers/promtsController.php',
        method: 'POST',
        data: { 
            action: 'obtenerDatosSesion',
            promptId: promptId
        },
        dataType: 'json', // Especificamos que esperamos JSON
        success: function(response) {
            console.log('Respuesta recibida:', response); // Para depuración
            if (response && response.status === 'ok' && response.datos) {
                textarea.value = prompt.template.replace('{datos_sesion}', response.datos);
            } else {
                textarea.value = 'Error: ' + (response.mensaje || 'Respuesta inválida del servidor');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error Ajax:', error); // Para depuración
            textarea.value = 'Error al procesar la solicitud: ' + error;
        }
    });
}

function pro_copiarPrompt() {
    const textarea = document.getElementById('pro_textarea');
    textarea.select();
    document.execCommand('copy');
    
    Swal.fire({
        icon: 'success',
        title: '¡Copiado!',
        text: 'El prompt ha sido copiado al portapapeles',
        timer: 1500,
        showConfirmButton: false
    });
}
</script>