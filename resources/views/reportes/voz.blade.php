@extends('layouts.app')

@section('title','Asistente de Voz')

@section('header','Asistente de Voz — Comandos por Reconocimiento de Voz')

@section('content')

<style>
.voz-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    gap: 24px;
}

.mic-btn {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    border: 4px solid #6366f1;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    font-size: 48px;
    cursor: pointer;
    transition: 0.3s;
    box-shadow: 0 8px 30px rgba(99, 102, 241, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.mic-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 12px 40px rgba(99, 102, 241, 0.6);
}

.mic-btn.listening {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    border-color: #dc2626;
    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.6);
    animation: pulse 1.4s infinite;
}

@keyframes pulse {
    0%   { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.6); }
    70%  { box-shadow: 0 0 0 30px rgba(220, 38, 38, 0); }
    100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
}

.help-text {
    color: #64748b;
    font-size: 15px;
    text-align: center;
    max-width: 480px;
    line-height: 1.6;
}

.result-box {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px 30px;
    min-width: 400px;
    max-width: 600px;
    text-align: center;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    color: #0f172a;
}

.command-label {
    background: #e0e7ff;
    color: #3730a3;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.comandos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    max-width: 600px;
    width: 100%;
}

.comando-chip {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    color: #475569;
    text-align: center;
}

.no-support {
    background: #fef3c7;
    color: #92400e;
    padding: 16px;
    border-radius: 10px;
    border: 1px solid #fcd34d;
    text-align: center;
    max-width: 500px;
}
</style>

<div class="voz-container">

    <div id="noSupport" class="no-support" style="display:none;">
        ⚠️ Tu navegador no soporta reconocimiento de voz. Usa Chrome o Edge para esta funcionalidad.
    </div>

    <button
        id="micBtn"
        class="mic-btn"
        onclick="toggleMic()"
        title="Clic para hablar">
        🎤
    </button>

    <div id="resultBox" class="result-box">
        <span id="resultText" style="color:#64748b;">Presiona el micrófono y dicta un comando...</span>
    </div>

    <p class="help-text">
        <strong>Prueba decir:</strong><br>
        "¿Cuántos aprobados hay?", "Dame el total de inscritos",
        "¿Cuántos reprobados hay?", "¿Cuántos grupos hay?",
        "Muéstrame los habilitados"
    </p>

    <div class="comandos-grid">
        <div class="comando-chip">📊 "¿Cuántos aprobados hay?"</div>
        <div class="comando-chip">❌ "Dame los reprobados"</div>
        <div class="comando-chip">👥 "Total de inscritos"</div>
        <div class="comando-chip">🏫 "¿Cuántos grupos hay?"</div>
        <div class="comando-chip">✅ "Muéstrame los habilitados"</div>
    </div>

</div>

<script>
const micBtn = document.getElementById('micBtn');
const resultText = document.getElementById('resultText');
const noSupport = document.getElementById('noSupport');

let recognition = null;
let listening = false;

const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

if (!SpeechRecognition) {
    noSupport.style.display = 'block';
    micBtn.style.display = 'none';
} else {
    recognition = new SpeechRecognition();
    recognition.lang = 'es-ES';
    recognition.interimResults = false;
    recognition.continuous = false;

    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        resultText.textContent = '📝 Escuchado: "' + transcript + '"';
        resultText.style.color = '#0f172a';
        enviarComando(transcript);
    };

    recognition.onerror = function(event) {
        stopListening();
        resultText.textContent = '❌ Error: ' + event.error + '. Intenta de nuevo.';
        resultText.style.color = '#dc2626';
    };

    recognition.onend = function() {
        stopListening();
    };
}

function toggleMic() {
    if (listening) {
        recognition.stop();
        stopListening();
    } else {
        try {
            recognition.start();
            startListening();
        } catch (e) {
            resultText.textContent = '❌ Error al iniciar el micrófono. Recarga la página.';
            resultText.style.color = '#dc2626';
        }
    }
}

function startListening() {
    listening = true;
    micBtn.classList.add('listening');
    micBtn.innerHTML = '🔴';
    resultText.textContent = '🎤 Escuchando...';
    resultText.style.color = '#6366f1';
}

function stopListening() {
    listening = false;
    micBtn.classList.remove('listening');
    micBtn.innerHTML = '🎤';
}

async function enviarComando(comando) {
    const token = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : '{{ csrf_token() }}';

    try {
        const response = await fetch('{{ route("voz.procesar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ comando: comando }),
        });

        const data = await response.json();

        resultText.textContent = '🤖 ' + data.mensaje;
        resultText.style.color = '#0f172a';

        // Síntesis de voz: el navegador lee la respuesta
        if (window.speechSynthesis) {
            const utterance = new SpeechSynthesisUtterance(data.mensaje);
            utterance.lang = 'es-ES';
            utterance.rate = 1.0;
            utterance.pitch = 1.0;
            window.speechSynthesis.speak(utterance);
        }
    } catch (error) {
        resultText.textContent = '❌ Error de conexión con el servidor.';
        resultText.style.color = '#dc2626';
    }
}
</script>

@endsection
