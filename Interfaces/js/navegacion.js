// Sistema de Navegación del Programa
// Maneja la navegación entre todas las ventanas

// Obtener la carpeta base (relativa a la ubicación del script)
function obtenerRutaBase() {
    const rutaActual = window.location.pathname;
    
    // Si estamos en una subcarpeta (clientes, usuarios, etc)
    if (rutaActual.includes('/clientes/') || 
        rutaActual.includes('/usuarios/') || 
        rutaActual.includes('/ventas/') || 
        rutaActual.includes('/entregas/') || 
        rutaActual.includes('/inventario/')) {
        return '../';
    }
    return './';
}

// Definir las rutas basadas en la ubicación actual
const rutas = {
    home: () => obtenerRutaBase() + 'home.html',
    iniciarSesion: () => obtenerRutaBase() + 'iniciar_sesion.html',
    reportes: () => obtenerRutaBase() + 'reportes.html',
    clientes: () => obtenerRutaBase() + 'clientes/clientes.html',
    usuarios: () => obtenerRutaBase() + 'usuarios/usuarios.html',
    ventas: () => obtenerRutaBase() + 'ventas/ventas.html',
    entregas: () => obtenerRutaBase() + 'entregas/entregas.html',
    inventario: () => obtenerRutaBase() + 'inventario/inventario.html'
};

// Funciones de navegación principales
function irA(pagina) {
    if (rutas[pagina]) {
        window.location.href = rutas[pagina];
    } else {
        console.error('Página no encontrada: ' + pagina);
    }
}

function irAlHome() {
    irA('home');
}

function salir() {
    if (confirm('¿Estás seguro de que deseas salir?')) {
        irA('iniciarSesion');
    }
}

// Mapeo de nombres de botones a funciones
const navegaciones = {
    'Home': irAlHome,
    'Inicio': irAlHome,
    'Salir': salir,
    'Ventas': () => irA('ventas'),
    'Usuario': () => irA('usuarios'),
    'Usuarios': () => irA('usuarios'),
    'Reportes': () => irA('reportes'),
    'Entregas': () => irA('entregas'),
    'Clientes': () => irA('clientes'),
    'Inventario': () => irA('inventario'),
    'Productos': () => irA('inventario'),
    'Sucursales': () => irA('home')
};

// Inicializar los botones cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Asignar funcionalidad a todos los botones con clase 'icon-btn'
    const botonesIcono = document.querySelectorAll('.icon-btn');
    botonesIcono.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const titulo = this.getAttribute('title');
            if (navegaciones[titulo]) {
                navegaciones[titulo]();
            }
        });
    });

    // Asignar funcionalidad a botones de acciones (en home)
    const botonesAccion = document.querySelectorAll('.action-card');
    botonesAccion.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const texto = this.innerText.trim();
            if (navegaciones[texto]) {
                navegaciones[texto]();
            }
        });
    });

    // Asignar funcionalidad al botón "Iniciar Sesión"
    const botonesIniciarSesion = document.querySelectorAll('.btn');
    botonesIniciarSesion.forEach(boton => {
        if (boton.innerText.includes('Iniciar Sesión')) {
            boton.addEventListener('click', function(e) {
                e.preventDefault();
                // Validar que los campos no estén vacíos
                const usuario = document.querySelector('input[name="usuario"]');
                const contrasena = document.querySelector('input[name="contraseña"]');
                if (usuario && contrasena && usuario.value && contrasena.value) {
                    irAlHome();
                } else {
                    alert('Por favor complete todos los campos');
                }
            });
        }
        if (boton.innerText.includes('Restablecer')) {
            boton.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (form) form.reset();
            });
        }
    });
});

// Exportar para uso en otros archivos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { irA, irAlHome, salir, navegaciones };
}
