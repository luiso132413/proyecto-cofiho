import express from 'express';

const app = express();
const PORT = process.env.PORT || 10000;

const BACKEND_URL = process.env.BACKEND_URL?.replace(/\/$/, '');

app.get('/', (req, res) => {
    res.json({
        sistema: 'COFIHO V2',
        servicio: 'API Render',
        estado: 'activo'
    });
});

app.get('/api/vehiculos', async (req, res) => {
    if (!BACKEND_URL) {
        return res.status(503).json({
            success: false,
            message: 'BACKEND_URL no configurada'
        });
    }

    try {
        const respuesta = await fetch(
            `${BACKEND_URL}/api/vehiculos`,
            {
                headers: {
                    Accept: 'application/json'
                },
                signal: AbortSignal.timeout(15000)
            }
        );

        if (!respuesta.ok) {
            return res.status(502).json({
                success: false,
                message: 'Error al consultar el backend Laravel'
            });
        }

        const datos = await respuesta.json();

        res.json(datos);

    } catch (error) {
        console.error('Error de conexión:', error.message);

        res.status(502).json({
            success: false,
            message: 'No se pudo conectar con Laravel'
        });
    }
});

app.listen(PORT, '0.0.0.0', () => {
    console.log(`COFIHO API activa en puerto ${PORT}`);
});
