document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const productos = document.querySelectorAll('.producto');
    const noResults = document.getElementById('noResults');
    const graficos = document.querySelectorAll('.grafico');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const hasSearch = searchTerm.length > 0; 
        let hasResults = false;
        
        graficos.forEach(grafico => {
            grafico.style.display = hasSearch ? 'none' : 'block';
        });
        
        productos.forEach(producto => {
            const productName = producto.getAttribute('data-name');
            if (productName.includes(searchTerm)) {
                producto.style.display = 'block';
                hasResults = true;
            } else {
                producto.style.display = 'none';
            }
        });
        noResults.style.display = (hasSearch && !hasResults) ? 'block' : 'none';
    });
    
    searchInput.addEventListener('search', function() {
        if (this.value === '') {
            graficos.forEach(grafico => grafico.style.display = 'block');
            productos.forEach(producto => producto.style.display = 'block');
            noResults.style.display = 'none';
        }
    });
});
function confirmarEliminacion() {
    if (confirm("¿Estás seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.")) {
        document.getElementById("formEliminarCuenta").submit();
    }
}